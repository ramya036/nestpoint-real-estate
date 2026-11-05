<?php
session_start();
require_once __DIR__ . '/config.php';
$conn = db_mysqli();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_user_id = $_POST['admin_user_id_login'];
    $admin_password = $_POST['admin_password_login'];

    // Use prepared statements to fetch data
    $stmt = $conn->prepare("SELECT * FROM admin_data WHERE admin_user_id = ?");
    $stmt->bind_param("s", $admin_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($admin_password, $row['admin_password'])) {
            // Password is correct, redirect to dashboard
            $_SESSION['user_type'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Invalid password
            echo "<script>
        alert('Invalid credentials');
        window.location.href = 'login.php?error=invalid';
    </script>";
    exit();
        }
    } else {
        // Invalid username
        echo "<script>
        alert('Invalid credentials');
        window.location.href = 'login.php?error=invalid';
    </script>";
    exit();
    }

    $stmt->close();
}

$conn->close();
?>
