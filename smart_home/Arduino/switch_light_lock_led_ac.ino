#include "DHT.h"
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>
#include <SPI.h>
#include <MFRC522.h>
#define DHTPIN D3
#define DHTTYPE DHT11
DHT dht(DHTPIN,DHTTYPE);


float humidityData;
float temperatureData;


const char* ssid = "Pavlendovci"; 
const char* password = "pavlendovci5";

const int lampa = 3;
const int led = 13;
const int klima = 2;
const int zamok = 14;

char server[] = "pavlendahome.online";

byte state = 0;

WiFiClient client;    

void setup()
{
 Serial.begin(115200);
  delay(10);
  dht.begin();
  // Connect to WiFi network
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
 
pinMode(lampa, OUTPUT);
pinMode(led, OUTPUT);
pinMode(klima, OUTPUT);
pinMode(zamok, OUTPUT);

  WiFi.begin(ssid, password);
 
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
 
// Start the server
//  server.begin();
  Serial.println("Server started");
  Serial.print(WiFi.localIP());
  delay(1000);
  Serial.println("connecting...");
 }
void loop()
{ 
  humidityData = dht.readHumidity();
  temperatureData = dht.readTemperature(); 
  Sending_To_phpmyadmindatabase(); 
  delay(2000); 
 }

 void Sending_To_phpmyadmindatabase()   
 {
   if (client.connect(server, 80)) {
    Serial.println("connected");

    client.print("GET /dht11.php?humidity=");
    client.print(humidityData);
    client.print("&temperature=");
    client.print(temperatureData);
    client.print(" ");      
    client.print("HTTP/1.1");
    client.println();
    client.println("Host: pavlendahome.online");
    client.println("Connection: close");
    client.println();
    
    while (client.connected()) {
      if (client.available()) {
        String line = client.readStringUntil('\n');
        
        Serial.println(line);

        if (line == "\r") {
          String response = client.readString();

          Serial.println("Server response:");
          Serial.println(response);

          int newState = response.toInt();

          updateState(newState);
        }
      }
    }
  } else {
  
    Serial.println("connection failed");
  }
 }

void updateState(byte s) {
  // stav sa nezmenil
  if (state == s) {
    return;
  }

  if (s & B00000001) {
    digitalWrite(lampa, HIGH);   
  } else {
     digitalWrite(lampa, LOW);    
  }

  if (s & B00000010) {
      digitalWrite(led, HIGH); 
  } else {
       digitalWrite(led, LOW); 
  }

  if (s & B00000100) {
      digitalWrite(zamok, HIGH); 
      
     
  } else {
       digitalWrite(zamok, LOW); 
           
  }

  if (s & B00001000) {
      digitalWrite(klima, HIGH); 
  } else {
       digitalWrite(klima, LOW);
  }

  state = s;
}
 
