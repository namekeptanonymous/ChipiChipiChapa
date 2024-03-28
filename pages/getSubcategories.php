<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    if (isset($_POST['categoryId'])) {
        $categoryId = $_POST['categoryId'];
        
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=db_24725301", "24725301", "24725301");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $stmt = $pdo->prepare("
                SELECT s.subCategoryId, c.name AS subcategory_name
                FROM subcategories s
                INNER JOIN categories c ON s.subCategoryId = c.id
                WHERE s.categoryId = :categoryId
            ");
            $stmt->bindValue(':categoryId', $categoryId);
            $stmt->execute();
        
            $options = '<option value="">Select Subcategory</option>';
            while ($row = $stmt->fetch()) {
                $options .= '<option value="' . $row['subCategoryId'] . '">' . $row['subcategory_name'] . '</option>';
            }
        
            echo $options;
        } catch (PDOException $e) {
            echo '<option value="">Error loading subcategories</option>';
        }
    } else {
        echo '<option value="">Select a Category First</option>';
    }
} else {
    echo '<option value="">Invalid Request</option>';
}
?>