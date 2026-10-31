<?php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../member2/login.php");
    exit();
}

$stmt = $conn->query("SELECT * FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Booking System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav>
    <h2>Online Booking</h2>

    <a href="index.php">Home</a>
    <a href="../member3/my_bookings.php">My Bookings</a>
    <a href="../member2/logout.php">Logout</a>
</nav>

<div class="container">

    <h1>Welcome to Online Booking System</h1>

    <p>Book your favourite services online easily.</p>

    <h2>Available Services</h2>

    <div class="services">

        <?php foreach($services as $service): ?>

        <div class="card">

            <h3>
                <?= htmlspecialchars($service['service_name']) ?>
            </h3>

            <p>
                <?= htmlspecialchars($service['description']) ?>
            </p>

            <h3>
                ₹<?= htmlspecialchars($service['price']) ?>
            </h3>

            <a class="btn"
               href="../member3/book.php?id=<?= $service['id'] ?>">
                Book Now
            </a>

        </div>

        <?php endforeach; ?>

    </div>

</div>

</body>
</html>
