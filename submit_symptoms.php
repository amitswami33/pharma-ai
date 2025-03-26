<?php
// cURL setup to send data to Python API
$ch = curl_init();

// API URL (Replace with your Python API's URL)
$api_url = 'http://localhost:5000/api/endpoint';  // Example: Flask running on localhost
$data = array('symptoms' => 'fever, cough'); // Example data to send

// Setting cURL options
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_POST, 1); // POST method
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Sending data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request and capture the response
$response = curl_exec($ch);

// Check for errors
if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

// Close cURL resource
curl_close($ch);

// Output the response from the Python API
echo $response;
?>
