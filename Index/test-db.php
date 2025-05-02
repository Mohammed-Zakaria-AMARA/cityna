<?php
require 'db_connection.php';
$pdo = getDbConnection();

// Test query
$stmt = $pdo->query("SELECT 1 AS test");
$result = $stmt->fetch();
echo "Database test: ".($result['test'] === 1 ? "Success!" : "Failed");?>