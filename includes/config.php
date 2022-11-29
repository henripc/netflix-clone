<?php

ob_start();
session_start();
date_default_timezone_set('America/Sao_Paulo');

$json = file_get_contents('env.json');
$envVar = json_decode($json, true);

try {
    $connection = new PDO("mysql:dbname={$envVar['DB_NAME']};host={$envVar['HOST']};port={$envVar['DB_PORT']}", $envVar['USERNAME'], $envVar['PASSWORD']);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (PDOException $e) {
    exit('Connection failed: ' . $e->getMessage());
}