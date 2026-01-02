<?php
require 'config.php';
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $role  = $_POST['role']; // buyer or seller
    $table = ($role === 'buyer') ? 'buyers' : 'sellers';

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM $table WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token   = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiry

        $upd = $conn->prepare("UPDATE $table SET reset_token=?, reset_expires=? WHERE email=?");
        $upd->bind_param("sss", $token, $expires, $email);
        $upd->execute();

        // Send email
        $resetLink = "http://yourdomain.com/reset_password.php?token=$token&role=$role";
        $subject = "Password Reset";
        $body    = "Hello,\n\nClick the link below to reset your password:\n$resetLink\n\nThis link expires in 1 hour.";
        mail($email, $subject, $body, "From: no-reply@yourdomain.com");

        $msg = "✅ A reset link has been sent to your email.";
    } else {
        $msg = "❌ No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<style>
body {font-family:Arial;background:#f4f7f5;display:flex;justify-content:center;align-items:center;height:100vh;}
.box {background:#fff;padding:30px;border-radius:8px;width:350px;box-shadow:0 0 10px rgba(0,0,0,.15);}
label {display:block;margin-top:10px;}
input,select {width:100%;padding:10px;margin-top:5px;border:1px solid #ccc;border-radius:6px;}
button {margin-top:15px;width:100%;background:#4CAF50;color:#fff;padding:12px;border:none;border-radius:6px;cursor:pointer;}
button:hover {background:#388E3C;}
.msg {margin-top:15px;color:#333;}
</style>
</head>
<body>
<div class="box">
<h2>Forgot Password</h2>
<form method="POST">
    <label>Role</label>
    <select name="role" required>
        <option value="">Select Role</option>
        <option value="buyer">Buyer</option>
        <option value="seller">Seller</option>
    </select>
    <label>Email</label>
    <input type="email" name="email" required>
    <button type="submit">Send Reset Link</button>
</form>
<div class="msg"><?= htmlspecialchars($msg) ?></div>
</div>
</body>
</html>
