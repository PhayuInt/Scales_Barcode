#include "HX711.h"
#define DOUT  3
#define CLK  2
HX711 scale(DOUT, CLK);
float Zero,Weight,value,b,c;
int au = 10;

void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200);
  Serial.print("Wait...");
  Serial.println();
  Zero = scale.read_average(50);
 
}

void loop() {
     value = scale.read_average(35);
      b = value-Zero;
      Weight = (((value - Zero)/126));
 // Weight = (scale.read_average(20) - Zero) / au;
     if(b < 250){
         Weight = 0;
    
      }
     else{
     Serial.print("Zero: ");
     Serial.println(Zero);
     Serial.print("value: ");
     Serial.println(value);
     Serial.print("ans: ");
     Serial.println(b);
    
    }
    Serial.print(" Reading: ");
    Serial.print(round(Weight));
    Serial.print(" g");
    Serial.println();
     
  delay(3000);

}
