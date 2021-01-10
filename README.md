# InfraRed Remote Controller and Temperature Measurements with Arduino
---

In this repository you will find example codes and tests codes we have developed in this 2020-2021 summer project.

## Project stages & Table of Contents

1. [IR Remote Controller](#ir-remote-controller)
2. [Temperature](#temperature)
3. [Arduino power consumption](#arduino-power-consumption)
4. [WiFi and local server connection](#wifi-and-local-server-connection)

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

We had the [*DHT11*](https://www.mouser.com/datasheet/2/758/DHT11-Technical-Data-Sheet-Translated-Version-1143054.pdf) sensor lying around and played with a couple lines of code to see how it worked. Not surprisingly it worked just fine and we didn't have much trouble at all. 

The only important thing to bare in mind is that the DHT11 is a digital sensor and needs to have a "settling time" between two consecutives reads (5 seconds aproximately). 

We follow [this tutorial](https://www.youtube.com/watch?v=hlmSF9xNARU&ab_channel=Programarfacil) as a guide.


### Arduino power consumption

Yesterday we devoted our time to investigate how to make the Arduino consume less current than the regular 20-70 [mA] (we measure that current in *idle* mode and the measurments correspond to Arduino Uno and Mega with ATMega2560 respectively).

We measure the current consumption using a multimeter in series with the power source. The cables were connected trhough one board to the 5V and GND pins of the board being measured in order to conect the multimeter properly.

The first thing we noticed was that using the *Vin* pin rather than the *5V* pin inmediately brought down the current from 20 [mA] to 12 [mA] as explained in [this video](https://www.youtube.com/watch?v=usKaGRzwIMI). Afterward we use the clock divider in *CLKPR* register to slow down the clock. We saw significant changes in current consumption when the clock was up to 4 [MHz], with slower clocks our measurements did not show significant changes (it remained near 5 [mA]). 

Finally we investigated about the [*LowPower library*](https://www.arduino.cc/reference/en/libraries/low-power/). It worked pretty well and the *powerDown* method brought the board's current down from 20 [mA] to roughly 6 [mA] (using the 16 [MHz] clock). However we did not see significant changes using this library while having a slower clock.

To conclude, both methods are useful in a variety of applications where the board is powered using a battery.

**NOTE**: We didn't desolder the always-on LED that shows the board is ON, by doing this you could bring down power consumption by a couple of mA (see the video linked before for more information).  Also the voltage was 5 [V] the whole time, you also can use less input voltage to drop the current consumption.


### WiFi and local server connection

Soon we realized that it would be interesting if the data from the sensors could be stored in a database.

To achive this, we studied a bit of *MySQL* and got familiarized with *XAMPP* and *PHPMyAdmin*, a GUI to quiclky deploy a database with all the information we needed

Furthermore, we learned some *php*, *javascript* and *HTML* to develop a way to comunicate with the database and to display the information in a graph (we intend to use the templates available at [*highcharts*](https://www.highcharts.com/)).

We followed the tutorials from [***Ioticos***](https://www.youtube.com/playlist?list=PLVnDMG-Nwzxl_5B65dlJsTKOa8xoIKqBm) that explain how to use *XAMPP* and *PHPMyAdmin*, how to record data into the database from an *ESP8266* and how to display the information.


