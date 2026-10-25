<?php
session_start();
$conn = new mysqli("localhost", "nguttula1", "nguttula1", "nguttula1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buyer_user_id = $_POST['buyer_user_id_login'];
    $buyer_password = $_POST['buyer_password_login'];
    
    // Prepared statement prevents SQL injection; password_verify checks the stored hash.
    $stmt = $conn->prepare("SELECT buyer_password FROM buyer_data WHERE buyer_user_id = ?");
    $stmt->bind_param("s", $buyer_user_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $count = ($row && password_verify($buyer_password, $row['buyer_password'])) ? 1 : 0;
      
    if($count == 1){  
        $_SESSION['user_type'] = 'buyer';
        header("location: buyer_dashboard.php");
    }  
    else{  
        echo "<script>
        alert('Invalid credentials');
        window.location.href = 'loginb.php?error=invalid';
    </script>";
    exit(); 
    } 

}

$conn->close();
?>
