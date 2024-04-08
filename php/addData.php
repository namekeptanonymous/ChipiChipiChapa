<?php
session_start();

if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: ../index.php");
    exit();
}

$servername = "localhost";
$username = "24725301";
$password = "24725301";
$database = "db_24725301";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $productId = $_POST['product_id'];
    $numEntries = $_POST['num_entries'];

    $stmtRecentPrice = $conn->prepare("SELECT price, date FROM PriceHistory WHERE product_id = :productId ORDER BY date DESC LIMIT 1");
    $stmtRecentPrice->bindParam(':productId', $productId);
    $stmtRecentPrice->execute();
    $row = $stmtRecentPrice->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $recentPrice = mt_rand(1000, 10000) / 100;
        $date = date('Y-m-d');
    } else {
        $recentPrice = $row['price'];
        $date = date('Y-m-d', strtotime($row['date'] . ' + 1 day'));
    }

    $stmt = $conn->prepare("INSERT INTO PriceHistory (product_id, price, date) VALUES (:productId, :price, :date)");

    for ($i = 0; $i < $numEntries; $i++) {
        $price = $recentPrice * (1 + (mt_rand(-10, 10) / 100)); 
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        $date = date('Y-m-d', strtotime($date . ' + 1 day')); 
    }

    echo "<script>alert('Price data successfully added for Product ID: $productId'); window.location.href = '../pages/inputData.php';</script>";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
