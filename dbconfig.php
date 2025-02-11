<?php
// Database connection function
function getDBConnection() {
    try {
        // Replace with your database credentials
        $host = 'localhost';
        $dbname = 'udb';  // Your database name
        $username = 'uer';   // Your database username
        $password = 'sa1';       // Your database password

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        return null;
    }
}
?>