<?php
require_once '../config/db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../member2/login.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT bookings.*, services.service_name
    FROM bookings
    JOIN services
    ON bookings.service_id = services.id
    WHERE bookings.user_id = ?
    ORDER BY bookings.booking_date DESC
");

$stmt->execute([$_SESSION['user_id']]);

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">

    <h2>My Bookings</h2>

    <table>

        <tr>
            <th>Service</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php foreach($bookings as $b): ?>

        <tr>

            <td><?= htmlspecialchars($b['service_name']) ?></td>

            <td><?= htmlspecialchars($b['booking_date']) ?></td>

            <td><?= htmlspecialchars($b['booking_time']) ?></td>

            <td><?= htmlspecialchars($b['status']) ?></td>

            <td>

                <?php if($b['status'] != 'Cancelled'): ?>

                <form action="cancel.php" method="POST">

                    <input type="hidden"
                           name="id"
                           value="<?= $b['id'] ?>">

                    <button>Cancel</button>

                </form>

                <?php endif; ?>

            </td>

        </tr>

        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
