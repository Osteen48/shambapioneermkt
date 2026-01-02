<?php
session_start();

// ✅ Database config (your earlier PDO connection)
$dsn = "mysql:host=localhost;dbname=agrimarket;charset=utf8mb4";
$user = "root";
$pass = "";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn,$user,$pass,$options);
} catch (PDOException $e) {
    die("Database connection failed: ".$e->getMessage());
}

$msg = "";

/* ---------- LOGIN ---------- */
if (isset($_POST['login'])) {
    $roleRadio = $_POST['role']; // buyer or seller from radio button
    $role = ($roleRadio === 'buyer') ? 'customer' : 'seller';
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? AND role=? LIMIT 1");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password,$user['password'])) {
        if ($user['status']==='active') {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'seller') {
                header("Location: \Agriweb\Seller\index.php");
            } else {
                header("Location: \Agriweb\Buyer\index.php");
            }
            exit;
        } else {
            $msg = "⚠️ Your account is pending or disabled. Contact the admin.";
        }
    } else {
        $msg = "❌ Invalid email or password.";
    }
}

/* ---------- REGISTER BUYER ---------- */
if (isset($_POST['register_buyer'])) {
    $name = trim($_POST['fname'])." ".trim($_POST['lname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = ''; // optional
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $pdo->prepare("INSERT INTO users (name,email,password,phone,address,role,status)
                       VALUES (?,?,?,?,?,'customer','active')")
            ->execute([$name,$email,$password,$phone,$address]);
        header("Location: auth.php?registered=buyer");
        exit;
    } catch (PDOException $e) {
        $msg = ($e->getCode()=='23000') ? "⚠️ Email already exists." : $e->getMessage();
    }
}

/* ---------- REGISTER SELLER ---------- */
if (isset($_POST['register_seller'])) {
    // Combine business name & owner name into one 'name' column
    $name = trim($_POST['business_name'])." (Owner: ".trim($_POST['owner_name']).")";
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = ''; // can extend later
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $pdo->prepare("INSERT INTO users (name,email,password,phone,address,role,status)
                       VALUES (?,?,?,?,?,'seller','pending')")
            ->execute([$name,$email,$password,$phone,$address]);
        header("Location: auth.php?registered=seller");
        exit;
    } catch (PDOException $e) {
        $msg = ($e->getCode()=='23000') ? "⚠️ Email already exists." : $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agriconnect | Auth</title>
<style>
body {font-family: Arial, sans-serif;background: #f4f7f5;
      display:flex;justify-content:center;align-items:center;min-height:100vh;}
.auth-box {background:#fff;width:420px;border-radius:12px;padding:25px;
           box-shadow:0 0 12px rgba(0,0,0,0.15);}
.tabs {display:flex;justify-content:space-between;margin-bottom:20px;}
.tab {flex:1;padding:12px;border:none;cursor:pointer;background:#eee;font-weight:bold;transition:0.2s;}
.tab.active {background:#4CAF50;color:white;}
form {display:none;flex-direction:column;}
form.active {display:flex;}
label {margin:8px 0 4px;}
input {padding:10px;border:1px solid #ddd;border-radius:6px;}
.submit-btn {margin-top:15px;background:#4CAF50;color:#fff;border:none;
             padding:12px;cursor:pointer;border-radius:6px;}
.submit-btn:hover {background:#388E3C;}
.role-choice {margin:10px 0;display:flex;justify-content:space-around;}
.forgot {margin-top:10px;text-align:right;font-size:14px;}
.forgot a {color:#4CAF50;text-decoration:none;}
.forgot a:hover {text-decoration:underline;}
.message{background:#f8d7da;color:#721c24;padding:8px;margin-bottom:10px;
         border:1px solid #f5c6cb;border-radius:6px;text-align:center;}
.success{background:#d4edda;color:#155724;padding:8px;margin-bottom:10px;
         border:1px solid #c3e6cb;border-radius:6px;text-align:center;}
</style>
</head>
<body>

<div class="auth-box">
    <div class="tabs">
        <button class="tab active" onclick="showForm('login')">Login</button>
        <button class="tab" onclick="showForm('buyer')">Register Buyer</button>
        <button class="tab" onclick="showForm('seller')">Register Seller</button>
    </div>

    <?php if($msg): ?><div class="message"><?php echo $msg; ?></div><?php endif; ?>
    <?php if(isset($_GET['registered'])): ?>
        <div class="success">
            ✅ <?php echo ucfirst($_GET['registered']); ?> account created successfully! Please log in.
        </div>
    <?php endif; ?>

    <!-- LOGIN -->
    <form id="login" class="active" method="POST">
        <h2>Welcome Back</h2>
        <div class="role-choice">
            <label><input type="radio" name="role" value="buyer" required> Customer</label>
            <label><input type="radio" name="role" value="seller" required> Seller</label>
        </div>
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email" required>
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit" class="submit-btn" name="login">Login</button>
        <div class="forgot">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </form>

    <!-- REGISTER BUYER -->
    <form id="buyer" method="POST">
        <h2>Create Buyer Account</h2>
        <label>First Name</label>
        <input type="text" name="fname" required>
        <label>Last Name</label>
        <input type="text" name="lname" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Phone</label>
        <input type="text" name="phone" placeholder="+254 700 000000" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit" class="submit-btn" name="register_buyer">Register as Buyer</button>
    </form>

    <!-- REGISTER SELLER -->
    <form id="seller" method="POST">
        <h2>Create Seller Account</h2>
        <label>Business Name</label>
        <input type="text" name="business_name" required>
        <label>Owner Name</label>
        <input type="text" name="owner_name" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Phone</label>
        <input type="text" name="phone" placeholder="+254 700 000000" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit" class="submit-btn" name="register_seller">Register as Seller</button>
    </form>
</div>

<script>
function showForm(id){
    document.querySelectorAll('form').forEach(f=>f.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
    const idx = {login:0,buyer:1,seller:2}[id];
    document.querySelectorAll('.tab')[idx].classList.add('active');
}
</script>
</body>
</html>
