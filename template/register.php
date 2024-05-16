<?php
require 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt->bind_param("sss", $username, $password, $email)) {
        if ($stmt->execute()) {
            $message = "Registrierung erfolgreich!";
        } else {
            $message = "Benutzername oder E-Mail bereits vergeben.";
        }
    } else {
        $message = "Ein Fehler ist aufgetreten.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren</title>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h2>Registrieren</h2>
    <p><?php echo $message; ?></p>
    <form method="post" action="register.php">
        Benutzername: <input type="text" name="username" required><br>
        Passwort: <input type="password" name="password" required><br>
        E-Mail: <input type="email" name="email" required><br>
        <br />
        <button type="submit">Registrieren</button>
        <br />
        <br />
        <a href="login.php">Login</a>
    </form>
</body>
</html>