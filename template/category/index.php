<?php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, name, color FROM categories WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorien Übersicht</title>
    <link rel="stylesheet" href="../styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- our project just needs Font Awesome Solid + Brands -->
    <link href="../assets/external/fontawesome.min.css" rel="stylesheet" />
    <link href="../assets/external/brands.min.css" rel="stylesheet" />
    <link href="../assets/external/solid.min.css" rel="stylesheet" />
</head>

<body>
    <h2>Kategorien</h2>
    <a href="edit.php" class="add icon">
        <i class="fa-solid fa-plus"></i>
    </a>

    <div class="accordion white-font">
        <?php while ($row = $result->fetch_assoc()) : ?>
            <label for='accordion-$id' class='accordion-header' style='background-color: <?= htmlspecialchars($row['color']) ?>'>
                <a href="show.php?id=<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['name']) ?></a>

                
                <a class="icon right" href="edit.php?id=<?= htmlspecialchars($row['id']) ?>"><i class="fa-solid fa-wrench"></i></a>
            </label>
        <?php endwhile; ?>
    </div>

    <br />

    <a href="../index.php">Zurück zu den Countdowns</a>
</body>

</html>

<?php
$stmt->close();
$conn->close();
