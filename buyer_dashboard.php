<?php
require_once __DIR__ . '/config.php';
require_role('buyer', 'admin');
$user_type = $_SESSION['user_type'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Nest Point: Buyer Dashboard</title>
    <link href="style_index.css" rel="stylesheet"> 
    <link href="style_buyer.css" rel="stylesheet"> 
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
        .navigation {
          display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 64, 128, 0.9);
    padding: 10px 20px;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
        }

        .navigation a {
            text-decoration: none;
            color: white;
            font-size: 1.1rem;
            margin: 0 15px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .navigation a:hover {
            background-color: #007acc;
            transform: scale(1.1);
        }

        .header {
            text-align: center;
            font-size: 2.5rem;
            margin: 30px 0;
            color: #f4f4f4;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        /* Buyer Dashboard Cards */
        .buyer_dashboard {
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
            text-align: center;
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
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .flip-card-front img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .flip-card-back {
            background: linear-gradient(to bottom, #0072ff, #00c6ff);
            color: white;
            padding: 20px;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 10px;
            text-align: left;
        }

        .flip-card-back h2 {
            font-size: 1.5rem;
        }

        .flip-card-back p {
            font-size: 1rem;
            margin: 5px 0;
        }
        .welcome-message {
    text-align: center;
    margin-top: 60px;
    color: #f4f4f4;
    font-size: 2.5rem;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }
        .navigation a.active {
        background-color: #3498db; /* Blue color for active link */
        color: white;
        font-weight: bold;
        border-bottom: 2px solid #1abc9c; /* Underline effect */
        transition: background 0.3s ease, border-bottom 0.3s ease;
    }

        .search-bar {
            margin: 20px auto;
            text-align: center;
        }

        .search-bar input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .search-bar button {
            padding: 10px 20px;
            margin-left: 10px;
            background-color: #007acc;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #005f99;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <div class="navigation">
    <?php if ($user_type === 'buyer'): ?>
          <a href="buyer_dashboard.php"class="active">DashBoard</a>
        <?php elseif ($user_type === 'admin'): ?>
            <a href="buyer_dashboard.php" class="active">Buyer DashBoard</a>
            <a href="seller_dashboard.php" >Seller DashBoard</a>
            <a href="form.php">Add Property</a>
            <a href="delete_card">Delete Property</a>
            <?php endif; ?>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Header -->
    <h1 class="welcome-message">Welcome, Buyer!</h1>

    <!-- Search Bar -->
    <div class="search-bar">
        <form action="buyer_dashboard.php" method="GET">
            <input type="text" name="search" placeholder="Search properties by location, bedrooms, garden, parking and price">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Buyer Dashboard Cards -->
    <div class="buyer_dashboard">
        <?php
        // connect to the database
        require_once __DIR__ . '/config.php';
        $pdo = db_pdo();

        // Check if a search query was submitted
        $search_query = isset($_GET['search']) ? $_GET['search'] : '';
        
        // Modify SQL query based on search input
        if (!empty($search_query)) {
            $sql = "SELECT * FROM card WHERE bed LIKE :search OR garden LIKE :search OR pa LIKE :search OR tax LIKE :search OR address LIKE :search";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['search' => "%$search_query%"]);
        } else {
            $sql = "SELECT * FROM card";
            $stmt = $pdo->query($sql);
        }

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
            echo '<p><b>Garden: </b>' . htmlspecialchars($row['garden']) . '</p>';
            echo '<p><b>Parking: </b>' . htmlspecialchars($row['pa']) . '</p>';
            echo '<p><b>Price: </b>$' . htmlspecialchars($row['tax']) . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>

</html>
