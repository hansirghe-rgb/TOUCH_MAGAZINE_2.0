<?php
require 'config/database.php';
try {
    $hash = password_hash('TouchAdmin2026!', PASSWORD_DEFAULT);
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->fetch()) {
        // Update existing password
        $update = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE username = 'admin'");
        $update->execute([$hash]);
        echo '<h3>Password securely reset!</h3>';
    } else {
        // Insert new admin user
        $insert = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES ('admin', ?)");
        $insert->execute([$hash]);
        echo '<h3>Admin account successfully created!</h3>';
    }
    
    echo '<p><strong>Username:</strong> admin</p>';
    echo '<p><strong>Password:</strong> TouchAdmin2026!</p>';
    echo '<p><a href="admin/login.php" style="color: blue; text-decoration: underline;">Click here to return to the Login Screen &rarr;</a></p>';
    
} catch (Exception $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>
