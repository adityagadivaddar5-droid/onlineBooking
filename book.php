<?php
require_once '../config/db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../member2/login.php");
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if(!$id) {
    die("Invalid service.");
}

$stmt = $conn->prepare(
    "SELECT * FROM services WHERE id=?"
);

$stmt->execute([$id]);

$service = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$service) {
    die("Service not found.");
}

$message = "";

if(isset($_POST['book'])) {

    $date = $_POST['date'];
    $time = $_POST['time'];

    if($date < date('Y-m-d')) {

        $message = "Select a valid date.";

    } else {

        try {

            $conn->beginTransaction();

            $lock = $conn->prepare(
                "SELECT id FROM services WHERE id=? FOR UPDATE"
            );
            $lock->execute([$id]);

            $check = $conn->prepare(
                "SELECT id FROM bookings
                 WHERE service_id=?
                 AND booking_date=?
                 AND booking_time=?
                 AND status != 'Cancelled'"
            );

            $check->execute([$id, $date, $time]);

            if($check->fetch()) {

                $conn->rollBack();
                $message = "Time slot already booked.";

            } else {

                $insert = $conn->prepare(
                    "INSERT INTO bookings
                    (user_id,service_id,booking_date,booking_time)
                    VALUES(?,?,?,?)"
                );

                $insert->execute([
                    $_SESSION['user_id'],
                    $id,
                    $date,
                    $time
                ]);

                $conn->commit();

                $message = "Booking successful!";

            }

        } catch(PDOException $e) {

            if($conn->inTransaction()) {
                $conn->rollBack();
            }

            $message = "Booking failed.";

        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Service</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">

    <h2>Book Your Service</h2>

    <h3><?= htmlspecialchars($service['service_name']) ?></h3>

    <p><?= htmlspecialchars($message) ?></p>

    <form method="POST">

        <label>Select Date</label>

        <input type="date"
               name="date"
               min="<?= date('Y-m-d') ?>"
               required>

        <label>Select Time</label>

        <input type="time"
               name="time"
               required>

        <button name="book">Confirm Booking</button>

    </form>

</div>

</body>
</html>
