<?php
require_once "../includes/config.php";

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Optionally load last plan
$stmt = $pdo->prepare("SELECT * FROM meal_plans WHERE user_id = :uid ORDER BY created_at DESC LIMIT 1");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$lastPlan = $stmt->fetch();
?>

<?php include "../includes/header.php"; ?>

<h2 class="mb-3">Welcome, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h2>

<div class="row">
  <div class="col-md-6 mb-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="card-title">Generate a New Meal Plan</h4>
        <form action="generate_plan.php" method="post">
          <div class="mb-3">
            <label class="form-label">Number of days</label>
            <input type="number" name="days" class="form-control" min="1" max="14" value="7" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Daily calorie target</label>
            <input type="number" name="calories" class="form-control" min="1200" max="4000" value="2000" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Diet type</label>
            <select name="diet_type" class="form-select">
              <option value="none">No preference</option>
              <option value="balanced">Balanced</option>
              <option value="low_carb">Low carb</option>
              <option value="high_protein">High protein</option>
              <option value="vegetarian">Vegetarian</option>
            </select>
          </div>
          <button type="submit" class="btn btn-success w-100">Generate Plan</button>
        </form>
      </div>
    </div>
  </div>

  <?php if ($lastPlan): ?>
    <div class="col-md-6 mb-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title">Your Latest Plan</h4>
          <p>
            From <strong><?= htmlspecialchars($lastPlan['start_date']) ?></strong>
            to <strong><?= htmlspecialchars($lastPlan['end_date']) ?></strong>
          </p>
          <a href="view_plan.php?id=<?= $lastPlan['id'] ?>" class="btn btn-outline-success">
            View Plan
          </a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include "../includes/footer.php"; ?>
