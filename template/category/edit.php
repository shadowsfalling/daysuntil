<?php
require '../db.php';
session_start();

$message = '';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$category_id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $color = $_POST['color'];

    if ($category_id) {
        // Update existing category
        $sql = "UPDATE categories SET name = ?, color = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $color, $category_id);
    } else {
        // Create new category
        $sql = "INSERT INTO categories (user_id, name, color) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $name, $color);
    }

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $message = "Ein Fehler ist aufgetreten.";
    }
    $stmt->close();
} else if ($category_id) {
    // Load existing category
    $sql = "SELECT name, color FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kategorie <?= $category_id ? 'bearbeiten' : 'hinzufügen' ?></title>
    <link rel="stylesheet" href="../styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1><?= $category_id ? 'Kategorie bearbeiten' : 'Neue Kategorie hinzufügen' ?></h1>
    <form method="post">
        Name: <input type="text" name="name" value="<?= $category['name'] ?? '' ?>" required><br>
        <br />
        Farbe: <input type="color" name="color" value="<?= $category['color'] ?? '' ?>" required><br>
        <br />
        <button type="submit"><?= $category_id ? 'Aktualisieren' : 'Erstellen' ?></button>
    </form>
    <?= $message ?>

    <br />
    <br />
    <a href="index.php">zurück</a>
</body>
</html>