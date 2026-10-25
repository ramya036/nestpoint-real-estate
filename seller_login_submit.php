<?php
session_start();
$conn = new mysqli("localhost", "nguttula1", "nguttula1", "nguttula1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seller_user_id = $_POST['seller_user_id_login'];
    $seller_password = $_POST['seller_password_login'];
    
    // Prepared statement prevents SQL injection; password_verify checks the stored hash.
    $stmt = $conn->prepare("SELECT seller_password FROM seller_data WHERE seller_user_id = ?");
    $stmt->bind_param("s", $seller_user_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $count = ($row && password_verify($seller_password, $row['seller_password'])) ? 1 : 0;
      
    if($count == 1){  
        $_SESSION['user_type'] = 'seller';
        header("Location: seller_dashboard.php");
    }  
    else{  
        echo "<script>
        alert('Invalid credentials');
        window.location.href = 'logins.php?error=invalid';
    </script>";
    exit();  
    } 

}

$conn->close();
?>