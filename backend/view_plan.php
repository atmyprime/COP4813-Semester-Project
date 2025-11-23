<?php
require_once "config.php";

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$planId = (int)($_GET['id'] ?? 0);

// Fetch plan (ensure it belongs to the current user)
$stmt = $pdo->prepare("SELECT * FROM meal_plans WHERE id = :id AND user_id = :uid");
$stmt->execute([':id' => $planId, ':uid' => $_SESSION['user_id']]);
$plan = $stmt->fetch();

if (!$plan) {
    include "header.php";
    echo '<div class="alert alert-danger">Meal plan not found.</div>';
    include "footer.php";
    exit;
}

// Fetch items
$stmt = $pdo->prepare("
    SELECT mpi.plan_date, mpi.meal_type, r.name, r.calories
    FROM meal_plan_items mpi
    JOIN recipes r ON mpi.recipe_id = r.id
    WHERE mpi.meal_plan_id = :pid
    ORDER BY mpi.plan_date, FIELD(mpi.meal_type,'breakfast','lunch','dinner','snack')
");
$stmt->execute([':pid' => $planId]);
$items = $stmt->fetchAll();

// Group by date
$days = [];
foreach ($items as $item) {
    $days[$item['plan_date']][] = $item;
}
?>

<?php include "header.php"; ?>

<h2 class="mb-3">
  Meal Plan (<?= htmlspecialchars($plan['start_date']) ?> → <?= htmlspecialchars($plan['end_date']) ?>)
</h2>

<?php foreach ($days as $date => $meals): ?>
  <div class="card mb-3 shadow-sm">
    <div class="card-header">
      <strong><?= htmlspecialchars($date) ?></strong>
    </div>
    <div class="card-body">
      <div class="row">
        <?php foreach ($meals as $m): ?>
          <div class="col-md-4 mb-2">
            <div class="border rounded p-2 bg-white">
              <strong><?= ucfirst(htmlspecialchars($m['meal_type'])) ?></strong><br>
              <?= htmlspecialchars($m['name']) ?><br>
              <small><?= (int)$m['calories'] ?> kcal</small>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>

<?php include "footer.php"; ?>
