<?php
$AR_DB = null;
try {
  $AR_DB = new PDO('mysql:host=127.0.0.1;dbname=ar_plp3;charset=utf8mb4','root','',[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
  ]);
} catch (Throwable $e) {
  http_response_code(500);
  exit('Error de conexión');
}
