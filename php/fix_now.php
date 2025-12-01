<?php
/**
 * One-Click Admin Password Fix
 * This will immediately update the admin password to 'admin123'
 */

require_once __DIR__ . '/dbconnect.php';

// Generate fresh hash
$password_hash = password_hash('admin123', PASSWORD_DEFAULT);

try {
    // Update admin password
    $stmt = $bdd->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $result = $stmt->execute([$password_hash]);
    
    if ($result) {
        // Verify it worked
        $verify = $bdd->prepare("SELECT password FROM users WHERE username = 'admin'");
        $verify->execute();
        $user = $verify->fetch();
        
        if (password_verify('admin123', $user['password'])) {
            echo "<!DOCTYPE html>
<html>
<head>
    <title>Password Fixed</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='bg-light d-flex align-items-center' style='min-height: 100vh;'>
    <div class='container'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card shadow-lg'>
                    <div class='card-body text-center p-5'>
                        <div class='mb-4'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='64' height='64' fill='#198754' class='bi bi-check-circle-fill' viewBox='0 0 16 16'>
                                <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>
                            </svg>
                        </div>
                        <h2 class='text-success mb-3'>Password Fixed!</h2>
                        <p class='lead'>Admin password has been successfully updated.</p>
                        <div class='alert alert-success mt-4 text-start'>
                            <strong>Login Credentials:</strong><br>
                            Username: <code>admin</code><br>
                            Password: <code>admin123</code>
                        </div>
                        <a href='login.php' class='btn btn-primary btn-lg mt-3'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-box-arrow-in-right me-2' viewBox='0 0 16 16'>
                                <path fill-rule='evenodd' d='M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z'/>
                                <path fill-rule='evenodd' d='M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z'/>
                            </svg>
                            Go to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
            exit;
        }
    }
    
    throw new Exception("Password update failed verification");
    
} catch (Exception $e) {
    echo "<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='bg-light d-flex align-items-center' style='min-height: 100vh;'>
    <div class='container'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card shadow-lg border-danger'>
                    <div class='card-body text-center p-5'>
                        <div class='mb-4'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='64' height='64' fill='#dc3545' class='bi bi-x-circle-fill' viewBox='0 0 16 16'>
                                <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z'/>
                            </svg>
                        </div>
                        <h2 class='text-danger mb-3'>Update Failed</h2>
                        <div class='alert alert-danger text-start'>
                            " . htmlspecialchars($e->getMessage()) . "
                        </div>
                        <a href='test_password.php' class='btn btn-outline-primary'>Back to Test</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
}
?>
