 <?php require 'config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ----- 1. Basic text validation ----- */
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($name === '')               $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Invalid email.";

    /* ----- 2. File validation ----- */
    $file = $_FILES['resume'] ?? null;
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Resume file is required.";
    } else {
        $maxSize = 2 * 1024 * 1024;                      // 2 MB
        $allowedExt  = ['pdf','doc','docx'];
        $allowedMime = ['application/pdf',
                        'application/msword',
                        // common DOCX MIME variations
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $size = $file['size'];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);

        if (!in_array($ext,  $allowedExt) || !in_array($mime, $allowedMime))
            $errors[] = "Only PDF or DOC/DOCX allowed.";
        if ($size > $maxSize)
            $errors[] = "File too large (max 2 MB).";
    }

    /* ----- 3. If all good → move file & insert DB ----- */
    if (!$errors) {
        $newName = uniqid('CV_', true) . '.' . $ext;
        $uploadDir = __DIR__ . '/uploads/';
        if (!move_uploaded_file($file['tmp_name'], $uploadDir.$newName)) {
            $errors[] = "Failed to store file.";
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO applications (name,email,resume_path,status) VALUES (?,?,?, 'Pending')"
            );
            $stmt->execute([$name, $email, $newName]);

            $success = "Application submitted! ✅";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><title>Internship Registration</title>
<link rel="stylesheet" href="https://unpkg.com/missing.css">
</head><body>
<h1>Apply for Internship</h1>

<?php if ($errors): ?>
    <ul style="color:red"><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
<?php elseif (!empty($success)): ?>
    <p style="color:green"><?= $success ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Name: <input type="text" name="name" required></label><br><br>
    <label>Email: <input type="email" name="email" required></label><br><br>
    <label>Resume (PDF/DOC, ≤2 MB):
        <input type="file" name="resume" accept=".pdf,.doc,.docx" required>
    </label><br><br>
    <button type="submit">Submit Application</button>
</form>
</body></html>
