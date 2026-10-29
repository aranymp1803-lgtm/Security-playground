<?php
/*
 * RECONSTRUCTED EDUCATIONAL EXAMPLE ONLY.
 * This is illustrative of the SQL injection flaw discussed in the lab.
 * It is not confirmed to be the exact original file from the VM.
 * Do not deploy this version.
 */
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

    // VULNERABLE: untrusted input is concatenated into SQL.
    $sql = "SELECT id, password FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $message = "Access granted!";
    } else {
        $message = "Access denied!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vulnerable Login Lab (Example)</title>
</head>
<body>
    <h1>Vulnerable Login Lab (Example)</h1>
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
