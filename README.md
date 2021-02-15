# InfraRed Remote Controller and Temperature Measurements with Arduino
---
***DISCLAIMER***: This is sort of a blog where we show what we did in the time between 2020 and 2021. It is not a full project nor a specific application development. We are only sharing interesting stuff we studied and what troubles we faced while doing so.

In this repository you will find example codes and tests codes we have developed.

## Project stages & Table of Contents

1. [IR Remote Controller](#ir-remote-controller)
2. [Temperature](#temperature)
3. [Arduino power consumption](#arduino-power-consumption)
4. [WiFi and local server connection](#wifi-and-local-server-connection)
5. [Displaying results on a website](#displaying-results-on-a-website)
6. [Connecting ESP8266 to the internet](#connecting-esp8266-to-the-internet)
7. [Going beyond with the website](#going-beyond-with-the-website)
8. [Improvements to the ESP8266 algorithm](#improvements-to-the-esp8266-algorithm)
9. [Future modifications](#future-modifications)
10. [MQTT](#mqtt)

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

We devoted some time to investigate how to make the Arduino consume less current than the regular 20-70 [mA] (we measure that current in *idle* mode and the measurments correspond to Arduino Uno and Mega with ATMega2560 respectively).

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


### Displaying results on a website

Ultimately we expected to showcase different graphs and images in an organized and nice-looking website. We had to learn how to upload our local server to an interntet hosting service. For this we decided to go with [*000webhost*](https://000webhost.com/) beacause it provided website and database hosting services.

You can check our [site](https://irresponsible-toolb.000webhostapp.com/index.php). It only displays an illustrative graph of what we expect to have in the future with the *Arduino* sensors information. The template of the site you can find it in [*colorlib*](https://colorlib.com/wp/free-bootstrap-admin-dashboard-templates/) (also uploaded as <code>.zip</code> in the <code>./Pagina_web/</code> directory. We used the *AdminLTE 3*.

Recently we managed to fetch information from the DB thanks to the *ioticos* and [*ELECTRONOOBS*](https://www.youtube.com/watch?v=dMSCVWquXhs&t=759s) tutorials and materials.

The result is prety satisfactory. You can check out the files in the <code>./Pagina_web/</code> directory of this repository.

***NOTE***: Please notice that in order to work, the relative paths of the files and the names must match the files locations inside the page directory.


### Connecting ESP8266 to the internet

The last fundamental step to set the sensors to log information to the database via WiFi, is to connect the [*ESP8266*](https://www.espressif.com/sites/default/files/documentation/0a-esp8266ex_datasheet_en.pdf) microcontroller to the house WiFi network.

The board we used is the [*Nodemcu Wifi Esp8266 Lua*](https://articulo.mercadolibre.com.ar/MLA-726650916-nodemcu-wifi-esp8266-lua-gpio-esp12f-4mb-uart-arduino-_JM#position=1&type=item&tracking_id=704c583a-3deb-4798-b04b-55097ce67604).

After reading the documentation of the [*ESP8266 Arduino Core*](https://github.com/lrmoreno007/ESP8266-Arduino-Spanish) we knew that the microcontroller had two working modes: 

1. Soft Access Point.
2. Station.

We decided that in order to get the most out of the ESP8266, first it had to initiate it in *AP* mode and list all the networks available so the user can choose to which connect from. After that, the microcontroller enters in *station* mode and then statrts sensing temperature and humidity data and sending it to the database. We inspired our code in [this](https://www.youtube.com/watch?t=750&v=TB7LmR9h-NA&feature=youtu.be) tutorial.

A useful function we implemented is that the microcontroller saves in its *EEPROM* the *SSID* and *password* of the last connected network. This is useful for example if the power goes of, when it's turn on again the device tries to connect to the network without needing the user to configure it again.

All the user configuration (selecting the correct network (*SSID*) and introducing the network *password*) is done with the user connecting to the device's network (while in *AP* mode) and enterning to a specific site (determined by the *local IP adress* saved in the microcontroller code, in this case is: 192.168.4.22) that shows all the availble information.


### Going beyond with the website

In order to make a more professional website, we decided to implement a user managment system. The users now only have access to the index page without logging in. If the user wants to visit the other pages it has to either log in or register as a new user. The system is a bit rudimentary for the moment but we are looking forward adding more and better features (as for example logging in with G+ or Facebook).


### Improvements to the ESP8266 algorithm

We tried to optimize the software using interrupts, a statistical approach to the data collected and improving some new features.

The first thing we did was upgrading the user interface so the device can be disconnected from the internet via a web page with a disconnect button. 

Secondly, we did not like the deviation that temperature and humidity sensed values had. To solve it, we decided to collect 20 values, calculate the average and then uploading them to the database. This helps to upload less values to the database (saving not only space in the DB but also bandwith due to less HTTP requests). 

In the third place, we included four buttons so the user can manually change the state of the relays. This is useful if the device disconnects from the internet and can't read the DB anymore. 

Finally, we researched how to use timer interrupts. We did not find very useful documentation, but used the example codes of [this](https://github.com/G6EJD/ESP_Interrupt_Examples/blob/master/ESP8266_Timer_Interrupts.ino) repository to find out how they work. However, many people had trouble while using timer interrupts and WiFi connectiom (sometimes the board crashes or disconnects and never re-connect again). We experienced the first problem, the board enter in *panic* mode and printed some message error in the terminal. We suspected that te reason this happened, is because the *Interrupt Service Routine* (ISR) exceeded the *Watchdog Timer* (WDT) and made the microcontroller crash. The first solution we tried, was adding the *yield()* function (similar to *delay(0)* or *delay()*) in the ISR, but it kept crashing (we added several yield() in many other parts of the code and resulted in the same error). The second solution, was creating a special ISR, that the only thing it did was toggling a flag, that was used in a conditional in the main loop function to excecute the former ISR function. This solved the problem temporarily, however we did not let the microcontroller run for several hours. We are not satisfied with the solution and we are looking forward finding a better way to deal with it.

The WiFi connection uses the ESP8266 *timer 0*, so there is only one timer to freely use (*timer 1*). We will try our best to implement the sensing function with timer1. However, we feel confindent about the *Tickers* library as explained [here](https://circuits4you.com/2018/01/02/esp8266-timer-ticker-example/) to deal with "interrupts" in the future or in situations when you have more than one callback function. 


### Future modifications

We are always thinking in whats next. In the [***issues***](https://github.com/OtroCuliau/InfraRed_Remote_Controller_and_Temperature_Measurements_with_Arduino/issues) section you can watch what we are planning to do next in the various parts of the project.


### MQTT 

We worked all this time using *HTTP* requests to comunicate to the online DB. Doing further research, we found out that the most wide spreaded way of connecting many devices with the internet was [***MQTT***](https://en.wikipedia.org/wiki/MQTT). The protocol basically requires three elements a publisher to a topic, a broker and a subscriber to that topic. Nontheless, there was a lot of information to assimilate and took us a while to understand what challenges we were facing. Our first step was to follow [this](https://www.youtube.com/playlist?list=PL2xmtLUbEuglyRtmmbp8S8qO8qa4suPMS) tutorials in order to understand how it worked and to get used to the new tools that are needed. In conclusion, we are certain that MQTT is the ultimate protocol to implement in a microcontroller due to its several advantages in speed and portability.
