<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_24725301", "24725301", "24725301");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$pid = $_GET['userId'];
$sql = 'SELECT * FROM comments WHERE userId LIKE :userId';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':userId', "%" . $pid . '%');
$stmt->execute();
while ($row = $stmt->fetch()) {
    $sql2 = 'SELECT userName FROM users WHERE userid LIKE :userid';
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindValue(':userid', '%' . $row['userid'] . '%');
    $stmt2->execute();
    $row2 = $stmt2->fetch();

    echo "<tr>";
    if (isset($_SESSION['admin']) && $_SESSION['admin']) {
        echo "<td class='commentText' onclick='editComment(this, " . $row['commentId'] . ")'>" . $row['commentText'] . "</td>";
    } else {
        echo "<td>" . $row['commentText'] . "</td>";
    }
    echo "<td>" . $row['timestamp'] . "</td>";
    echo "<td><a href='../pages/product.php?pid=" . $row['pid'] . "'>" . $row['pid'] . "</a></td>";

    if (isset($_SESSION['admin']) && $_SESSION['admin']) {
        echo "<td class='delete'><a href='../php/delete_comment.php?id=" . $row['commentId'] . "' style='text-decoration: none'>
            <button class='btn btn-outline-danger my-2 my-sm-0 d-flex align-items-center justify-content-center' style='padding: 6px'>
            <span class='material-symbols-outlined'>delete</span></button></a></td>";
    }
    echo "</tr>";
}
?>

<script>
    function editComment(element, commentid) {
    var newText = prompt("Enter new comment text:", element.innerText.trim());
    if (newText !== null) {
        $.ajax({
            url: '../php/update_comment.php',
            method: 'POST',
            data: { commentId: commentid, newText: newText },
            success: function(response) {
                if (response === 'success') {
                    element.innerText = newText;
                    alert('Comment $commentId updated!');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('An error occurred while updating the comment.' + error);
            }
        });
    }
}

</script>
