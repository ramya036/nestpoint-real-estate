<?php require_once __DIR__ . '/config.php'; require_role('seller', 'admin'); ?>
<?php
session_start();
$user_type = $_SESSION['user_type'] ?? 'seller'; // Set to 'guest' if not logged in
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeQuest Real Estate: Manage Property</title>
    <link href="style_index.css" rel="stylesheet"> 
    <link href="style_seller.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('img/homebackground.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        .topnav {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 64, 128, 0.9);
        padding: 29px 20px;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
    }

    .topnav a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
        font-size: 1.2rem;
        padding: 8px 15px;
        border-radius: 5px;
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .topnav a:hover {
        background-color: #007acc;
        transform: scale(1.1);
    }

        .topnav a:hover {
            background-color: #4682b4;
            transform: scale(1.1);
        }

        .c {
            text-align: center;
            background-color: #f0f8ff;
            color: #333;
            width: 50%;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .c:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .c h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .c label {
            display: block;
            margin-bottom: 10px;
            font-size: 1.2em;
            text-align: left;
        }

        .c input, .c select {
            width: 90%;
            padding: 10px;
            font-size: 1em;
            border: 2px solid #1e90ff;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .c input[type="submit"] {
            width: 50%;
            background-color: #1e90ff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .c input[type="submit"]:hover {
            background-color: #4682b4;
        }

        .c span {
            font-size: 1.1em;
            color: #1e90ff;
        }

        .delete-form {
            margin-top: 40px;
        }

        .delete-form input {
            width: 90%;
            padding: 10px;
            font-size: 1em;
            border: 2px solid #dc3545;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .delete-form input[type="submit"] {
            background-color: #dc3545;
            color: white;
            cursor: pointer;
        }

        .delete-form input[type="submit"]:hover {
            background-color: #c82333;
        }
        .topnav a.active {
        background-color: #3498db; /* Blue color for active link */
        color: white;
        font-weight: bold;
        border-bottom: 2px solid #1abc9c; /* Underline effect */
        transition: background 0.3s ease, border-bottom 0.3s ease;
    }
    </style>
</head>
<body>
    <!-- Navigation Menu -->
    <div class="topnav">
    <?php if ($user_type === 'seller'): ?>
        <a href="seller_dashboard.php">Dashboard</a>
        
        <?php elseif ($user_type === 'admin'): ?>
            <a href="buyer_dashboard.php">Buyer DashBoard</a>
            <a href="seller_dashboard.php" >Seller DashBoard</a>
            <?php endif; ?>
        <a href="form.php">Add Property</a>
        <a href="delete_card.php" class="active">Delete Property</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="c">
        <div class="delete-form">
            <h1>Remove Property</h1>
            <form action="delete_card.php" method="post">
                <label for="card_id">Enter Card ID to Delete:</label>
                <input type="text" id="card_id" name="card_id" required>
                <input type="submit" value="Delete Property">
            </form>
        </div>
    </div>

    <script>
        // Function to update the displayed value of the number of beds
        function updateBedValue() {
            var bedValue = document.getElementById("bed").value;
            document.getElementById("bedValue").innerHTML = bedValue;
        }
    </script>
    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_id = $_POST['card_id'];

    require_once __DIR__ . '/config.php';
$conn = db_mysqli();

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM card WHERE id = ?");
    $stmt->bind_param("i", $card_id);

    if ($stmt->execute()) {
        echo "<script>showAlert('Data deleted successfully.');</script>";
    } else {
        echo "<script>showAlert('Error deleting data:.');</script>". $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
</body>
</html>
