<?php
session_start(); // Move to top

define('TITLE', 'My Booking');
define('PAGE', 'MyBooking');
include('includes/header.php'); 
include('../dbConnection.php');

// Ensure session is valid
if ($_SESSION['is_login']) {
    $mEmail = $_SESSION['mEmail'];
} else {
    echo "<script> location.href='memberLogin.php'; </script>";
    exit();
}
?>

<div class="col-sm-9 col-md-10 mt-5 text-center">
    <p class="bg-dark text-white p-2">My Booking</p>

    <?php
    // Fetch booking records for the logged-in user
    $sql = "SELECT * FROM submitbookingt_tb WHERE member_email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $mEmail);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered table-hover">
        <thead>
            <tr class="bg-secondary text-white">
                <th>Booking ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Booking Type</th>
                <th>Trainer</th>
                <th>Address</th>
                <th>Date</th>
                <th>Cancel</th>
            </tr>
        </thead>
        <tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                <td>' . $row["Booking_id"] . '</td>
                <td>' . $row["member_name"] . '</td>
                <td>' . $row["member_email"] . '</td>
                <td>' . $row["booking_type"] . '</td>
                <td>' . $row["trainer"] . '</td>
                <td>' . $row["member_add1"] . '</td>
                <td>' . $row["member_date"] . '</td>
                <td>
                    <form action="" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="' . $row["Booking_id"] . '">
                        <button type="submit" class="btn btn-danger" name="delete"><i class="far fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info">No Bookings Found.</div>';
    }

    // Handle deletion
    if (isset($_POST['delete'])) {
        $bookingId = $_POST['id'];
        $deleteStmt = $conn->prepare("DELETE FROM submitbookingt_tb WHERE Booking_id = ?");
        $deleteStmt->bind_param("i", $bookingId);

        if ($deleteStmt->execute()) {
            echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
        } else {
            echo '<div class="alert alert-danger">Unable to Delete Data</div>';
        }
    }

    $stmt->close();
    ?>
</div>

<?php include('includes/footer.php'); ?>
