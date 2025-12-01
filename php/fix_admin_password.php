<?php
/**
 * Quick Fix: Reset Admin Password
 * 
 * This script will update the admin password to 'admin123' in your database
 * Run this file once, then try logging in again
 */

require_once __DIR__ . '/dbconnect.php';

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Fix Admin Password</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css'>
</head>
<body class='bg-light py-5'>
<div class='container'>
    <div class='row justify-content-center'>
        <div class='col-md-6'>
            <div class='card shadow'>
                <div class='card-header bg-primary text-white'>
                    <h4 class='mb-0'><i class='bi bi-key-fill me-2'></i>Fix Admin Password</h4>
                </div>
                <div class='card-body'>";

try {
    // Generate a fresh password hash for 'admin123'
    $new_password = 'admin123';
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Check if admin user exists
    $check = $bdd->prepare("SELECT user_id, username, role FROM users WHERE username = 'admin'");
    $check->execute();
    $admin = $check->fetch();
    
    if ($admin) {
        echo "<div class='alert alert-info'>";
        echo "<strong><i class='bi bi-info-circle me-2'></i>Admin user found:</strong><br>";
        echo "User ID: {$admin['user_id']}<br>";
        echo "Username: {$admin['username']}<br>";
        echo "Role: {$admin['role']}";
        echo "</div>";
        
        // Update the password
        $update = $bdd->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
        $update->execute([$password_hash]);
        
        echo "<div class='alert alert-success'>";
        echo "<h5><i class='bi bi-check-circle-fill me-2'></i>Password Updated Successfully!</h5>";
        echo "<p class='mb-0'>The admin password has been reset to: <strong>admin123</strong></p>";
        echo "</div>";
        
        // Verify the update worked
        $verify = $bdd->prepare("SELECT password FROM users WHERE username = 'admin'");
        $verify->execute();
        $updated_user = $verify->fetch();
        
        if (password_verify('admin123', $updated_user['password'])) {
            echo "<div class='alert alert-success'>";
            echo "<i class='bi bi-check-circle-fill me-2'></i>Password verification successful! You can now login.";
            echo "</div>";
        } else {
            echo "<div class='alert alert-danger'>";
            echo "<i class='bi bi-exclamation-triangle me-2'></i>Warning: Password verification failed. Try running this script again.";
            echo "</div>";
        }
        
        echo "<div class='card bg-light mt-3'>";
        echo "<div class='card-body'>";
        echo "<h6>Login Credentials:</h6>";
        echo "<ul class='mb-0'>";
        echo "<li><strong>Username:</strong> admin</li>";
        echo "<li><strong>Password:</strong> admin123</li>";
        echo "</ul>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='mt-3 text-center'>";
        echo "<a href='login.php' class='btn btn-primary'><i class='bi bi-box-arrow-in-right me-2'></i>Go to Login</a>";
        echo "</div>";
        
    } else {
        // Admin user doesn't exist - create it
        echo "<div class='alert alert-warning'>";
        echo "<i class='bi bi-exclamation-triangle me-2'></i>Admin user not found. Creating new admin user...";
        echo "</div>";
        
        $insert = $bdd->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $insert->execute(['admin', 'admin@evenements.com', $password_hash, 'admin']);
        
        echo "<div class='alert alert-success'>";
        echo "<h5><i class='bi bi-check-circle-fill me-2'></i>Admin User Created!</h5>";
        echo "<p class='mb-0'>A new admin user has been created with password: <strong>admin123</strong></p>";
        echo "</div>";
        
        echo "<div class='card bg-light mt-3'>";
        echo "<div class='card-body'>";
        echo "<h6>Login Credentials:</h6>";
        echo "<ul class='mb-0'>";
        echo "<li><strong>Username:</strong> admin</li>";
        echo "<li><strong>Password:</strong> admin123</li>";
        echo "<li><strong>Email:</strong> admin@evenements.com</li>";
        echo "</ul>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='mt-3 text-center'>";
        echo "<a href='login.php' class='btn btn-primary'><i class='bi bi-box-arrow-in-right me-2'></i>Go to Login</a>";
        echo "</div>";
    }
    
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>";
    echo "<strong><i class='bi bi-exclamation-triangle me-2'></i>Database Error:</strong><br>";
    echo htmlspecialchars($e->getMessage());
    echo "</div>";
    
    echo "<div class='alert alert-info'>";
    echo "<strong>Possible solutions:</strong>";
    echo "<ul>";
    echo "<li>Make sure MySQL server is running</li>";
    echo "<li>Check database credentials in php/dbconnect.php</li>";
    echo "<li>Run setup_database.php to initialize the database</li>";
    echo "</ul>";
    echo "</div>";
}

echo "        </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>";
?>
