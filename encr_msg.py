#!/usr/bin/env python

import socket
import sys
import urllib2
import base64
from Crypto import Random
from Crypto.Cipher import Blowfish

BS = 8
pad = lambda s: s + (BS - len(s) % BS) * chr(BS - len(s) % BS)
unpad = lambda s : s[0:-ord(s[-1])]

def make_encr_str(raw):    
    mykey=base64.urlsafe_b64decode('BSjocT2Ik9wEi3UP7FvFeyye8i2oqiuB_mE7dYBu6mE=')
    iv = Random.new().read(8)
    obj=Blowfish.new(mykey,Blowfish.MODE_CBC,iv) 
    return base64.urlsafe_b64encode(iv+obj.encrypt(pad(raw)))

def getserial():
  # Extract serial from cpuinfo file
  cpuserial = "0000000000000000"
  try:
    f = open('/proc/cpuinfo','r')
    for line in f:
      if line[0:6]=='Serial':
        cpuserial = line[10:26]
    f.close()
  except:
    cpuserial = "ERROR000000000"
  return cpuserial

def gettoken():
  cpuserial=getserial()
  params='serial='+getserial()+'&action=99'
  send_this='c='+make_encr_str(params)
  response=urllib2.urlopen('http://www.d-jundt.org/IoT/log_it.php?'+send_this).read()
  startpos=response.find('<div id="contentArea">')+22
  response=response[startpos:] #cut formatting in front
  startpos=response.find('</div>')
  response=response[:startpos]
  return response.lstrip().rstrip()

def send_msg(action):
    token=   gettoken()
    cpuserial=getserial()
    params='serial='+getserial()+'&action='+str(action)+'&token='+token+'&paddingreserve'
    send_this='c='+make_encr_str(params)
    return urllib2.urlopen('http://www.d-jundt.org/IoT/log_it.php?'+send_this).read()
