<?php require 'config.php';
if (!isset($_SESSION['admin'])) header('Location: login.php');

$apps = $pdo->query("SELECT * FROM applications ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Dashboard</title>
<style>
table{border-collapse:collapse;width:100%}
th,td{border:1px solid #ccc;padding:6px;text-align:left}
.status-Pending  {background:#fffbe6}
.status-Selected {background:#e7ffe7}
.status-Rejected {background:#ffe7e7}
</style>
</head><body>
<h2>Applications Dashboard</h2>
<p><a href="logout.php">Logout</a></p>

<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Resume</th><th>Status</th><th>Action</th></tr>
<?php foreach ($apps as $a): ?>
<tr class="status-<?= $a['status'] ?>">
    <td><?= $a['id'] ?></td>
    <td><?= htmlspecialchars($a['name']) ?></td>
    <td><?= htmlspecialchars($a['email']) ?></td>
    <td><a href="uploads/<?= rawurlencode($a['resume_path']) ?>" target="_blank">Download</a></td>
    <td><?= $a['status'] ?></td>
    <td>
        <form method="post" action="update_status.php" style="display:inline">
            <input type="hidden" name="id" value="<?= $a['id'] ?>">
            <select name="status" onchange="this.form.submit()">
                <?php foreach (['Pending','Selected','Rejected'] as $s): ?>
                    <option value="<?= $s ?>"<?= $s==$a['status']?' selected':''?>><?= $s ?></option>
                <?php endforeach ?>
            </select>
        </form>
    </td>
</tr>
<?php endforeach ?>
</table>
</body></html>
