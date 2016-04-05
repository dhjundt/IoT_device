# -*- coding: utf-8 -*-
"""
Rasperry Pi 
@author: djundt - March 2016
Coursera class - programming for capstone project
https://www.coursera.org/specializations/iot
"""

# include libraries
import Queue as qu
import threading as td
import RPi.GPIO as GPIO
import time

#include website sub-routines
import encr_msg

#globals
secretcode=['1','2','3','7','8','9']
codeinput=[]
codetimeout=10 #after first button, need to finish in time
tminbutton=0.05 #minimum time a button needs to be pressed to be accepted
tcodestart=0.0
prevval=(False,' ',0.0)
tstart=0.0
debug=True

# use GPIO connector numbering (B+)
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

#setup row outputs
rows=[10,9,11,5]
for i in rows:
    GPIO.setup(i,GPIO.OUT, initial=GPIO.LOW)
#LED output
GPIO.setup(24,GPIO.OUT, initial=GPIO.LOW)

#RELAY output
GPIO.setup(23,GPIO.OUT, initial=GPIO.LOW)

#setup col inputs
cols=[6,13,26]
for c in cols:
    GPIO.setup(c,GPIO.IN,pull_up_down=GPIO.PUD_DOWN)  #1-*; 2-0; 3-#

#setup closed switch input
GPIO.setup(18,GPIO.IN,pull_up_down=GPIO.PUD_DOWN)

#setup motion input
GPIO.setup(25,GPIO.IN,pull_up_down=GPIO.PUD_DOWN)

def printif(text):
    global debug
    if debug and text<>'':
        print text
# end print if

def setLED(on):
#setLED is used to show a key is pressed or code was wrong
    GPIO.output(24,on)
# end def setLED

def blinkLED():
#after wrong input,call this to blink for 3sec
    for i in range(15):
        setLED(True)
        time.sleep(0.1)
        setLED(False)
        time.sleep(0.1)    
# end def blinkLED

def pushbutton():
#simulates the button being pushed, initiating motion
    GPIO.output(23,True)
    time.sleep(1)
    GPIO.output(23,False)
# end def pushbutton

def isclosed():
#checks if door is closed
    return GPIO.input(18)
# end def isclosed

def current_now():
#to see if momentary threshold for current (motion) is exceeded
    return GPIO.input(25)
# end def current_now

def moving():
    # do it three times to discriminate against noise better
    initial=0.0
    for i in range(10000):
        initial=initial+1.0*current_now()
    test1=(initial>0.175)
    time.sleep(0.01)
    initial=0.0
    for i in range(10000):
        initial=initial+1.0*current_now()
    time.sleep(0.01)
    test2=(initial>0.175)
    time.sleep(0.01)
    initial=0.0
    for i in range(10000):
        initial=initial+1.0*current_now()    
    test3=(initial>0.175)
    return (test1 and test2 and test3)
# end def moving

def reset_keypad():
# restore keypad to make it ready for fresh input
    global tcodestart, codeinput,prevval, tstart, focus_on_keypad
    tcodestart=0
    codeinput=[]
    prevval=(False,' ',0.0)
    tstart=0
    focus_on_keypad=False
    setLED(False)

def readkey():
#    report tuple (button pressed, char, time pressed)
#    only reports character if 'button pressed'=False (when finger lifted)
#    and button had been pressed for a minimum amount of time
    keymap=[['1','2','3'],['4','5','6'],['7','8','9'],['#','0','#']]
    global prevval, tstart, tminbutton
    previous=prevval
    ans=' '
    for i in range(len(rows)):
        GPIO.output(rows[i],True)
        for j in range(len(cols)):
            if (GPIO.input(cols[j])):
                ans=keymap[i][j]
        GPIO.output(rows[i],False)
    if ans==' ': #not pressed now
        if (previous[0]): #first no longer pressed
            setLED(False)
            if time.time()-tstart>tminbutton:
                prevval=(False,previous[1],time.time()-tstart)
            else: #too short
                prevval=(False,' ',0.0)
        else: 
            prevval=(False,' ',0.0)
    else: #pressed now
        if (previous[0]): #also previously
            prevval = (True,ans,0.0)
        else:
            tstart=time.time()
            setLED(True)
            prevval = (True,ans,time.time()-tstart)            
    return prevval
# end def readkey

def input_started():
# this does a sweep and checks if any key pressed (quick)
    return(readkey()[0])

def enter_key():
# called once a key is pressed
# will return true as soon as key released if it is #
    timeout=60 #should never occur
    ans='some value'
    if input_started():
        t0=time.time()
        while (time.time()-t0)<timeout:
            ans=readkey()
            if ans[2]<>0.0:
                break
        return (ans[1]=='#')
    else:
        return False
    
def checkcode(secret):
#   calls readkey() and checks if correct after enter is hit
#   either reports empty string (still waiting for more input)
#   or 'correct' or 'wrong' or 'timeout' if it took longer than codetimeout
    global codetimeout, tcodestart, codeinput
    if ((time.time()-tcodestart>codetimeout) and (tcodestart>0.0)):
        blinkLED()
        reset_keypad()
        return 'timeout'
    else: #not timed out
        (on,char,ton)=readkey() #do one sweep
        if (on==True or char==' '): #no entry - no action
            return ''
        else: #a new key was pressed
            if (char=='#'): #check for correct input
                if (codeinput==secret):
                    reset_keypad()
                    return 'correct'
                else: # enter but not correct
                    reset_keypad()
                    blinkLED()
                    return 'wrong'
            else: # a key other than enter pressed
                if (len(codeinput)==0):
                    tcodestart=time.time()
                codeinput.append(char)
                return ''

def enter_code(secret):
# called once a key is pressed (alternate to enter_key()
# will return string after code entry complete or timed out
    if input_started():
        while True:
            ret=checkcode(secret)
            if ret<>'':
                break
        return ret
    else:
        return ''

def web_messages():
# initiates work on the other thread to send a get request to web server
    while True:
        message_to_transmit=q.get()        
        ret=encr_msg.send_msg(message_to_transmit)
        q.task_done() #to remove task from queue
# end def web_messages

# start of main program
q=qu.Queue()
t=td.Thread(target=web_messages)
t.daemon=True
t.start()
while True:
    if(isclosed()):
        #isclosed
        if moving():
            printif ('it started moving while closed')
            setLED(True) #light LED while moving
            # door is opening by push button
            while moving():
                time.sleep(0.3) #wait until not moving
            setLED(False) #turn LED off now
            time.sleep(1.5)
            #should be closed and not moving
            if(not moving() and not isclosed()):
                q.put_nowait(0)
                printif ('case 0 open by button - success')
            else:
                q.put_nowait(1)
                printif ('case 1 open by button - failure')
            # end door opening by push button
        msg=checkcode(secretcode) #this will take a while if button pressed
        printif (msg)
        if (msg=='correct'):                
            pushbutton() #activate
            setLED(True) #light LED while moving
            time.sleep(0.5) #allow motor to start
            while moving():
                time.sleep(0.3) #wait until not moving
            time.sleep(1.5)
            #should be open and not moving
            reset_keypad()
            if(not moving() and not isclosed()):
                q.put_nowait(2)
                printif ('case 2 open by keypad - success')
            else:
                q.put_nowait(3)
                printif ('case 3 open by keypad - failure')
                #end correct
        #end msg<>'' (i.e. there was some input on keyboard)
        elif (msg=="wrong"):
            reset_keypad()
            q.put_nowait(8)
            printif ('case 8 open but wrong code')
            #end wrong
        elif (msg=="timeout"):
            reset_keypad()
            q.put_nowait(9)
            printif ('case 9 open but timeout')
            #end wrong           
        #end isclosed
    else:
        #not isclosed i.e. open
        if enter_key():
            #key pressed - try to close door now
            pushbutton() #activate
            setLED(True) #light LED while moving
            time.sleep(0.5) #allow motor to start
            while moving():
                time.sleep(0.3) #wait until not moving
            time.sleep(1.5)
            setLED(False) #turn LED off now
            #should be closed and not moving
            if(not moving() and isclosed()):
                q.put_nowait(6)
                printif ('case 6 close by keypad - success')
            else:
                q.put_nowait(7)
                printif ('case 7 close by keypad - failure')
            reset_keypad()
            #end key pressed - try to close door now
        if moving():
            printif ('it started moving while open')
            setLED(True) #light LED while moving
            # door is closing by push button
            while moving():
                time.sleep(0.3) #wait until not moving
            time.sleep(1.5)
            #should be closed and not moving
            reset_keypad()
            if(not moving() and isclosed()):
                q.put_nowait(4)
                printif ('case 4 close by button - success')
            else:
                q.put_nowait(5)
                printif ('case 5 close by button - failure')
            reset_keypad()
            # end door closing by push button
        #end not isclosed
#end while True
