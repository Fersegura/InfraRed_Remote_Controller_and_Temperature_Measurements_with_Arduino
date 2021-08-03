
# ESP32
---

## In this section are available the same pieces of code developed in other sections with the difference that here are suited for the ESP32 microcontroller

### WiFi

This code makes the ESP32 work as Station first (STA), where it serves via a web server a web page in his local WiFi network.
First the user has to connect to the WiFi network (SSID and PW are user-configurable in the code), then acces via a web browser to the IP address (192.168.4.22) and choose the WiFi network that the device will connect to (and enter the its password). When this is done, the device starts working as an Acces Point (Soft-AP).

In the loop, it keeps scanning for available networks if the usser disconnects the device from the previous configured WiFi network. To do this he has to enter the local network as before and click the disconnect button from the webpage.

The device also saves the SSID and PW of the latest network connected in the FLASH memory and will try to connect to that network first.
