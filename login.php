<?php
require_once '../config/db.php';

$message = "";

if(isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email=?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user &&
       password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if($user['role'] == 'admin') {

            header("Location: ../member4/admin.php");

        } else {

            header("Location: ../member1/index.php");

        }

        exit();

    } else {

        $message = "Invalid email or password.";

    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">

    <h2>Customer Login</h2>

    <p><?= htmlspecialchars($message) ?></p>

    <form method="POST">

        <input type="email"
               name="email"
               placeholder="Enter Email"
               required>

        <input type="password"
               name="password"
               placeholder="Enter Password"
               required>

        <button name="login">Login</button>

    </form>

    <a href="register.php">Create Account</a>

</div>

</body>
</html>
