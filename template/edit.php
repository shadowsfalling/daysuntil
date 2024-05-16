<?php
include 'db.php';
include 'incl/auth.php';

$message = '';
$isUpdating = isset($_GET['id']);
$termin_id = $isUpdating ? $_GET['id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titel = $_POST['titel'];
    $datum_zeit = date('Y-m-d H:i:s', strtotime($_POST['datum_zeit']));
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? $_POST['category_id'] : null;

    if ($isUpdating) {
        $sql = "UPDATE countdowns SET title = ?, datetime = ?, category_id = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $titel, $datum_zeit, $category_id, $termin_id, $_SESSION['user_id']);
    } else {
        $sql = "INSERT INTO countdowns (title, datetime, category_id, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $titel, $datum_zeit, $category_id, $_SESSION['user_id']);
    }

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $message = $isUpdating ? "Termin erfolgreich aktualisiert!" : "Termin erfolgreich hinzugefügt!";

        header('location: index.php');

    } else {
        $message = "Fehler beim " . ($isUpdating ? "Aktualisieren" : "Hinzufügen") . " des Termins.";
    }
    $stmt->close();
} elseif ($isUpdating) {
    $sql = "SELECT title, datetime, category_id FROM countdowns WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $termin_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentData = $result->fetch_assoc();
    $stmt->close();
}

$sql = "SELECT id, name FROM categories WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$categoryResult = $stmt->get_result();
$conn->close();
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title><?= $isUpdating ? 'Termin aktualisieren' : 'Termin hinzufügen' ?></title>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h2><?= $isUpdating ? 'Termin aktualisieren' : 'Neuen Termin hinzufügen' ?></h2>
    <form method="post">
        Titel: <input type="text" name="titel" value="<?= htmlspecialchars($currentData['title'] ?? '') ?>" required><br>
        <br />
        Datum und Zeit: <input type="datetime-local" name="datum_zeit" value="<?= date('Y-m-d\TH:i', strtotime($currentData['datetime'] ?? 'now')) ?>" required><br>
        <br />
        Kategorie: <select name="category_id">
            <option value=""></option>
            <?php while ($category = $categoryResult->fetch_assoc()): ?>
                <option value="<?= $category['id'] ?>" <?= isset($currentData['category_id']) && $currentData['category_id'] == $category['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br>
        <input type="submit" value="<?= $isUpdating ? 'Aktualisieren' : 'Termin hinzufügen' ?>">
        <br /><br />
        <a href="index.php">zurück</a>
    </form>
    <p><?= $message ?></p>
</body>

</html>