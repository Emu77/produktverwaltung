<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");

$pdo = new PDO("mysql:host=localhost;dbname=produktverwaltung;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("DELETE FROM produkte WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(["message" => "Produkt gelöscht"]);