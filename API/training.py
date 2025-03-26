import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import LabelEncoder
import pickle
import numpy as np

# --- Load Dataset ---
data = pd.read_csv("symptoms_dataset.csv")

# Auto-detect symptom columns (excluding Disease and Medicines columns)
symptom_columns = [col for col in data.columns if "Symptom" in col]
X = data[symptom_columns]
y = data["Disease"]

# Create medicines mapping
medicine_mapping = data.groupby("Disease")[["Medicine 1", "Medicine 2", "Medicine 3"]].apply(
    lambda df: df.values.flatten()).to_dict()

# Encode diseases
disease_encoder = LabelEncoder()
y_encoded = disease_encoder.fit_transform(y)

# Encode symptoms using unique encoders for each column
symptom_encoders = {}
X_encoded = pd.DataFrame()
for col in symptom_columns:
    le = LabelEncoder()
    X_encoded[col] = le.fit_transform(X[col].astype(str))
    symptom_encoders[col] = le

# Split dataset
X_train, X_test, y_train, y_test = train_test_split(X_encoded, y_encoded, test_size=0.3, random_state=42)

# Train model
model = RandomForestClassifier(random_state=42)
model.fit(X_train, y_train)

# Save the model and encoders
with open("model.pkl", "wb") as model_file:
    pickle.dump(model, model_file)

with open("disease_encoder.pkl", "wb") as disease_file:
    pickle.dump(disease_encoder, disease_file)

with open("medicine_mapping.pkl", "wb") as medicine_file:
    pickle.dump(medicine_mapping, medicine_file)

with open("symptom_encoders.pkl", "wb") as symptom_encoders_file:
    pickle.dump(symptom_encoders, symptom_encoders_file)

print("Model, encoders, and medicine mapping saved successfully.")

# --- Testing Section ---
# Load model and encoders
with open("model.pkl", "rb") as model_file:
    model = pickle.load(model_file)

with open("disease_encoder.pkl", "rb") as disease_file:
    disease_encoder = pickle.load(disease_file)

with open("medicine_mapping.pkl", "rb") as medicine_file:
    medicine_mapping = pickle.load(medicine_file)

with open("symptom_encoders.pkl", "rb") as symptom_encoders_file:
    symptom_encoders = pickle.load(symptom_encoders_file)

# Test with new symptoms
new_symptoms = ["Fever", "Headache", "Fatigue"]  # Example symptoms
encoded_symptoms = []

for col, symptom in zip(symptom_columns, new_symptoms):
    if symptom in symptom_encoders[col].classes_:
        encoded_symptoms.append(symptom_encoders[col].transform([symptom])[0])
    else:
        # Handle unknown symptom by assigning neutral value or custom handling
        encoded_symptoms.append(-1)

# Convert encoded symptoms to DataFrame
encoded_symptoms_df = pd.DataFrame([encoded_symptoms], columns=symptom_columns)

# Ensure shape matches model expectations
encoded_symptoms_df.fillna(-1, inplace=True)

# Predict the disease
prediction = model.predict(encoded_symptoms_df)
predicted_disease = disease_encoder.inverse_transform(prediction)[0]

# Fetch recommended medicines
recommended_medicines = medicine_mapping.get(predicted_disease, ["No medicine found"])

print(f"Predicted Disease: {predicted_disease}")
print(f"Recommended Medicines: {', '.join(recommended_medicines)}")
