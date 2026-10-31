<?php
require_once '../config/db.php';

if(!isset($_SESSION['user_id']) ||
   $_SESSION['role'] !== 'admin') {

    die("Access Denied!");
}

if(isset($_POST['update'])) {

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    $status = $_POST['status'] ?? '';

    if($id && in_array($status,
       ['Pending','Confirmed','Cancelled'], true)) {

        $stmt = $conn->prepare(
            "UPDATE bookings SET status=? WHERE id=?"
        );

        $stmt->execute([$status, $id]);

    }
}

$stmt = $conn->query("
    SELECT bookings.*,
           users.name,
           users.email,
           services.service_name
    FROM bookings
    JOIN users
    ON bookings.user_id = users.id
    JOIN services
    ON bookings.service_id = services.id
    ORDER BY bookings.created_at DESC
");

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">

    <h1>Admin Dashboard</h1>

    <h2>All Customer Bookings</h2>

    <table>

        <tr>
            <th>Customer</th>
            <th>Email</th>
            <th>Service</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Update</th>
        </tr>

        <?php foreach($bookings as $b): ?>

        <tr>

            <td><?= htmlspecialchars($b['name']) ?></td>

            <td><?= htmlspecialchars($b['email']) ?></td>

            <td><?= htmlspecialchars($b['service_name']) ?></td>

            <td><?= htmlspecialchars($b['booking_date']) ?></td>

            <td><?= htmlspecialchars($b['booking_time']) ?></td>

            <td><?= htmlspecialchars($b['status']) ?></td>

            <td>

                <form method="POST">

                    <input type="hidden"
                           name="id"
                           value="<?= $b['id'] ?>">

                    <select name="status">

                        <option value="Pending">Pending</option>

                        <option value="Confirmed">Confirmed</option>

                        <option value="Cancelled">Cancelled</option>

                    </select>

                    <button name="update">Update</button>

                </form>

            </td>

        </tr>

        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
