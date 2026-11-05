<?php
require_once __DIR__ . '/config.php';
$conn = db_mysqli();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = $_POST['admin_name'];
    $admin_user_id = $_POST['admin_user_id'];
    $admin_email = $_POST['admin_email'];
    $admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT); // Secure password hashing

    // Use prepared statements to insert data
    $stmt = $conn->prepare("INSERT INTO admin_data (admin_name, admin_user_id, admin_email, admin_password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $admin_name, $admin_user_id, $admin_email, $admin_password);
    try {
    if ($stmt->execute()) {
        echo "Admin Data Inserted successfully!";
        header("Location: login.php"); // Redirect to login page after successful registration
        exit();
    } 
    else {
        throw new Exception($stmt->error);
    }
}
catch(Exception $e) {
    echo "<script>alert('Please check the provided details. Registration or login failed.');</script>";
        echo "<script>window.location.href = 'admin_registration_form.php';</script>";
        exit(); 
}

    $stmt->close();
}

$conn->close();
?>
