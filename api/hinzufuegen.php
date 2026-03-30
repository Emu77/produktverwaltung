<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$pdo = new PDO("mysql:host=localhost;dbname=produktverwaltung;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$input = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("INSERT INTO produkte (name, preis, beschreibung) VALUES (?, ?, ?)");
$stmt->execute([$input['name'], $input['preis'], $input['beschreibung']]);

echo json_encode(["message" => "Produkt erstellt", "id" => $pdo->lastInsertId()]);