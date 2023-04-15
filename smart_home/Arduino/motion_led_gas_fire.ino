#include <virtuabotixRTC.h>

const int MOTION_SENSOR_PIN = 2;   
const int LED_PIN           = 3;   
int motionStateCurrent      = LOW; 
int motionStatePrevious     = LOW; 
const int gasLed = 9;
const int gasLed2 = 10;
const int buzzer = 11;


virtuabotixRTC myRTC(6, 7, 8);

void setup() {
  Serial.begin(9600);              
  pinMode(MOTION_SENSOR_PIN, INPUT); 
  pinMode(LED_PIN, OUTPUT);  
  //myRTC.setDS1302Time(18, 11, 9, 3, 22, 3, 2023);
  pinMode(gasLed, OUTPUT);
  pinMode(gasLed2, OUTPUT);
  pinMode(buzzer, OUTPUT);

  

}

void loop() {

   myRTC.updateTime();
   gas_fire();
   pohyb();
   delay(1000);


}

void pohyb(){

   if (myRTC.hours >= 22 && myRTC.hours <=6){

  motionStatePrevious = motionStateCurrent;                          
  motionStateCurrent  = digitalRead(MOTION_SENSOR_PIN);            
  

  if (motionStatePrevious == LOW && motionStateCurrent == HIGH) {    
    Serial.println("Motion detected!");
    digitalWrite(LED_PIN, HIGH);
   
  }
  else
  if (motionStatePrevious == HIGH && motionStateCurrent == LOW) {    
    Serial.println("Motion stopped!");
    digitalWrite(LED_PIN, LOW);  
  }
  }

}


void gas_fire(){
  int val;
  val=analogRead(0);

  int sensorValue = analogRead(A1);
  
   
   if (val > 800)
  {
    digitalWrite(gasLed, HIGH);
    digitalWrite(gasLed2, LOW);
    tone(buzzer, 2000, 500);
  }
  else
  {
    digitalWrite(gasLed, LOW);
    digitalWrite(gasLed2, HIGH);
    noTone(buzzer);
  }

  if (sensorValue > 9)
  {
    digitalWrite(gasLed, HIGH);
    digitalWrite(gasLed2, LOW);
    tone(buzzer, 2000, 900);
  }
  else
  {
    digitalWrite(gasLed, LOW);
    digitalWrite(gasLed2, HIGH);
    noTone(buzzer);
  }
}
