<?php
include 'db.php';
include 'incl/countdown_check.php';
$id = $_GET['id'];

$sql = "SELECT title, datetime FROM countdowns WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$titel = $row['title'];
$datum_zeit = $row['datetime'];
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Countdown: <?php echo htmlspecialchars($titel); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            margin-top: 50px;
        }

        h1 {
            color: #4a90e2;
        }

        #countdown {
            font-size: 24px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin-top: 20px;
        }
    </style>
    <script>
        function updateCountdown() {
            const countToDate = new Date("<?php echo $datum_zeit; ?>").getTime();
            const now = new Date().getTime();
            const difference = countToDate - now;

            const days = Math.floor(difference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((difference % (1000 * 60)) / 1000);

            document.getElementById("countdown").innerHTML = days + " Tage <br />" + hours + " Stunden <br />" + minutes + " Minuten <br />" + seconds + " Sekunden ";
        }

        setInterval(updateCountdown, 1000);
    </script>
</head>

<body>
    <h1>Countdown bis zum Termin: <?php echo htmlspecialchars($titel); ?></h1>
    <div id="countdown"></div>

    <br />
    <br />

    <?php
    if (isset($_SESSION['user_id'])) {
        echo "<a href='index.php'>zurück</a>";
    }
    ?>

</body>

</html>