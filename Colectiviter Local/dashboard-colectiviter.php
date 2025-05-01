<?php
// Assume database connection and session already established
require 'includes/db.php'; // your DB connection file

// Fetch reports from DB (filtering logic should be added later)
$reports = $conn->query("SELECT * FROM reports ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Local Authority Dashboard</title>
  <link rel="stylesheet" href="styles/global.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="content">
    <h1>Report List</h1>

    <form method="GET" class="filters">
      <input type="date" name="date">
      <select name="category">
        <option disabled selected>Auto selected</option>
        <option value="Sécurité Publique">Sécurité Publique</option>
        <option value="Propreté et environnement">Propreté</option>
        <!-- ... -->
      </select>
      <select name="subcategory">
        <option disabled selected>Dropdown</option>
        <!-- dynamically filled in future -->
      </select>
      <select name="status">
        <option disabled selected>Auto selected</option>
        <option value="en attente">En attente</option>
        <option value="en cours">En cours</option>
        <option value="résolu">Résolu</option>
      </select>
      <button type="submit" class="btn secondaryBtn">Apply</button>
    </form>

    <a href="collectivite-grid.php" class="btn secondaryBtn">Go back to Collectivité</a>

    <div class="report-grid">
      <?php foreach ($reports as $report): ?>
        <div class="problem-card">
          <p><strong>Problem:</strong> <?= htmlspecialchars($report['title']) ?></p>
          <p><strong>Status:</strong>
            <span class="status <?= $report['status'] ?>"><?= ucfirst($report['status']) ?></span>
          </p>
          <a href="form-view.php?id=<?= $report['id'] ?>" class="btn primaryBtn">Details</a>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>
