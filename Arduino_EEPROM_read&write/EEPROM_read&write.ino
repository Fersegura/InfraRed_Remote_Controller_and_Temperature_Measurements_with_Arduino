#include <EEPROM.h>

// start reading from the first byte (address 0) of the EEPROM
int address = 0;
byte value;

void setup() 
{
    // initialize serial and wait for port to open:
    Serial.begin(9600);
    while (!Serial) {
        ; // wait for serial port to connect. Needed for native USB port only
    }
    address=2;
    value=12;
    EEPROM.write(address,value);
    value=13;
    address=3;
    EEPROM.write(address,value);
}

void loop() {
    //guardo un dato en la memoria eeprom
    for(int i =0;i<10;i++)
    {
        int valor=EEPROM.read(i);
        Serial.print("en la ubicacion: ");
        Serial.print(i);
        Serial.print(" el dato guardado es: ");
        Serial.println(valor,DEC);
    }
    
    delay(1000);
  
} 
