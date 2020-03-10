#include <esp8266wifi.h>
#include <esp8266httpclient.h>

const char* ssid = "real";
const char* password = "11111111";

void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print("Connecting..");
  }

}

void loop() {
  if (WiFi.status() == WL_CONNECTED) { 
    HTTPClient http; 
    http.begin(“http://jsonplaceholder.typicode.com/users/1"); 
    int httpCode = http.GET(); 
    if (httpCode > 0) { 
      String payload = http.getString(); 
      Serial.println(payload);
    }
  http.end(); 
 
 }
 delay(30000); //Send a request every 30 seconds
}
