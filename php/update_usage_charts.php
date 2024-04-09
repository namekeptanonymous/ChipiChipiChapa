<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_24725301", "24725301", "24725301");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

try {
    $visitsSql = 'SELECT DATE(timestamp) as date, COUNT(*) as visits FROM ' . $_GET['table'] . ' GROUP BY DATE(timestamp) ORDER BY date DESC';
    $visitsStmt = $pdo->prepare($visitsSql);
    $visitsStmt->execute();

    $dates = [];
    $visits = [];

    while ($row = $visitsStmt->fetch(PDO::FETCH_ASSOC)) {
        $dates[] = $row['date'];
        $visits[] = $row['visits'];
    }
    $responseData = [
        'dates' => $dates,
        'visits' => $visits
    ];
    header('Content-Type: application/json');
    echo json_encode($responseData);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    exit;
}
?>
