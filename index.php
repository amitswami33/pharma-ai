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
        </div>
    </nav>
    
    <div class="container">
        <div class="container-box">
            <h3 class="text-center">Welcome to PharmaAI</h3>
            <p class="text-center">An intelligent medicine prescription system.</p>
        </div>
    </div>
    <div class="container-box">
    <h3 class="text-center">Login or Create Account</h3>
    <a href="login.php" class="btn btn-primary w-100">Login</a>
    <p></p>
    <a href="register.php" class="btn btn-primary w-100">Create Account</a>
</div>

<footer>
        <p class="footer-text">&copy; 2025 PharmaAI. All rights reserved.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>