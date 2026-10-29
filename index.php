<?php
$message = "";

$conn = new mysqli(
    "10.61.0.224",
    "webapp",
    "REPLACE_WITH_DB_PASSWORD",
    "login_app"
);

if ($conn->connect_error) {
    http_response_code(500);
    die("Application temporarily unavailable.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    // Prepared statement: user input cannot change the SQL structure.
    $stmt = $conn->prepare(
        "SELECT id, password FROM users WHERE username = ?"
    );

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $storedPassword);
            $stmt->fetch();

            // Supports hashed passwords and the existing lab account.
            // The plaintext fallback is temporary for migration/testing.
            $passwordMatches = password_verify($password, $storedPassword)
                || hash_equals($storedPassword, $password);

            if ($passwordMatches) {
                $message = "Access granted!";
            } else {
                $message = "Access denied!";
            }
        } else {
            $message = "Access denied!";
        }

        $stmt->close();
    } else {
        http_response_code(500);
        $message = "Application temporarily unavailable.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Web Application Lab</title>
</head>
<body>
    <h1>Secure Web Application Lab</h1>
    <h2>Login</h2>

    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">Login</button>
    </form>

    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
</body>
</html>

<?php
$conn->close();
?>
