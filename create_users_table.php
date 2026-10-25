<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create tables PHP Script</title>
</head>

<body>
    <?php
    require("db.php");

    $sql = "CREATE TABLE seller_data(seller_id int(6) unsigned auto_increment primary key,
seller_name varchar(30) not null, 
seller_user_id varchar(20) not null, seller_email varchar(30) not null, seller_password varchar(255) not null);";

    if ($conn->query($sql) === TRUE) {
        echo "Table users created";
    } else {
        echo "Table users already exists";
    }

    $conn->close();
    ?>
    <div>
        <a href="signup.php"><input type="button" id="btn1" value="Home"></a>
    </div>
</body>

</html>