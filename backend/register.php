<?php
require_once "../includes/config.php";

$errors = [];
$name = $email = $password = $confirm = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // Basic validation
    if ($name === '')   $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (:n, :e, :p)");
            $stmt->execute([
                ':n' => $name,
                ':e' => $email,
                ':p' => $hash
            ]);

            header("Location: login.php?registered=1");
            exit;
        } catch (PDOException $e) {
            // Duplicate email
            if ($e->getCode() == 23000) {
                $errors[] = "That email is already registered.";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<?php include "../includes/header.php"; ?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <h2 class="mb-3">Create an Account</h2>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" novalidate>
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control"
               value="<?= htmlspecialchars($name) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
               value="<?= htmlspecialchars($email) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password (min 8 chars)</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success">Register</button>
    </form>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
