# InfraRed Remote Controller and Temperature Measurements with Arduino
---

In this repository you will find example codes and tests codes we have developed in this 2020-2021 summer project.

## Project stages & Table of Contents

1. [IR Remote Controller](#ir-remote-controller)
2. [Temperature](#temperature)

### IR Remote Controller

 Firstly, we wanted to turn on and off an air conditioner. We investigated [*ir protocols*](http://www.diegm.uniud.it/bernardini/Laboratorio_Didattico/2016-2017/2017-Telecomando/ir-protocols.html)  to learn about them and to know which where the most widespread among home appliances.
 Soon we discover there are several different protocols and testing with Arudino module based on the [*VS1838*](https://www.alldatasheet.es/datasheet-pdf/pdf/1132466/ETC2/VS1838.html) sensor and a TV remote control we realized that the ***NEC*** protocol is the most common one.
 
 However, AC's don't work like this. Instead, they use a way longer protocol which we could not find its name. 
 
 Here we encounter two problems: 
 
 - The library we were using ([*Arduino-IRremote*](https://github.com/Arduino-IRremote/Arduino-IRremote)) had a reciever buffer limit of 100 "symbols" (we will talk about what the "symbols" are in a minute).
 - The AC (and here we mention that there are a lot of AC's that behave similarly) had a much longer message and didn't belong to any ir protocol we studied so far.
 
 Both problems are related, in the way that with the help of an osciloscope we saw the message transmitted by the AC's remote and didn't fully fited to the protocols we studied and when we tried to capture the raw "symbols" recieved and send them to the AC, it wouldn't respond. So making a new research and further investigating the libraries recomendations we discovered that AC's do not comply with standard ir protocols. To solve this problem first we downloaded the code provided [here](https://www.analysir.com/blog/2014/03/19/air-conditioners-problems-recording-long-infrared-remote-control-signals-arduino) and printed the lenght of the array. We realized that it had 227 "symbols" and that the "symbols" represented the time in microseconds between two edges for a PWM signal with a carrier frecuency of 38[kHZ].
 
 Now there was a new challenge. The variables captured during the *loop* of the Arduino code were saved in the [*SRAM*](https://playground.arduino.cc/Learning/Memory/) memory and soon we ran out of available memory and the program crashed. The apparent solution was pre-recording the *on* and *off* codes and save them as global variables. 
 
 Going a bit further, we discovered that there is a way to save variables (globals and/or static only) in the *Flash* memory by using the [PROGMEM](https://www.arduino.cc/reference/en/language/variables/utilities/progmem/) function. 
 
 Now we managed not only to turn on and off the AC but also there is the posibility to save future codes to gain more control over the appliance.
 
 
### Temperature

Not implemented yet.
