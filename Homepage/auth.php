
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agriconnect | Auth</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f7f5;
    display: flex; justify-content: center; align-items: center;
    min-height: 100vh;
}
.auth-box {
    background: #fff;
    width: 420px;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 0 12px rgba(0,0,0,0.15);
}
.tabs {display: flex; justify-content: space-between; margin-bottom: 20px;}
.tab {
    flex: 1; padding: 12px;
    border: none; cursor: pointer;
    background: #eee; font-weight: bold;
    transition: 0.2s;
}
.tab.active { background: #4CAF50; color: white; }
form {display: none; flex-direction: column;}
form.active {display: flex;}
label {margin: 8px 0 4px;}
input, select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}
.submit-btn {
    margin-top: 15px;
    background: #4CAF50;
    color: #fff;
    border: none;
    padding: 12px;
    cursor: pointer;
    border-radius: 6px;
}
.submit-btn:hover {background: #388E3C;}
.role-choice {
    margin: 10px 0;
    display: flex;
    justify-content: space-around;
}
.forgot {
    margin-top: 10px;
    text-align: right;
    font-size: 14px;
}
.forgot a { color: #4CAF50; text-decoration: none; }
.forgot a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="auth-box">
    <div class="tabs">
        <button class="tab active" onclick="showForm('login')">Login</button>
        <button class="tab" onclick="showForm('buyer')">Register Buyer</button>
        <button class="tab" onclick="showForm('seller')">Register Seller</button>
    </div>

    <!-- LOGIN -->
    <!-- Place this inside your auth.php where the login form is -->
<form id="login" class="active" method="POST" action="process_login.php">
    <h2>Welcome Back</h2>
    <div class="role-choice">
        <label><input type="radio" name="role" value="buyer" required> Buyer</label>
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
    <form id="buyer" method="POST" action="process_register_buyer.php">
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
        <button type="submit" class="submit-btn">Register as Buyer</button>
    </form>

    <!-- REGISTER SELLER -->
    <form id="seller" method="POST" action="process_register_seller.php">
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
        <button type="submit" class="submit-btn">Register as Seller</button>
    </form>
</div>

<script>
function showForm(id){
    document.querySelectorAll('form').forEach(f=>f.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
    const btn = [...document.querySelectorAll('.tab')].find(b=>b.textContent.toLowerCase().includes(id));
    if(btn) btn.classList.add('active');
}
</script>
<?php if(isset($_GET['registered'])): ?>
    <div style="background:#d4edda;color:#155724;padding:10px;margin-bottom:15px;
                border:1px solid #c3e6cb;border-radius:6px;text-align:center;">
        ✅ <?php echo ucfirst($_GET['registered']); ?> account created successfully!  
        Please log in below.
    </div>
<?php endif; ?>

<?php if(isset($_GET['registered']) && $_GET['registered'] === 'buyer'): ?>
    <div style="background:#d4edda;color:#155724;padding:10px;margin-bottom:15px;
                border:1px solid #c3e6cb;border-radius:6px;text-align:center;">
        ✅ Buyer account created successfully!  
        Please log in below.
    </div>
<?php endif; ?>

</body>
</html>
