<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $countdown_id = $_POST['countdown_id'];
    $repeat_unit = $_POST['repeat_unit'];

    $sql = "SELECT datetime, title, is_public, category_id, user_id FROM countdowns WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $countdown_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $original = $result->fetch_assoc();

    $new_datetime = new DateTime($original['datetime']);
    switch ($repeat_unit) {
        case 'day':
            $new_datetime->modify('+1 day');
            break;
        case 'week':
            $new_datetime->modify('+1 week');
            break;
        case 'month':
            $new_datetime->modify('+1 month');
            break;
        case 'year':
            $new_datetime->modify('+1 year');
            break;
    }

    $sql = "INSERT INTO countdowns (title, datetime, is_public, category_id, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $new_date_str = $new_datetime->format('Y-m-d H:i:s');
    $stmt->bind_param("ssiii", $original['title'], $new_date_str, $original['is_public'], $original['category_id'], $original['user_id']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Countdown erfolgreich kopiert!";
    } else {
        echo "Fehler beim Kopieren des Countdowns.";
    }
    $stmt->close();
}

$sql = "SELECT id, title FROM countdowns WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$categoryResult = $stmt->get_result();
$conn->close();
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Countdown kopieren</title>

    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- our project just needs Font Awesome Solid + Brands -->
    <link href="assets/external/fontawesome.min.css" rel="stylesheet" />
    <link href="assets/external/brands.min.css" rel="stylesheet" />
    <link href="assets/external/solid.min.css" rel="stylesheet" />

</head>

<body>
    <h1>Countdown kopieren</h1>
    <form method="post">
        <label for="countdown_id">Wähle einen Countdown:</label>
        <select name="countdown_id" id="countdown_id" required>
            <?php while ($row = $categoryResult->fetch_assoc()) : ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></option>
            <?php endwhile; ?>
        </select><br>
        <br />
        <label for="repeat_unit">Wiederholen nach:</label>
        <select name="repeat_unit" id="repeat_unit" required>
            <option value="day">Tag</option>
            <option value="week">Woche</option>
            <option value="month">Monat</option>
            <option value="year">Jahr</option>
        </select><br>
        <br />
        <input type="submit" value="Countdown kopieren">
    </form>
    <br />

    <a href="index.php">zurück</a>

</body>

</html>