<?php
/**
 * Password Hash Generator and Tester
 * Use this to generate correct password hashes and test authentication
 */

// Generate new hash for admin123
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Password Hash Generator</h2>";
echo "<p><strong>Password:</strong> $password</p>";
echo "<p><strong>New Hash:</strong></p>";
echo "<pre>$hash</pre>";

echo "<hr>";

// Test the hash from the database
$database_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "<h2>Hash Verification Test</h2>";
echo "<p><strong>Testing password:</strong> $password</p>";
echo "<p><strong>Against database hash:</strong></p>";
echo "<pre>$database_hash</pre>";

if (password_verify($password, $database_hash)) {
    echo "<p style='color: green; font-weight: bold;'>✓ Password verification SUCCESSFUL</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>✗ Password verification FAILED</p>";
}

echo "<hr>";

// Check what's actually in the database
require_once __DIR__ . '/dbconnect.php';

echo "<h2>Database Check</h2>";

try {
    $query = $bdd->prepare("SELECT user_id, username, email, role, password FROM users WHERE username = 'admin'");
    $query->execute();
    $user = $query->fetch();
    
    if ($user) {
        echo "<p style='color: green;'>✓ Admin user found in database</p>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        echo "<tr><td>User ID</td><td>{$user['user_id']}</td></tr>";
        echo "<tr><td>Username</td><td>{$user['username']}</td></tr>";
        echo "<tr><td>Email</td><td>{$user['email']}</td></tr>";
        echo "<tr><td>Role</td><td>{$user['role']}</td></tr>";
        echo "<tr><td>Password Hash</td><td><pre>" . htmlspecialchars($user['password']) . "</pre></td></tr>";
        echo "</table>";
        
        echo "<h3>Login Test</h3>";
        $test_password = 'admin123';
        if (password_verify($test_password, $user['password'])) {
            echo "<p style='color: green; font-weight: bold; font-size: 18px;'>✓ Login with 'admin123' would SUCCEED</p>";
        } else {
            echo "<p style='color: red; font-weight: bold; font-size: 18px;'>✗ Login with 'admin123' would FAIL</p>";
            echo "<p>The password hash in the database doesn't match 'admin123'.</p>";
            echo "<p><strong>Solution:</strong> Run this UPDATE query:</p>";
            echo "<pre style='background: #f5f5f5; padding: 10px;'>";
            echo "UPDATE users SET password = '" . password_hash('admin123', PASSWORD_DEFAULT) . "' WHERE username = 'admin';";
            echo "</pre>";
        }
    } else {
        echo "<p style='color: red;'>✗ Admin user NOT found in database</p>";
        echo "<p><strong>Solution:</strong> Run the setup_database.php script or insert the admin user manually.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<hr>
<h2>Quick Fix</h2>
<p>If the password doesn't work, you can update it by running this SQL query in phpMyAdmin:</p>
<pre style='background: #f5f5f5; padding: 10px;'>
UPDATE users SET password = '<?= password_hash('admin123', PASSWORD_DEFAULT) ?>' WHERE username = 'admin';
</pre>

<p><a href="login.php">Go to Login Page</a></p>
