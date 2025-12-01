<?php
/**
 * Database Setup Script
 * 
 * This script will:
 * 1. Create the database if it doesn't exist
 * 2. Create all necessary tables
 * 3. Add default admin user
 * 4. Optionally add sample data
 * 
 * WARNING: Running this script will delete all existing data!
 * 
 * Usage: Run this file directly in your browser
 * Example: http://localhost/EventRegistration%20PHTML/php/setup_database.php
 */

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gestion_evenements');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - Event Registration System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .setup-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .log-entry {
            padding: 0.5rem;
            border-left: 3px solid #198754;
            background: #f8f9fa;
            margin-bottom: 0.5rem;
            font-family: monospace;
        }
        .log-entry.error {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
        .log-entry.warning {
            border-left-color: #ffc107;
            background: #fff3cd;
        }
    </style>
</head>
<body>
    <div class="container setup-container">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center py-4">
                <i class="bi bi-database-gear display-3"></i>
                <h2 class="mt-3 mb-0">Database Setup</h2>
                <p class="mb-0">Event Registration System</p>
            </div>
            <div class="card-body p-4">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $includeSampleData = isset($_POST['sample_data']) && $_POST['sample_data'] === 'yes';
                    
                    echo '<div class="log-container">';
                    echo '<h5 class="mb-3"><i class="bi bi-terminal me-2"></i>Setup Log</h5>';
                    
                    try {
                        // Connect without database first
                        echo '<div class="log-entry">Connecting to MySQL server...</div>';
                        $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USER, DB_PASS);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        echo '<div class="log-entry">✓ Connected to MySQL server</div>';
                        
                        // Drop and create database
                        echo '<div class="log-entry warning">Dropping existing database (if exists)...</div>';
                        $pdo->exec("DROP DATABASE IF EXISTS " . DB_NAME);
                        echo '<div class="log-entry">Creating database: ' . DB_NAME . '</div>';
                        $pdo->exec("CREATE DATABASE " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                        $pdo->exec("USE " . DB_NAME);
                        echo '<div class="log-entry">✓ Database created successfully</div>';
                        
                        // Create users table
                        echo '<div class="log-entry">Creating table: users</div>';
                        $pdo->exec("
                            CREATE TABLE users (
                                user_id INT AUTO_INCREMENT PRIMARY KEY,
                                username VARCHAR(50) NOT NULL UNIQUE,
                                email VARCHAR(100) NOT NULL UNIQUE,
                                password VARCHAR(255) NOT NULL,
                                role ENUM('admin', 'client') NOT NULL DEFAULT 'client',
                                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                INDEX idx_username (username),
                                INDEX idx_email (email),
                                INDEX idx_role (role)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                        ");
                        echo '<div class="log-entry">✓ Table users created</div>';
                        
                        // Create events table
                        echo '<div class="log-entry">Creating table: events</div>';
                        $pdo->exec("
                            CREATE TABLE events (
                                event_id INT AUTO_INCREMENT PRIMARY KEY,
                                title VARCHAR(200) NOT NULL,
                                description TEXT,
                                event_date DATE NOT NULL,
                                lieu VARCHAR(200) NOT NULL,
                                prix DECIMAL(10, 2) DEFAULT 0.00,
                                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                INDEX idx_event_date (event_date),
                                INDEX idx_title (title)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                        ");
                        echo '<div class="log-entry">✓ Table events created</div>';
                        
                        // Create participants table
                        echo '<div class="log-entry">Creating table: participants</div>';
                        $pdo->exec("
                            CREATE TABLE participants (
                                participant_id INT AUTO_INCREMENT PRIMARY KEY,
                                name VARCHAR(100) NOT NULL,
                                email VARCHAR(100) NOT NULL UNIQUE,
                                phone VARCHAR(20),
                                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                INDEX idx_email (email),
                                INDEX idx_name (name)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                        ");
                        echo '<div class="log-entry">✓ Table participants created</div>';
                        
                        // Create inscriptions table
                        echo '<div class="log-entry">Creating table: inscriptions</div>';
                        $pdo->exec("
                            CREATE TABLE inscriptions (
                                inscription_id INT AUTO_INCREMENT PRIMARY KEY,
                                participant_id INT NOT NULL,
                                event_id INT NOT NULL,
                                registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                FOREIGN KEY (participant_id) REFERENCES participants(participant_id) ON DELETE CASCADE,
                                FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
                                UNIQUE KEY unique_inscription (participant_id, event_id),
                                INDEX idx_participant (participant_id),
                                INDEX idx_event (event_id)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                        ");
                        echo '<div class="log-entry">✓ Table inscriptions created</div>';
                        
                        // Insert admin user
                        echo '<div class="log-entry">Creating default admin user...</div>';
                        $stmt = $pdo->prepare("
                            INSERT INTO users (username, email, password, role) 
                            VALUES (?, ?, ?, ?)
                        ");
                        $stmt->execute([
                            'admin',
                            'admin@evenements.com',
                            password_hash('admin123', PASSWORD_DEFAULT),
                            'admin'
                        ]);
                        echo '<div class="log-entry">✓ Admin user created (username: admin, password: admin123)</div>';
                        
                        // Insert sample client user
                        echo '<div class="log-entry">Creating default client user...</div>';
                        $stmt->execute([
                            'client',
                            'client@evenements.com',
                            password_hash('client123', PASSWORD_DEFAULT),
                            'client'
                        ]);
                        echo '<div class="log-entry">✓ Client user created (username: client, password: client123)</div>';
                        
                        // Insert sample data if requested
                        if ($includeSampleData) {
                            echo '<div class="log-entry">Inserting sample events...</div>';
                            $pdo->exec("
                                INSERT INTO events (title, description, event_date, lieu, prix) VALUES
                                ('Conférence Tech 2025', 'Une conférence sur les dernières technologies et innovations dans le domaine du numérique.', '2025-03-15', 'Centre de Congrès Paris', 49.99),
                                ('Atelier Développement Web', 'Apprenez à créer des applications web modernes avec les frameworks les plus populaires.', '2025-04-20', 'Campus Universitaire Lyon', 29.99),
                                ('Séminaire Marketing Digital', 'Stratégies et techniques pour réussir votre marketing en ligne.', '2025-05-10', 'Hôtel Marriott Marseille', 79.99),
                                ('Festival de Musique', 'Trois jours de concerts avec des artistes internationaux.', '2025-06-15', 'Parc des Expositions Bordeaux', 120.00),
                                ('Formation SQL Avancé', 'Maîtrisez les requêtes complexes et l\\'optimisation de bases de données.', '2025-07-05', 'Centre de Formation Toulouse', 199.99)
                            ");
                            echo '<div class="log-entry">✓ Sample events inserted</div>';
                            
                            echo '<div class="log-entry">Inserting sample participants...</div>';
                            $pdo->exec("
                                INSERT INTO participants (name, email, phone) VALUES
                                ('Jean Dupont', 'jean.dupont@email.com', '0612345678'),
                                ('Marie Martin', 'marie.martin@email.com', '0623456789'),
                                ('Pierre Durand', 'pierre.durand@email.com', '0634567890'),
                                ('Sophie Bernard', 'sophie.bernard@email.com', '0645678901'),
                                ('Luc Petit', 'luc.petit@email.com', '0656789012')
                            ");
                            echo '<div class="log-entry">✓ Sample participants inserted</div>';
                            
                            echo '<div class="log-entry">Inserting sample inscriptions...</div>';
                            $pdo->exec("
                                INSERT INTO inscriptions (participant_id, event_id) VALUES
                                (1, 1), (1, 2), (2, 1), (2, 3), (3, 4),
                                (4, 2), (4, 5), (5, 1), (5, 3)
                            ");
                            echo '<div class="log-entry">✓ Sample inscriptions inserted</div>';
                        }
                        
                        echo '<div class="log-entry" style="border-left-color: #0d6efd; background: #cfe2ff; font-weight: bold; margin-top: 1rem;">
                            <i class="bi bi-check-circle-fill me-2"></i>Database setup completed successfully!
                        </div>';
                        
                        echo '</div>';
                        
                        echo '
                        <div class="alert alert-success mt-4">
                            <h5 class="alert-heading"><i class="bi bi-check-circle me-2"></i>Setup Complete!</h5>
                            <hr>
                            <p class="mb-2"><strong>Database:</strong> ' . DB_NAME . '</p>
                            <p class="mb-2"><strong>Admin Login:</strong></p>
                            <ul>
                                <li>Username: <code>admin</code></li>
                                <li>Password: <code>admin123</code></li>
                            </ul>
                            <p class="mb-2"><strong>Client Login:</strong></p>
                            <ul>
                                <li>Username: <code>client</code></li>
                                <li>Password: <code>client123</code></li>
                            </ul>
                            <hr>
                            <a href="login.php" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Go to Login
                            </a>
                            <a href="index.php" class="btn btn-outline-primary">
                                <i class="bi bi-house me-2"></i>Go to Homepage
                            </a>
                        </div>
                        ';
                        
                    } catch (PDOException $e) {
                        echo '<div class="log-entry error">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                        echo '</div>';
                        echo '
                        <div class="alert alert-danger mt-4">
                            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Setup Failed!</h5>
                            <p>Please check the error log above and ensure:</p>
                            <ul>
                                <li>MySQL server is running</li>
                                <li>Database credentials in dbconnect.php are correct</li>
                                <li>The MySQL user has permission to create databases</li>
                            </ul>
                        </div>
                        ';
                    }
                } else {
                    // Show setup form
                    ?>
                    <div class="alert alert-warning">
                        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Warning!</h5>
                        <p class="mb-0">This script will <strong>delete all existing data</strong> in the database and recreate all tables.</p>
                    </div>
                    
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-info-circle me-2"></i>Database Information</h5>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td width="150"><strong>Host:</strong></td>
                                    <td><?= DB_HOST ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Database:</strong></td>
                                    <td><?= DB_NAME ?></td>
                                </tr>
                                <tr>
                                    <td><strong>User:</strong></td>
                                    <td><?= DB_USER ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <form method="POST" onsubmit="return confirm('Are you sure you want to reset the database? This will delete all existing data!');">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="sample_data" name="sample_data" value="yes" checked>
                            <label class="form-check-label" for="sample_data">
                                <strong>Include sample data</strong> (recommended for testing)
                                <br>
                                <small class="text-muted">This will add sample events, participants, and inscriptions to the database.</small>
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-arrow-clockwise me-2"></i>Initialize Database
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                    <?php
                }
                ?>
            </div>
            <div class="card-footer text-center text-muted">
                <small>Event Registration System v1.0</small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
