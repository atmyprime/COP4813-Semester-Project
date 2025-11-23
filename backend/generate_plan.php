<?php
require_once "config.php";

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$days      = (int)($_POST['days'] ?? 0);
$calories  = (int)($_POST['calories'] ?? 0);
$diet_type = $_POST['diet_type'] ?? 'none';

$errors = [];

// Basic input validation
if ($days < 1 || $days > 14) {
    $errors[] = "Days must be between 1 and 14.";
}
if ($calories < 1200 || $calories > 4000) {
    $errors[] = "Calories must be between 1200 and 4000.";
}
$validDiets = ['none','balanced','low_carb','high_protein','vegetarian'];
if (!in_array($diet_type, $validDiets, true)) {
    $errors[] = "Invalid diet type.";
}

if ($errors) {
    // Store errors in session and redirect back if you want
    // For now, just dump them
    include "header.php";
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>';
    echo '</ul><a href="dashboard.php" class="btn btn-secondary mt-2">Back</a></div>';
    include "footer.php";
    exit;
}

// Create meal plan
$userId    = $_SESSION['user_id'];
$startDate = date('Y-m-d');
$endDate   = date('Y-m-d', strtotime("+".($days-1)." days"));

$pdo->beginTransaction();

// Insert meal_plans row
$stmt = $pdo->prepare("INSERT INTO meal_plans (user_id, start_date, end_date, total_calories)
                       VALUES (:uid, :sd, :ed, :tc)");
$stmt->execute([
    ':uid' => $userId,
    ':sd'  => $startDate,
    ':ed'  => $endDate,
    ':tc'  => $calories * $days
]);
$planId = $pdo->lastInsertId();

// For simplicity, just choose random recipes per meal type
$mealTypes = ['breakfast','lunch','dinner'];

for ($i = 0; $i < $days; $i++) {
    $currentDate = date('Y-m-d', strtotime("+$i days"));
    foreach ($mealTypes as $mt) {

        // Filter recipes by meal_type and diet_type (or 'none')
        if ($diet_type === 'none') {
            $sql = "SELECT id FROM recipes WHERE meal_type = :mt ORDER BY RAND() LIMIT 1";
            $params = [':mt' => $mt];
        } else {
            $sql = "SELECT id FROM recipes 
                    WHERE meal_type = :mt 
                      AND (diet_type = :dt OR diet_type = 'none')
                    ORDER BY RAND() LIMIT 1";
            $params = [':mt' => $mt, ':dt' => $diet_type];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $recipe = $stmt->fetch();

        if ($recipe) {
            $insertItem = $pdo->prepare(
                "INSERT INTO meal_plan_items (meal_plan_id, plan_date, meal_type, recipe_id)
                 VALUES (:pid, :pd, :mt, :rid)"
            );
            $insertItem->execute([
                ':pid' => $planId,
                ':pd'  => $currentDate,
                ':mt'  => $mt,
                ':rid' => $recipe['id']
            ]);
        }
        // If no matching recipe, you could log or handle it; for now it just skips.
    }
}

$pdo->commit();

// Redirect to view plan
header("Location: view_plan.php?id=" . $planId);
exit;
