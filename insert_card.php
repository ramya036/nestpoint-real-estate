<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drop Users table PHP Script</title>
</head>

<body>
    <?php

require_once __DIR__ . '/config.php';
require_role('seller');
require("db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Form data (cast numbers so bad input can't break the query)
  $name    = trim($_POST['name'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $age     = (int)($_POST['age'] ?? 0);
  $bed     = (int)($_POST['bed'] ?? 0);
  $ad      = (int)($_POST['ad'] ?? 0);
  $garden  = trim($_POST['garden'] ?? '');
  $pa      = trim($_POST['pa'] ?? '');
  $tax     = (int)($_POST['tax'] ?? 0);

  // Only accept real images up to 5 MB, saved under a random name
  $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
  $file = $_FILES['file'] ?? null;
  $type = ($file && $file['error'] === UPLOAD_ERR_OK) ? mime_content_type($file['tmp_name']) : null;

  if (!$type || !isset($allowed[$type]) || $file['size'] > 5 * 1024 * 1024) {
    echo "<p>Please upload a JPG, PNG or WEBP image under 5 MB.</p>";
  } else {
    $file_name = bin2hex(random_bytes(8)) . '.' . $allowed[$type];
    move_uploaded_file($file['tmp_name'], __DIR__ . '/img/' . $file_name);

    $stmt = $conn->prepare(
      "INSERT INTO card (name, address, age, bed, ad, garden, pa, tax, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssiiissis", $name, $address, $age, $bed, $ad, $garden, $pa, $tax, $file_name);
    $stmt->execute();
    $stmt->close();
    header("Location: seller_dashboard.php");
    exit;
  }

  $conn->close();

}
?>
<?php
header("Location: seller_dashboard.php");
?>
</body>

</html>