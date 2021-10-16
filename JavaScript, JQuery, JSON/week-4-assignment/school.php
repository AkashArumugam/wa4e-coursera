<?php

require_once "pdo.php";
session_start();

if(!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
	die('ACCESS DENIED');
}

header('Content-Type: application/json; charset=utf-8');

$stmt = $pdo->query('SELECT name FROM institution WHERE name LIKE "%'.$_GET['term'].'%"');
$rows = array();
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
  $rows[] = array('label' => $row['name']);
}

echo json_encode($rows, JSON_PRETTY_PRINT);