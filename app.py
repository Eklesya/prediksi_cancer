from flask import Flask, request, jsonify
import pickle
import numpy as np

app = Flask(__name__)

# Load model
with open('model_cancer.ipynb', 'rb') as file:
    model = pickle.load(file)

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()

    smoking = data.get('smoking', 0)
    genetic = data.get('genetic', 0)
    alcohol = data.get('alcohol', 0)
    pollution = data.get('pollution', 0)
    obesity = data.get('obesity', 0)

    input_features = np.array([[smoking, genetic, alcohol, pollution, obesity]])

    prediction = model.predict(input_features)
    result = int(prediction[0])  # pastikan integer

    return jsonify({'prediction': result})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
