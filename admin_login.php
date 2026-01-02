<?php
session_start();
require_once "config.php"; // your DB connection

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare(
        "SELECT user_id, email, password, role, status
         FROM users
         WHERE email = ? AND role = 'admin' AND status = 'active' LIMIT 1"
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role']    = $user['role'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="styles.css">
  <style>
body{font-family:Arial;background:#f4f4f4;}
.container{max-width:400px;margin:80px auto;background:#fff;padding:20px;
border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,.1);}
h2{text-align:center;color:#4CAF50;}
input{width:100%;padding:10px;margin:10px 0;border:1px solid #ccc;border-radius:5px;}
button{width:100%;padding:10px;background:#4CAF50;color:#fff;border:none;
border-radius:5px;cursor:pointer;}
button:hover{background:#388E3C;}
.message{color:#e53935;text-align:center;}
</style>
</head>
<body>
  <div class="login-container">
    <h2>Administrator Login</h2>
    <?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
    <form method="post">
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
