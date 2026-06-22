<?php
function getDBConnection() {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=u07;charset=utf8mb4", "u07", "YOUR_PASSWORD_HERE");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        error_log("DB Connection Failed: " . $e->getMessage());
        return null;
    }
}
?>
