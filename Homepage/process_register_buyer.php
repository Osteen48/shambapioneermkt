 <?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role  = $_POST['role'];
    $table = ($role === 'buyer') ? 'buyers' : 'sellers';

    $stmt = $conn->prepare("SELECT id, password FROM $table WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['role']    = $role;
            header("Location: " . ($role === 'buyer' ? "Buyer/index.php" : "Seller/index.php"));
            exit;
        } else {
            header("Location: auth.php?error=Invalid password");
            exit;
        }
    } else {
        header("Location: auth.php?error=Email not found");
        exit;
    }
}
