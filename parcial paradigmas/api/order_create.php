<?php
require __DIR__.'/../includes/AR_conexion.php';
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || empty($input['items']) || empty($input['customer'])) { http_response_code(400); exit('Bad request'); }

$items = $input['items']; // [{id, qty, price}]
$customer = $input['customer']; // {name, phone, address}

$AR_DB->beginTransaction();
try {
  // Calcula totales en servidor
  $subtotal = 0.0;
  foreach ($items as $it) {
    $subtotal += (float)$it['price'] * (int)$it['qty'];
  }
  $total = $subtotal; // Aquí podrías sumar delivery o descuentos

  $stmt = $AR_DB->prepare('INSERT INTO tt_orders (customer_name,customer_phone,customer_address,subtotal,total) VALUES (?,?,?,?,?)');
  $stmt->execute([$customer['name'], $customer['phone'], $customer['address'], $subtotal, $total]);
  $orderId = (int)$AR_DB->lastInsertId();

  $stmtItem = $AR_DB->prepare('INSERT INTO tt_order_items (order_id,product_id,qty,unit_price) VALUES (?,?,?,?)');
  foreach ($items as $it) {
    $stmtItem->execute([$orderId, (int)$it['id'], (int)$it['qty'], (float)$it['price']]);
  }

  $AR_DB->commit();
  echo json_encode(['ok'=>true,'order_id'=>$orderId,'total'=>$total]);
} catch (Throwable $e) {
  $AR_DB->rollBack();
  http_response_code(500);
  echo json_encode(['ok'=>false]);
}
