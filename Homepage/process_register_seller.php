<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $business_name = $_POST['business_name'];
    $owner_name    = $_POST['owner_name'];
    $email         = $_POST['email'];
    $phone         = $_POST['phone'];
    $password      = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO sellers 
        (business_name, owner_name, email, phone, password)
        VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss",
        $business_name,$owner_name,$email,$phone,$password);

    if ($stmt->execute()) {
        header("Location: auth.php?registered=seller");
        exit;
    } else {
        die("Database Error: " . $stmt->error);
    }
}
