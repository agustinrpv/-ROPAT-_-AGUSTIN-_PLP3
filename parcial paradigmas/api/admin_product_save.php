<?php
require __DIR__.'/../includes/AR_conexion.php';
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) { http_response_code(400); exit; }
$id = $input['id'] ?? null;
if ($id) {
  $stmt = $AR_DB->prepare('UPDATE ar_products SET name=?,category=?,price=?,stock=? WHERE id=?');
  $stmt->execute([$input['name'],$input['category'],$input['price'],$input['stock'],$id]);
} else {
  $stmt = $AR_DB->prepare('INSERT INTO ar_products (name,category,price,stock) VALUES (?,?,?,?)');
  $stmt->execute([$input['name'],$input['category'],$input['price'],$input['stock']]);
}
echo json_encode(['ok'=>true]);
