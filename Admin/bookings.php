<?php
define('TITLE', 'Bookings');
define('PAGE', 'bookings');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

if (!isset($_SESSION['is_adminlogin'])) {
    echo "<script> location.href='login.php'; </script>";
    exit;
}

$aEmail = $_SESSION['aEmail'];
?>

<div class="col-sm-9 col-md-10 mt-5 text-center">
    <p class="bg-dark text-white p-2">Member Booking List</p>

    <?php
    $sql = "SELECT * FROM submitbookingt_tb ORDER BY Booking_id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered table-hover">
            <thead>
                <tr class="bg-secondary text-white">
                    <th>Booking ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Booking Type</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>Date</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>';
        
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["Booking_id"] . '</td>';
            echo '<td>' . $row["member_name"] . '</td>';
            echo '<td>' . $row["member_email"] . '</td>';
            echo '<td>' . $row["booking_type"] . '</td>';
            echo '<td>' . $row["member_add1"] . '</td>';
            echo '<td>' . $row["member_mobile"] . '</td>';
            echo '<td>' . $row["member_date"] . '</td>';
            echo '<td>
                <form action="" method="POST" class="d-inline">
                    <input type="hidden" name="id" value="' . $row["Booking_id"] . '">
                    <button type="submit" class="btn btn-danger" name="delete"><i class="far fa-trash-alt"></i></button>
                </form>
            </td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info">No bookings found.</div>';
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM submitbookingt_tb WHERE Booking_id = $id";
        if ($conn->query($sql) === TRUE) {
            echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
        } else {
            echo '<div class="alert alert-danger">Unable to delete booking.</div>';
        }
    }
    ?>
</div>

<?php include('includes/footer.php'); ?>
