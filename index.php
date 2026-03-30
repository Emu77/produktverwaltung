<?php
$pdo = new PDO("mysql:host=localhost;dbname=produktverwaltung;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Speichern (neu oder bearbeiten)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $preis = $_POST['preis'];
    $beschreibung = $_POST['beschreibung'];

    if ($id) {
        $stmt = $pdo->prepare("UPDATE produkte SET name=?, preis=?, beschreibung=? WHERE id=?");
        $stmt->execute([$name, $preis, $beschreibung, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO produkte (name, preis, beschreibung) VALUES (?, ?, ?)");
        $stmt->execute([$name, $preis, $beschreibung]);
    }
    header("Location: index.php");
    exit;
}

// Löschen
if (isset($_GET['loeschen'])) {
    $stmt = $pdo->prepare("DELETE FROM produkte WHERE id=?");
    $stmt->execute([$_GET['loeschen']]);
    header("Location: index.php");
    exit;
}

// Bearbeiten vorbereiten
$bearbeiten = null;
if (isset($_GET['bearbeiten'])) {
    $stmt = $pdo->prepare("SELECT * FROM produkte WHERE id=?");
    $stmt->execute([$_GET['bearbeiten']]);
    $bearbeiten = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Alle Produkte laden
$produkte = $pdo->query("SELECT * FROM produkte")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Produktverwaltung</title>
<style>
    #tabelle td:nth-child(2), #tabelle th:nth-child(2) { min-width: 150px; }
    #tabelle td:nth-child(3), #tabelle th:nth-child(3) { min-width: 90px; text-align: right; }
    #tabelle td:nth-child(4), #tabelle th:nth-child(4) { min-width: 250px; }
    body {
    max-width: 600px;
    margin: 0 auto;
    }
    input[type="text"], input[type="number"] {
    width: 120px;
    }    
    </style>
</head>
<body>
  <h1>Produktverwaltung</h1>

  <table id="tabelle" border="1">
    <thead>
      <tr><th>ID</th><th>Name</th><th>Preis</th><th>Beschreibung</th><th>Aktionen</th></tr>
    </thead>
    <tbody>
      <?php foreach ($produkte as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= number_format($p['preis'], 2, ',', '.') ?> €</td>
        <td><?= htmlspecialchars($p['beschreibung']) ?></td>
        <td>
          <a href="?bearbeiten=<?= $p['id'] ?>">✏️</a>
          <a href="?loeschen=<?= $p['id'] ?>" onclick="return confirm('Wirklich löschen?')">🗑️</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h2><?= $bearbeiten ? 'Produkt bearbeiten' : 'Neues Produkt anlegen' ?></h2>
  <form method="post">
    <input type="hidden" name="id" value="<?= $bearbeiten['id'] ?? '' ?>">
    <input type="text"   name="name"          placeholder="Name"          value="<?= htmlspecialchars($bearbeiten['name'] ?? '') ?>">
    <input type="number" name="preis"         placeholder="Preis" step="0.01" value="<?= $bearbeiten['preis'] ?? '' ?>">
    <input type="text"   name="beschreibung"  placeholder="Beschreibung"  value="<?= htmlspecialchars($bearbeiten['beschreibung'] ?? '') ?>">
    <button type="submit">Speichern</button>
    <a href="index.php"><button type="button">Abbrechen</button></a>
  </form>

</body>
</html>