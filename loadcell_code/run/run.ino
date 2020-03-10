
#include "HX711.h"
#define calibration_factor -3800 // เอาค่าที่ได้จากการปรับ มาใส่ตรงนี้
#define DOUT  3
#define CLK  2
HX711 scale(DOUT, CLK);
void setup() {
  Serial.begin(9600);
  Serial.println("ArduinoAll Calibrating...");
  scale.set_scale(calibration_factor); // ปรับค่า calibration factor
  scale.tare(); //รีเซตน้ำหนักเป็น 0
  Serial.println("OK Start :");
}
void loop() {
  Serial.print("Reading: ");
  Serial.print(scale.get_units(), 2); //แสดงผลน้ำหนัก 2 ตำแหน่ง
  Serial.println(" kg");
  delay(500);
}
