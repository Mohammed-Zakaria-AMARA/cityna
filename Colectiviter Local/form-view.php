<?php
require 'includes/db.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM reports WHERE id = ?");
$stmt->execute([$id]);
$report = $stmt->fetch();

if (!$report) {
  echo "Report not found.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Form Details</title>
  <link rel="stylesheet" href="styles/global.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="form-details">
    <div class="form-container">
      <h2>From Details</h2>
      <p><strong>Category:</strong> <?= $report['category'] ?></p>
      <p><strong>Subcategory:</strong> <?= $report['subcategory'] ?></p>
      <p><strong>Description:</strong><br><?= $report['description'] ?></p>
      <p><strong>Commune:</strong> <?= $report['commune'] ?> | Wilaya: <?= $report['wilaya'] ?></p>
      <p><strong>Citoyen:</strong> <?= $report['full_name'] ?> | 📞 <?= $report['phone'] ?></p>
      <a href="dashboard-collectivite.php" class="btn secondaryBtn">Back</a>
    </div>
  </main>
</body>
</html>
