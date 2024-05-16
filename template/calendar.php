<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$year = date('Y');
$month = date('m');

if(isset($_GET['month'])) {
    $month = $_GET['month'];
}

$start_date = "$year-$month-01";
$end_date = date("Y-m-t", strtotime($start_date));

$sql = "SELECT id, title, datetime FROM countdowns WHERE user_id = ? AND datetime BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $start_date, $end_date);
$stmt->execute();
$results = $stmt->get_result();

$events = [];
while ($row = $results->fetch_assoc()) {
    $day = date('j', strtotime($row['datetime']));
    $events[$day][] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kalenderansicht</title>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Kalender für <?= date("F Y", strtotime($start_date)) ?></h1>
    <table>
        <tr>
            <th>Mo</th><th>Di</th><th>Mi</th><th>Do</th><th>Fr</th><th>Sa</th><th>So</th>
        </tr>
        <?php
        $date = strtotime($start_date);
        while (date('m', $date) == $month) {
            echo '<tr>';
            for ($i = 0; $i < 7; $i++) {
                echo '<td>';
                if (date('m', $date) == $month) {
                    $day = date('j', $date);
                    echo $day;
                    if (isset($events[$day])) {
                        foreach ($events[$day] as $event) {
                            echo "<div><a href='show.php?id={$event['id']}'>" . htmlspecialchars($event['title']) . "</a></div>";
                        }
                    }
                }
                echo '</td>';
                $date = strtotime('+1 day', $date);
            }
            echo '</tr>';
            if (date('m', $date) != $month) break;
        }
        ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>