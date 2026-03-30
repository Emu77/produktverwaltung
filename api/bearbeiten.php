<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

$pdo = new PDO("mysql:host=localhost;dbname=produktverwaltung;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? null;
$input = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("UPDATE produkte SET name=?, preis=?, beschreibung=? WHERE id=?");
$stmt->execute([$input['name'], $input['preis'], $input['beschreibung'], $id]);

echo json_encode(["message" => "Produkt aktualisiert"]);