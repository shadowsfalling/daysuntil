<?php
require 'db.php';
session_start();

$countdown_id = $_GET['id'];

$sql = "SELECT user_id, is_public FROM countdowns WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $countdown_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $countdown = $result->fetch_assoc();
    if ($countdown['is_public'] == 1) {
    } else {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $countdown['user_id']) {
            header("Location: login.php");
            exit();
        }
    }
} else {

    header("Location: login.php");
    exit();
}

$stmt->close();
