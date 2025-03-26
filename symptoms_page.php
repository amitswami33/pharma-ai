<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include('db_connection.php');

// Default values for disease and medicines
$disease = '';
$medicines = [];

// Get user ID
$user_id = $_SESSION['user_id'];

// Fetch symptoms from the database for the logged-in user if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $symptoms = $_POST['symptoms']; // Get symptoms from form input

    $data = json_encode(["symptoms" => explode(',', $symptoms)]); // Convert symptoms to JSON format

    $url = "http://127.0.0.1:5000/predict"; // Change this to your deployed API URL

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response && !$error) {
        $result = json_decode($response, true);
        
        // Extract disease and medicines from the response
        $disease = $result['predicted_disease'] ?? 'No disease predicted';
        $medicines = $result['recommended_medicines'] ?? [];
    } else {
        $disease = 'Error connecting to the API. Please try again.';
        $medicines = [];
    }

    // Save the entered symptoms to the database
    $query = "INSERT INTO symptoms (user_id, symptom_text) VALUES ('$user_id', '$symptoms')";
    mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container-box {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">PharmaAI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="symptoms_page.php">Submit Symptoms</a></li>
                    <li class="nav-item"><a class="nav-link" href="symptom_history.php">History</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
            " . $_SESSION['message'] . "
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
    unset($_SESSION['message']); // Clear message after displaying
}
?>

    <div class="container">
        <div>
            <h2>Enter Symptoms for AI-based Prescription</h2>
            
        </div>

        <!-- Form to enter symptoms -->
        <div class="form-container">
            <form method="POST" action="symptoms_page.php">
                <label for="symptoms">Enter Symptoms (comma-separated):</label>
                <input type="text" id="symptoms" name="symptoms" required placeholder="e.g., fever, cough, headache" value="<?php echo isset($symptoms) ? htmlspecialchars($symptoms) : ''; ?>">
                <button type="submit">Get Prescription</button>
            </form>
        </div>

        <!-- Display Prescription -->
        <?php if ($disease): ?>
            <div class="result-card">
                <h4>Recommended Medicines:</h4>
                <?php if (!empty($medicines)): ?>
                    <ul class="medicines">
                        <?php foreach ($medicines as $medicine): ?>
                            <li><?php echo htmlspecialchars($medicine); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No medicines recommended.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <footer>
        <p class="footer-text">&copy; 2025 PharmaAI. All rights reserved.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
