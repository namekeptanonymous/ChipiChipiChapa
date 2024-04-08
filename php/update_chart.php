<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_24725301", "24725301", "24725301");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $pid = isset($_GET['pid']) ? $_GET['pid'] : null;
    $months = isset($_GET['months']) ? $_GET['months'] : 1; // Default to 1 month if not provided
    if ($pid !== null) {
        try {
            $priceHistorySql = 'SELECT date, price FROM PriceHistory WHERE product_id = :pid ORDER BY date DESC LIMIT :limit';
            $priceHistoryStmt = $pdo->prepare($priceHistorySql);
            $priceHistoryStmt->bindValue(':pid', $pid);
            $priceHistoryStmt->bindValue(':limit', $months*30, PDO::PARAM_INT); // Explicitly specify data type
            $priceHistoryStmt->execute();

            $dates = [];
            $prices = [];

            while ($row = $priceHistoryStmt->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = $row['date'];
                $prices[] = $row['price'];
            }
            $responseData = [
                'dates' => $dates,
                'prices' => $prices
            ];
            header('Content-Type: application/json');
            echo json_encode($responseData);
        } catch (PDOException $e) {
            echo 'Error fetching new data: ' . $e->getMessage();
        }
    } else {
        echo 'Product ID is required.';
    }
} else {
    echo 'Invalid request method.';
}
?>
