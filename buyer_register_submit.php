<?php
require_once __DIR__ . '/config.php';
$conn = db_mysqli();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buyer_name = $_POST['buyer_name'];
    $buyer_user_id = $_POST['buyer_user_id'];
    $buyer_email = $_POST['buyer_email'];
    $buyer_password = password_hash($_POST['buyer_password'], PASSWORD_DEFAULT); // store a hash, never the plain password
    // Use prepared statements
    $stmt = $conn->prepare("INSERT INTO buyer_data (buyer_name, buyer_user_id, buyer_email, buyer_password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $buyer_name, $buyer_user_id, $buyer_email, $buyer_password);
    $result = $stmt->get_result();
    try {
    if ($stmt->execute()) {
        echo "Buyer Data Inserted successfully!\n";
        echo "<script> window.location.href = 'loginb.php'</script>";
    }  
    else if($result!=1) {
        echo "<script>alert('Please check the provided details. Registration or login failed.');</script>";
        echo "<script>window.location.href = 'buyer_registration_form.php';</script>";
        exit(); 
    }
    else {
        throw new Exception($stmt->error);
    }
}
catch(Exception $e) {
    echo "<script>alert('Please check the provided details. Registration or login failed.');</script>";
        echo "<script>window.location.href = 'buyer_registration_form.php';</script>";
        exit(); 
}

    $stmt->close();
    $result = $conn->query("SELECT * FROM buyer_data");

}

$conn->close();
?>
