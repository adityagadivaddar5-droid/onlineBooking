<?php
require_once '../config/db.php';

$message = "";

if(isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if($name === "" ||
       !filter_var($email, FILTER_VALIDATE_EMAIL) ||
       strlen($password) < 6) {

        $message = "Please enter valid details.";

    } else {

        $hashed = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        try {

            $stmt = $conn->prepare(
                "INSERT INTO users(name,email,password)
                 VALUES(?,?,?)"
            );

            $stmt->execute([
                $name,
                $email,
                $hashed
            ]);

            $message = "Registration successful!";

        } catch(PDOException $e) {

            $message = "Email already registered.";

        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">

    <h2>Customer Registration</h2>

    <p><?= htmlspecialchars($message) ?></p>

    <form method="POST">

        <input type="text"
               name="name"
               placeholder="Enter Name"
               required>

        <input type="email"
               name="email"
               placeholder="Enter Email"
               required>

        <input type="password"
               name="password"
               placeholder="Enter Password"
               required>

        <button name="register">
            Register
        </button>

    </form>

    <a href="login.php">Already have an account? Login</a>

</div>

</body>
</html>
