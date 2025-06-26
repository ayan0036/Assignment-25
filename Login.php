 <?php require 'config.php';

if (isset($_SESSION['admin'])) header('Location: dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE username = ?");
    $stmt->execute([$user]);
    $row = $stmt->fetch();

    if ($row && password_verify($pass, $row['password_hash'])) {
        $_SESSION['admin'] = $user;
        header('Location: dashboard.php'); exit;
    }
    $error = "Invalid credentials.";
}
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Admin Login</title></head><body>
<h2>Admin Login</h2>
<?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
<form method="post">
    <input name="username" placeholder="Username" required>
    <input name="password" type="password" placeholder="Password" required>
    <button>Login</button>
</form>
</body></html>
