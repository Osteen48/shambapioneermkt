<?php
require 'config.php';
$msg = "";

$token = $_GET['token'] ?? '';
$role  = $_GET['role'] ?? '';
$table = ($role === 'buyer') ? 'buyers' : 'sellers';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token    = $_POST['token'];
    $role     = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $table    = ($role === 'buyer') ? 'buyers' : 'sellers';

    // Validate token
    $stmt = $conn->prepare("SELECT id FROM $table WHERE reset_token=? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $upd = $conn->prepare("UPDATE $table 
            SET password=?, reset_token=NULL, reset_expires=NULL 
            WHERE reset_token=?");
        $upd->bind_param("ss", $password, $token);
        $upd->execute();
        $msg = "✅ Password reset successful. <a href='auth.php'>Login</a>";
    } else {
        $msg = "❌ Invalid or expired token.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<style>
body {font-family:Arial;background:#f4f7f5;display:flex;justify-content:center;align-items:center;height:100vh;}
.box {background:#fff;padding:30px;border-radius:8px;width:350px;box-shadow:0 0 10px rgba(0,0,0,.15);}
label {display:block;margin-top:10px;}
input {width:100%;padding:10px;margin-top:5px;border:1px solid #ccc;border-radius:6px;}
button {margin-top:15px;width:100%;background:#4CAF50;color:#fff;padding:12px;border:none;border-radius:6px;cursor:pointer;}
button:hover {background:#388E3C;}
.msg {margin-top:15px;color:#333;}
</style>
</head>
<body>
<div class="box">
<h2>Create New Password</h2>
<form method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
    <label>New Password</label>
    <input type="password" name="password" required>
    <button type="submit">Reset Password</button>
</form>
<div class="msg"><?= $msg ?></div>
</div>
</body>
</html>
