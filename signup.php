<?php
session_start();
 $msg = "";

// Database connection
 $conn = new mysqli("localhost", "root", "", "auhhc_project_system", 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Registration
if (isset($_POST['register'])) {

    // mysqli_real_escape_string() is a security function that escapes
    //  special characters in a string for use in an SQL statement, helping prevent SQL injection attacks
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    // All new users are registered as students by default
    $role = "student"; 

//     password_hash() function to create a secure hash of the password
// PASSWORD_DEFAULT ensures the strongest available hashing algorithm is used
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
// Email Duplicate Check
    $check = $conn->query("SELECT email FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $msg = "<div class='alert error'>Email already registered!</div>";
    } else {
//         If the email doesn't exist, it creates a new SQL INSERT statement
// Adds the user's information to the users table in the database
        $sql = "INSERT INTO users (fullname, email, password, role) VALUES ('$name', '$email', '$pass', '$role')";
        if ($conn->query($sql)) {
            $msg = "<div class='alert success'>Registration Successful! Please login.</div>";
            // Redirect to login after successful registration
            echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 2000);</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AUHHC Project System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #004a99;
            --secondary: #003366;
            --accent: #0066cc;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg: #f8fafc;
            --text: #1e293b;
            --card-bg: #ffffff;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 0 15px;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid var(--success);
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            border-left: 4px solid var(--danger);
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
            width: 100%;
        }

        .card-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 74, 153, 0.1);
        }

        /* Info Box */
        .info-box {
            background: #e0f2fe;
            border-left: 4px solid var(--primary);
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            color: #0c4a6e;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            width: 100%;
            justify-content: center;
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            width: 100%;
            justify-content: center;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        /* Footer */
        .footer {
            background: var(--secondary);
            color: white;
            padding: 1.5rem 0;
            text-align: center;
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .card {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div>
            <a href="index.php" class="logo">
                <img src="./loogo.jpg"
                     alt="Ambo University Logo"
                     style="
                        width: 65px;
                        height: 65px;
                        border-radius: 100%;
                        object-fit: contain;
                     ">

                <span>AUHHC Project Cord</span>
            </a>

            <?= $msg ?>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-user-plus"></i> Create Account
                    </h2>
                </div>
                <form method="POST">
                    <!-- <div class="info-box">
                        <i class="fas fa-info-circle"></i> All new accounts are created as students. 
                        System administrators will assign supervisor or other roles as needed.
                    </div> -->
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create a strong password" required minlength="6">
                    </div>
                    <button type="submit" name="register" class="btn btn-primary">
                        <i class="fas fa-user-check"></i> Create Account
                    </button>
                </form>
                <p style="text-align: center; margin-top: 1rem; color: #64748b;">
                    Already have an account? <a href="login.php" style="color: var(--primary);">Login here</a>
                </p>
                <p style="text-align: center; margin-top: 0.5rem;">
                    <a href="index.php" style="color: var(--primary); text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <div style="font-size: 1.2rem; font-weight: bold; margin-bottom: 0.5rem;">
                <i class="fas fa-graduation-cap"></i> AUHHC Project System
            </div>
            <p>Department of Information Technology</p>
            <div class="footer-links">
                <a href="index.php">Home</a> |
                <a href="index.php?view=about">About</a> 
                <a href="#" onclick="alert('Contact page would open here')">Contact</a> |
                <a href="#" onclick="alert('Privacy policy would open here')">Privacy Policy</a>
            </div>
            <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                &copy; <?= date('Y') ?> AUHHC University. All rights reserved.
            </p>
        </div>
    </footer>
</body>

</html>