#include <ESP32Servo.h>
#include <Wire.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <HardwareSerial.h>
#include <DFRobotDFPlayerMini.h>
#include <WiFi.h>
#include <PubSubClient.h>
#include <ArduinoJson.h>


int getSoundNumber(const String& name) {
  if (name == "Alarme.mp3") return 1;
  if (name == "Applaudissement.mp3") return 2;
  if (name == "Atchoum.mp3") return 3;
  return -1;
}

// ================== CONFIG ==================
const char* ssid = "twitch.tv/captainxlama";
const char* password = "GUACAM0LE";

const char* mqtt_server = "mqtt.latetedanslatoile.fr";
const int mqtt_port = 1883;
const char* mqtt_user ="Epsi";
const char* mqtt_pass ="EpsiWis2018!";

// ================== OBJETS ==================
WiFiClient espClient;
PubSubClient client(espClient);

// Servo
Servo servo;
const int SERVO_PIN = 12;

// OLED
#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, -1);
const int SDApin = 33;
const int SCLpin = 25;

// Audio
HardwareSerial mp3Serial(2);
DFRobotDFPlayerMini player;

// ================== UTILS ==================
String cleanMQTTMessage(byte* payload, unsigned int length) {
  String msg = "";
  for (unsigned int i = 0; i < length; i++) {
    char c = (char)payload[i];
    if (c != '\n' && c != '\r') msg += c;
  }
  return msg;
}

// ================== CALLBACK MQTT ==================
void callback(char* topic, byte* payload, unsigned int length) {
  Serial.println("\n=== Message MQTT reçu ===");

  String jsonStr = cleanMQTTMessage(payload, length);
  Serial.println("Message nettoyé:");
  Serial.println(jsonStr);

  DynamicJsonDocument doc(4096);
  if (deserializeJson(doc, jsonStr)) {
    Serial.println("❌ Erreur JSON");
    return;
  }

  // ========= MOUVEMENTS =========
  JsonArray mouvements = doc["mouvements"];
  for (JsonObject mvt : mouvements) {

    float angle = mvt["MvtAngle"].as<float>();
    float duree = mvt["MvtTime"].as<float>();

    Serial.printf("→ Mouvement: %.2f° pendant %.2fs\n", angle, duree);

    servo.write(angle);
    delay(duree * 1000);

    servo.write(90); // retour position neutre
    }


  // ========= AFFICHAGES =========
  JsonArray affichages = doc["affichages"];
for (JsonObject aff : affichages) {

  const char* texte = aff["AffText"];
  float duree = aff["AffTime"].as<float>();

  Serial.printf("→ Affichage: %s pendant %.2fs\n", texte, duree);

  display.clearDisplay();
  display.setCursor(0, 0);
  display.setTextWrap(true);
  display.setTextSize(1);                 // ✅ IMPORTANT
  display.setTextColor(SSD1306_WHITE);

  display.println(texte);
  display.display();                      // ✅ PUSH VERS L'ÉCRAN

  delay(duree * 1000);

  display.clearDisplay();                 // Nettoyage après affichage
}

  // ========= SONS =========
JsonArray sons = doc["sons"];
for (JsonObject son : sons) {

  String file = son["SonNote"];
  int volume = son["SonVolume"];

  int track = getSoundNumber(file);

  if (track == -1) {
    Serial.printf("❌ Son inconnu : %s\n", file.c_str());
    continue;
  }

  Serial.printf("→ Son: %s → piste %d\n", file.c_str(), track);

  player.volume(volume);
  player.play(track);

  delay(3000); // durée du son (simple pour test)
}

  Serial.println("=========================");
}

// ================== MQTT ==================
void reconnect() {
  while (!client.connected()) {
    Serial.print("Connexion MQTT...");
    if (client.connect("ESP32-Bisik", mqtt_user, mqtt_pass)) {
      Serial.println("OK");
      client.subscribe("Bisik");
    } else {
      Serial.println("Échec, retry 5s");
      delay(5000);
    }
  }
}

// ================== SETUP ==================
void setup() {
  Serial.begin(115200);

  // Servo
  servo.attach(SERVO_PIN);
  servo.write(90); // position neutre
  Serial.println("✅ Servo prêt");


  // OLED
  Wire.begin(SDApin, SCLpin);

  if (!display.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
    Serial.println("❌ Écran OLED non détecté");
    while (true);
  }

  display.clearDisplay();
  display.setTextSize(1);
  display.setTextColor(SSD1306_WHITE);
  display.setCursor(0, 0);
  display.println("OLED prêt");
  display.display();

  Serial.println("✅ Écran prêt");


  // Audio
  mp3Serial.begin(9600, SERIAL_8N1, 16, 17);
  if (player.begin(mp3Serial)) {
    player.volume(20);
  }

  // WiFi
  WiFi.begin(ssid, password);
  Serial.print("WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi OK");

  // MQTT
  client.setServer(mqtt_server, mqtt_port);
  client.setCallback(callback);
  client.setBufferSize(4096);
}

// ================== LOOP ==================
void loop() {
  if (!client.connected()) reconnect();
  client.loop();
}
