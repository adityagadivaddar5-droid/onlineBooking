<?php
require_once '../config/db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../member2/login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if($id) {

        $stmt = $conn->prepare("
            UPDATE bookings
            SET status='Cancelled'
            WHERE id=? AND user_id=?
            AND status != 'Cancelled'
        ");

        $stmt->execute([
            $id,
            $_SESSION['user_id']
        ]);
    }
}

header("Location: my_bookings.php");
exit();
?>
