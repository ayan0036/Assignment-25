 <?php require 'config.php';
if (!isset($_SESSION['admin'])) exit('Unauthorized');

$id     = (int)($_POST['id']     ?? 0);
$status = $_POST['status'] ?? 'Pending';
if (!in_array($status, ['Pending','Selected','Rejected'])) exit('Bad status');

$stmt = $pdo->prepare("UPDATE applications SET status=? WHERE id=?");
$stmt->execute([$status, $id]);

header('Location: dashboard.php');
