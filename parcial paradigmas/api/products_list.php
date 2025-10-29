<?php
require __DIR__.'/../includes/AR_conexion.php';
$cat = $_GET['category'] ?? 'all';
if ($cat === 'all') {
  $stmt = $AR_DB->query('SELECT id,name,category,price,stock FROM ar_products ORDER BY category,name');
} else {
  $stmt = $AR_DB->prepare('SELECT id,name,category,price,stock FROM ar_products WHERE category=? ORDER BY name');
  $stmt->execute([$cat]);
}
echo json_encode($stmt->fetchAll());
