#include <WiFi.h>
#include <PubSubClient.h>
#include <ArduinoJson.h>

const char* ssid = "VotreWiFi";
const char* password = "VotreMotDePasse";
const char* mqtt_server = "172.16.112.1";

WiFiClient espClient;
PubSubClient client(espClient);

void callback(char* topic, byte* payload, unsigned int length) {
    String message = "";
    for (int i = 0; i < length; i++) {
        message += (char)payload[i];
    }
    
    Serial.println("Message reçu:");
    Serial.println(message);
    
    // Parser le JSON
    DynamicJsonDocument doc(2048);
    deserializeJson(doc, message);
    
    JsonArray steps = doc.as<JsonArray>();
    
    for (JsonObject step : steps) {
        String type = step["type"];
        
        if (type == "M") {
            float angle = step["angle"];
            float duree = step["duree"];
            // Bouger le servomoteur
            Serial.printf("Mouvement: angle=%f, durée=%f\n", angle, duree);
        }
        else if (type == "A") {
            String texte = step["texte"];
            float duree = step["duree"];
            // Afficher sur l'écran
            Serial.printf("Affichage: %s, durée=%f\n", texte.c_str(), duree);
        }
        else if (type == "S") {
            String fichier = step["fichier"];
            int volume = step["volume"];
            float duree = step["duree"];
            // Jouer le son
            Serial.printf("Son: %s, volume=%d, durée=%f\n", fichier.c_str(), volume, duree);
        }
    }
}

void setup() {
    Serial.begin(115200);
    WiFi.begin(ssid, password);
    client.setServer(mqtt_server, 1883);
    client.setCallback(callback);
}

void reconnect() {
    while (!client.connected()) {
        if (client.connect("ESP32Client")) {
            client.subscribe("choregraphie/data");
        }
    }
}

void loop() {
    if (!client.connected()) {
        reconnect();
    }
    client.loop();
}