<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include('db_connection.php');

$user_id = $_SESSION['user_id'];

// Fetch symptoms from the database for the logged-in user
$query = "SELECT * FROM symptoms WHERE user_id='$user_id' ORDER BY symptom_id DESC";
$result = mysqli_query($conn, $query);
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
    

    <div class="container">
        <div class="card">
            <h2>Symptoms History</h2> </div> 
            

            <div class="history">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    $sr_no = 1; // Starting Sr.No
                    echo "<div class='symptom-table'>";
                    echo "<table>";
                    echo "<thead><tr><th>Sr. No</th><th>Symptoms</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $sr_no++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['symptom_text']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo "<p class='no-history'>No symptoms history available.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <footer>
        <p class="footer-text">&copy; 2025 PharmaAI. All rights reserved.</p>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
