# IoT_device
### Coursera class
Coursera capstone project for interfacing to garage door and logging activity
Course taken in Spring 2016
The specialization is [Introduction to the Internet of Things and Embedded Systems] offered by the University of California, Irvine.

The specialization consists of 6 classes. The Arduino platform (including a primer in c programming) and the Raspberry pi platform (python) was introduced.
The class offering is at a modest level of sophistication, and the homework assignments were aimed at a beginner. 
### Capstone project
The capstone project asked to design an IoT device of our own choosing, and this repository contains my programming code. The program runs on the Rasperry pi and is written in python. It interfaces with sensors and actuators to interact with an existing electric garage door opener. A [video] showing the implementation can be seen on YouTube.
The homework assignments for the capstone class are here:
 - week 1: [Top level requirement]
 - week 2: [Specification]
 - week 3: [Testing protocol]

A more detailed description of the [design and testing] of components was not part of the Coursera class, but hey, once you do the work, you might as well write it up.
### Programs
The Raspberry pi program is *Main_Garage_Door.py*. It handles the inputs (keypad, sensors), the actuator (LED, SSR to simulate button press) and the web server communication.
The website code is in php. The IoT device interacts with the page [*log_it.php*]. First, a token is requested after supplying the Serial number (encrypted). If successfully decrypted, the server generates a random token, sends it back and stores it in a mysql table associated with the Serial number of the IoT device. Then, the IoT device sends the information to be stored. The server makes an entry in the database only if the Serial number and the token match with the record in the database. 

Finally, the user can look at his log (no login required now) by looking at the page [*doorlog.php*]. The serial number is the one of my IoT device.



[Introduction to the Internet of Things and Embedded Systems]: <https://www.coursera.org/learn/iot>
 [video]:<https://www.youtube.com/watch?v=A8CVJ2s7bAk>  
[Top level requirement]:<http://www.d-jundt.org/pdf/Week1_Specification.pdf>
[Specification]:<http://www.d-jundt.org/pdf/IoT_capstone_wk2.pdf>
[Testing protocol]:<http://www.d-jundt.org/pdf/IoT-capstone-wk3.pdf>
[design and testing]: <http://www.d-jundt.org/pdf/IoTDetailed_design.pdf>
[*log_it.php*]: <http://www.d-jundt.org/IoT/log_it.php>
[*doorlog.php*]: <http://www.d-jundt.org/IoT/doorlog.php?serial=00000000ff8110f7>