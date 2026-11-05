<?php
require_once __DIR__ . '/config.php';
require_role('seller', 'admin');
$user_type = $_SESSION['user_type'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nest Point: Seller Dashboard</title>
    <link href="style_index.css" rel="stylesheet"> 
    <link href="style_seller.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    /* Reset and General Styles */
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

    /* Navigation Bar Styles */
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

    .welcome-message {
        text-align: center;
        margin-top: 60px;
        color: #f4f4f4;
        font-size: 2.5rem;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
    }

    /* Card Styles */
    .card-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        padding: 40px;
    }

    .flip-card {
        background-color: transparent;
        width: 300px;
        height: 400px;
        perspective: 1000px;
        transition: transform 0.5s ease, box-shadow 0.3s ease;
    }

    .flip-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }

    .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: left;
        transition: transform 0.8s;
        transform-style: preserve-3d;
    }

    .flip-card:hover .flip-card-inner {
        transform: rotateY(180deg);
    }

    .flip-card-front, .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 10px;
        overflow: hidden;
    }

    .flip-card-front {
        background-color: #f3f3f3;
    }

    .flip-card-front img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .flip-card-back {
        background: linear-gradient(to bottom, #0072ff, #00c6ff);
        color: white;
        padding: 20px;
        transform: rotateY(180deg);
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start; /* Aligns text to the left */
        gap: 10px;
        text-align: left; /* Ensures left alignment for text */
        border-radius: 10px;
    }

    .flip-card-back h2 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .flip-card-back p {
        font-size: 1rem;
        margin: 5px 0;
    }

    /* Add New Card Styles */
    .add-card {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 300px;
        height: 400px;
        background-color: #f3f3f3;
        border: 2px dashed #007acc;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .add-card:hover {
        background-color: #007acc;
        transform: scale(1.1);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }

    .add-card img {
        width: 50px;
        height: 50px;
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
        <!-- <a href="#" class="active">Dashboard</a>
        <a href="index.php">Home</a>
        <a href="form.php" class="add-link">Add Property</a>
        <a href="delete_card.php" class="delete-link">Delete Property</a>
        <a href="logout.php">Logout</a>
        <div> -->
        <?php if ($user_type === 'seller'): ?>
          <a href="seller_dashboard.php"class="active">DashBoard</a>
        <a href="form.php" class="add-link">Add Property</a>
        <a href="delete_card.php" class="delete-link">Delete Property</a>
        <a href="logout.php">Logout</a>
        <?php elseif ($user_type === 'admin'): ?>
            <a href="buyer_dashboard.php" >Buyer DashBoard</a>
            <a href="seller_dashboard.php" class="active">Seller DashBoard</a>
            <a href="form.php">Add Property</a>
            <a href="delete_card">Delete Property</a>
            <a href="logout.php">Logout</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="welcome-message">Welcome, Seller!</div>

    <!-- Card Container -->
    <div class="card-container">
        <?php
        // connect to the database
        try {
          require_once __DIR__ . '/config.php';
        $pdo = db_pdo();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // query to retrieve the data from the database
            $sql = "SELECT * FROM card";
            $stmt = $pdo->query($sql);

            // loop through the data and create a card for each row
            foreach ($stmt as $row) {
                $image_path = 'img/' . $row['image'];

                echo '<div class="flip-card">';
                echo '<div class="flip-card-inner">';
                echo '<div class="flip-card-front">';
                echo '<img src="' . $image_path . '" alt="Property Image">';
                echo '</div>';
                echo '<div class="flip-card-back">';
                echo '<h2>' . htmlspecialchars($row['name'] ?: 'Home listing') . '</h2>';
                echo '<p>ID: ' . htmlspecialchars($row['id']) . '</p>';
                echo '<p>Address: ' . htmlspecialchars($row['address']) . '</p>';
                echo '<p>Age: ' . htmlspecialchars($row['age']) . ' years</p>';
                echo '<p>Beds: ' . htmlspecialchars($row['bed']) . '</p>';
                echo '<p>Baths: ' . htmlspecialchars($row['ad']) . '</p>';
                echo '<p>Garden: ' . htmlspecialchars($row['garden']) . '</p>';
                echo '<p>Parking: ' . htmlspecialchars($row['pa']) . '</p>';
                echo '<p>Price: $' . htmlspecialchars($row['tax']) . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>

        <!-- Add New Card -->
        <div class="add-card">
            <a href="form.php">
                <img src="img/plus.jpg" alt="Add New">
            </a>
        </div>
    </div>

</body>
</html>
