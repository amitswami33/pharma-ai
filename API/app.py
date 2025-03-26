from flask import Flask, request, jsonify
import pickle
import pandas as pd
import numpy as np

app = Flask(__name__)

# Load the pickle files
with open('model.pkl', 'rb') as model_file:
    model = pickle.load(model_file)

with open('symptom_encoders.pkl', 'rb') as encoders_file:
    symptom_encoders = pickle.load(encoders_file)

with open('disease_encoder.pkl', 'rb') as disease_encoder_file:
    disease_encoder = pickle.load(disease_encoder_file)

with open('medicine_mapping.pkl', 'rb') as medicine_mapping_file:
    medicine_mapping = pickle.load(medicine_mapping_file)

# New endpoint to list all valid symptoms
@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.json
        symptoms = data.get('symptoms', [])

        print("Received symptoms:", symptoms)  # Debugging print

        # Encode the symptoms column-wise
        encoded_symptoms = []
        for col, symptom in zip(symptom_encoders.keys(), symptoms):
            if symptom in symptom_encoders[col].classes_:
                encoded_symptoms.append(symptom_encoders[col].transform([symptom])[0])
            else:
                encoded_symptoms.append(-1)  # Handle unknown symptoms

        print("Encoded symptoms:", encoded_symptoms)  # Debugging print

        # Ensure the input matches the model's expected shape
        input_df = pd.DataFrame([encoded_symptoms], columns=symptom_encoders.keys())
        input_df.fillna(-1, inplace=True)  # Fill missing values

        # Predict the disease
        predicted_disease_idx = model.predict(input_df)[0]
        predicted_disease = disease_encoder.inverse_transform([predicted_disease_idx])[0]

        # Fetch recommended medicines, limit to 4, remove duplicates
        recommended_medicines = list(dict.fromkeys(medicine_mapping.get(predicted_disease, [])))[:4]

        print("Recommended Medicines:", recommended_medicines)  # Debugging print

        return jsonify({
            'recommended_medicines': recommended_medicines  # Disease name is removed
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500


if __name__ == '__main__':
    app.run(debug=True)
