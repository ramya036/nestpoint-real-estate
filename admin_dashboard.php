<?php
require_once __DIR__ . '/config.php';
require_role('admin');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <link href="style_index.css" rel="stylesheet">
    <link href="style_seller.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

        /* Navigation Bar */
        .navbar {
          display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 64, 128, 0.9);
    padding: 29px 20px;
    position: absolute;
    width: 100%;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
        }

        .navbar a {
            text-decoration: none;
            color: white;
            font-size: 1.2rem;
            margin: 0 15px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .navbar a:hover {
            background-color: #007acc;
            transform: scale(1.1);
        }

        .navbar .brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
        }

        .welcome {
            text-align: center;
            font-size: 2rem;
            color: whitesmoke;
            margin-top: 150px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 50px;
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
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }

        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 10px;
            overflow: hidden;
        }

        .flip-card-front {
            background-color: #bbb;
            color: black;
        }

        .flip-card-front img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .flip-card-back {
            background: linear-gradient(to bottom, #0072ff, #00c6ff);
            color: white;
            text-align: left;
            padding: 20px;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .flip-card-back h2 {
            font-size: 1.5rem;
        }

        .flip-card-back p {
            font-size: 1rem;
            margin: 5px 0;
        }
        .navbar a.active {
        background-color: #3498db; /* Blue color for active link */
        color: white;
        font-weight: bold;
        border-bottom: 2px solid #1abc9c; /* Underline effect */
        transition: background 0.3s ease, border-bottom 0.3s ease;
    }
    </style>
</head>

<body>
    <div class="navbar">
        <div>
            <a href="buyer_dashboard.php" class="active">Buyer DashBoard</a>
            <a href="seller_dashboard.php">Seller DashBoard</a>
            <a href="form.php">Add Property</a>
            <a href="delete_card">Delete Property</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <p class="welcome">Welcome, Admin!</p>

    <div class="card-container">
        <?php
        // connect to the database
        require_once __DIR__ . '/config.php';
        $pdo = db_pdo();

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
            echo '<h2><b>Apartment: </b>' . htmlspecialchars($row['name'] ?: 'Home listing') . '</h2>';
            echo '<p><b>Address: </b>' . htmlspecialchars($row['address']) . '</p>';
            echo '<p><b>Age: </b>' . htmlspecialchars($row['age']) . '</p>';
            echo '<p><b>No. of Beds: </b>' . htmlspecialchars($row['bed']) . '</p>';
            echo '<p><b>No. of Baths: </b>' . htmlspecialchars($row['ad']) . '</p>';
            echo '<p><b>Garden available: </b>' . htmlspecialchars($row['garden']) . '</p>';
            echo '<p><b>Parking available: </b>' . htmlspecialchars($row['pa']) . '</p>';
            echo '<p><b>Price: </b>$' . htmlspecialchars($row['tax']) . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>

</html>
