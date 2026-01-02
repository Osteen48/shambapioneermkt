 <?php
// process_login.php
session_start();
require __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: auth.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

if (!$email || !$password || !in_array($role, ['buyer','seller','admin'])) {
    header("Location: auth.php?error=Missing fields");
    exit;
}

// Use prepared statements
$stmt = $conn->prepare("SELECT user_id, password, status FROM users WHERE email=? AND role=? LIMIT 1");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['status'] !== 'active') {
        header("Location: auth.php?error=Account not active");
        exit;
    }

    if (password_verify($password, $row['password'])) {
        // Login success
        $_SESSION['user_id'] = (int)$row['user_id'];
        $_SESSION['role'] = $role;
        // regenerate id
        session_regenerate_id(true);

        // redirect based on role
        if ($role === 'buyer') header("Location: Buyers/index.php");
        elseif ($role === 'seller') header("Location: Sellers/index.php");
        else header("Location: Admin/index.php");
        exit;
    }
}

// fail
header("Location: auth.php?error=Invalid credentials");
exit;
