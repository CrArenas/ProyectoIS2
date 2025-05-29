from flask import Flask, request, jsonify
import requests
import joblib
import firebase_admin
from firebase_admin import credentials, firestore
import numpy as np
import os

app = Flask(__name__)

API_KEY = "1234" 

modelo = joblib.load("deteccion_fraude.joblib")

cred = credentials.Certificate("api-flask-deteccion-firebase-adminsdk-fbsvc-0eafe76b56.json")
firebase_admin.initialize_app(cred)
db = firestore.client()


MICROSERVICE_NOTIFICATIONS = "http://localhost:8001/api/send-sms"
headers = {
    "X-API-KEY": API_KEY 
}
@app.before_request
def verificar_api_key():
    api_key_recibida = request.headers.get("X-API-Key")
    if api_key_recibida != API_KEY:
        return jsonify({"message": "Acceso Denegado"}), 403
        
@app.route('/predict', methods=['POST'])
def predecir():
    try:
        datos = request.json  
        
        columnas_requeridas = [f"V{i}" for i in range(1, 29)] + ["amount"]
        if not all(col in datos for col in columnas_requeridas):
            return jsonify({"error": "Faltan columnas en los datos"}), 400
        
        features = np.array([datos[col] for col in columnas_requeridas]).reshape(1, -1)

        prediction = modelo.predict(features)[0]
        fraudulenta = bool(prediction)

        db.collection("transacciones").add({
            "datos": datos,
            "fraudulenta": fraudulenta
        })
        if fraudulenta:
            
            laravel_response = requests.get(MICROSERVICE_NOTIFICATIONS, headers=headers)
            return jsonify(laravel_response.json())

        else: 
            user_id= str(request.headers.get("UserId"))
            MICROSERVICE_TRANSACTIONS = f"http://localhost:8002/api/transactions/{user_id}"
            laravel_response = requests.post(MICROSERVICE_TRANSACTIONS, json=datos, headers=headers)
            return jsonify(laravel_response.json())

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)

    