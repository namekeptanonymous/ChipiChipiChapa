<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=bestbuy", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=chipichipichapa", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

# Get all comments
$pid = $_GET['pid'];
$sql = 'SELECT * FROM comments WHERE pid LIKE :pid';
$stmt = $conn->prepare($sql);
$stmt->bindValue(':pid', "%" . $pid . '%');
$stmt->execute();
while ($row = $stmt->fetch()) {
    $sql2 = 'SELECT userName FROM users WHERE userid LIKE :userid';
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bindValue(':userid', '%' . $row['userid'] . '%');
    $stmt2->execute();
    $row2 = $stmt2->fetch();
    echo "<tr>";
    if (isset($_SESSION['admin']) && $_SESSION['admin']) {
        echo "<td><a href='../pages/manage_users.php?userId=" . $row['userid'] . "'</a>" . $row2['userName'] . "</td> ";
        echo "<td class='commentText' onclick='editComment(this, " . $row['commentId'] . ")'> " . $row['commentText'] . "</td> ";
    } else {
        echo "<td>". $row2['userName'] . "</td> ";
        echo "<td> " . $row['commentText'] . "</td> ";
    }
    echo "<td> " . $row['timestamp'] . "</td> ";
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
