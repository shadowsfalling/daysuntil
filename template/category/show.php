<?php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$category_id = $_GET['id'];

$sql = "SELECT name, color FROM categories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$category_result = $stmt->get_result();

if ($category_row = $category_result->fetch_assoc()) {
    $name = $category_row['name'];
    $color = $category_row['color'];
} else {
    echo "Kategorie nicht gefunden.";
    exit();
}

$countdown_sql = "SELECT id, title, datetime FROM countdowns WHERE category_id = ? AND user_id = ?";
$countdown_stmt = $conn->prepare($countdown_sql);
$countdown_stmt->bind_param("ii", $category_id, $user_id);
$countdown_stmt->execute();
$countdown_result = $countdown_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Detailansicht Kategorie - <?= htmlspecialchars($name) ?></title>
    <link rel="stylesheet" href="../styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- our project just needs Font Awesome Solid + Brands -->
    <link href="../assets/external/fontawesome.min.css" rel="stylesheet" />
    <link href="../assets/external/brands.min.css" rel="stylesheet" />
    <link href="../assets/external/solid.min.css" rel="stylesheet" />

    <script>
        function startCountdown(datum_zeit, elementId) {
            var countdown = setInterval(function() {
                var now = new Date().getTime();
                var eventDate = new Date(datum_zeit).getTime();
                var distance = eventDate - now;

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById(elementId).innerHTML = days + " Tage " + hours + " Std. " +
                    minutes + " Min. " + seconds + " Sek. ";

                if (distance < 0) {
                    clearInterval(countdown);
                    document.getElementById(elementId).innerHTML = "Termin abgelaufen";
                }
            }, 1000);
        }
    </script>
</head>

<body>
    <h1><?= htmlspecialchars($name) ?></h1>
    <p>Farbe: <span style="color:<?= htmlspecialchars($color) ?>"><?= htmlspecialchars($color) ?></span></p>
    <div class="accordion">
        <?php if ($countdown_result->num_rows > 0) : ?>
            <?php while ($row = $countdown_result->fetch_assoc()) : ?>
                <input type='checkbox' id='accordion-<?= $row['id'] ?>' hidden>
                <label for='accordion-<?= $row['id'] ?>' class='accordion-header' style='background-color: <?= htmlspecialchars($color) ?>;'><?= $row['datetime'] ?> - <?= htmlspecialchars($row['title']) ?></label>
                <div class='accordion-content'>
                    <p id='countdown-<?= $row['id'] ?>'>Warte auf Start...</p>
                    <script>
                        startCountdown('<?= $row['datetime'] ?>', 'countdown-<?= $row['id'] ?>');
                    </script>

                    <div class="right">
                        <a href="../show.php?id=<?= $row['id'] ?>"><i class="fa-solid fa-eye"></i></a>
                        <a href="../edit.php?id=<?= $row['id'] ?>"><i class="fa-solid fa-wrench"></i></a>
                        <a href="../repeat.php?id=<?= $row['id'] ?>"><i class="fa-solid fa-rotate-right"></i></a>

                    </div>

                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Keine Countdowns gefunden.</p>
        <?php endif; ?>
    </div>
    <a href="index.php">Zurück zur Liste</a>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>