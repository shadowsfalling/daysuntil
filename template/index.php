<?php
include 'incl/auth.php';
include 'db.php';
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Termine mit Countdown</title>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- our project just needs Font Awesome Solid + Brands -->
    <link href="assets/external/fontawesome.min.css" rel="stylesheet" />
    <link href="assets/external/brands.min.css" rel="stylesheet" />
    <link href="assets/external/solid.min.css" rel="stylesheet" />

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

                if (days > 0) {
                    document.getElementById(elementId).innerHTML = days + " Tage ";
                } else {
                    document.getElementById(elementId).innerHTML = "";
                }

                document.getElementById(elementId).innerHTML += hours + " Std. " +
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

    <h2>Übersicht</h2>

    <a href="edit.php" class="add icon">
        <i class="fa-solid fa-plus"></i>
    </a>

    <div class="navi">
        <a href="category" class="icon"><i class="fa-solid fa-layer-group"></i></a>
        <a href="past.php" class="icon"><i class="fa-solid fa-clock-rotate-left"></i></i></a>
        <a href="calendar.php" class="icon"><i class="fa-solid fa-calendar-days"></i></a>
        <a href="logout.php" class="icon"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>

    <br />
    <div class="accordion">
        <?php
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT countdowns.id, countdowns.title, countdowns.datetime, categories.color
        FROM countdowns
        LEFT JOIN categories ON countdowns.category_id = categories.id
        WHERE countdowns.user_id = ? AND countdowns.datetime > NOW() 
        ORDER BY countdowns.datetime";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $title = $row['title'];
                $datetime = $row['datetime'];
                $color = $row['color'] ?? '#000';

                echo "<input type='checkbox' id='accordion-$id' hidden>";
                echo "<label for='accordion-$id' class='accordion-header' style='background-color: $color;'>$datetime - $title</label>";
                echo "<div class='accordion-content'>";
                echo "<p id='countdown-$id'>Warte auf Start...</p>";
                echo '<div class="right">';
                echo '<a href="show.php?id=' . $id . '"><i class="fa-solid fa-eye"></i></a> ';
                echo '<a href="edit.php?id=' . $id . '"><i class="fa-solid fa-wrench"></i></a> ';
                echo '<a href="repeat.php?id=' . $id . '"><i class="fa-solid fa-rotate-right"></i></a>';
                echo '</div>';
                echo "<script>startCountdown('$datetime', 'countdown-$id');</script>";
                echo "</div>";
            }
        } else {
            echo "Keine Termine gefunden.";
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>
    <br />

</body>

</html>