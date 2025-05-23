<?php
define('TITLE', 'View Schedule');
define('PAGE', 'schedule');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

if (isset($_SESSION['is_adminlogin'])) {
  $aEmail = $_SESSION['aEmail'];
} else {
  echo "<script> location.href='login.php'; </script>";
  exit;
}
?>

<div class="col-sm-9 col-md-10 mt-5 text-center">
  <p class="bg-dark text-white p-2">Gym Schedule</p>

  <?php
  $sql = "SELECT * FROM tbl_events ORDER BY start DESC";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    echo '<table class="table table-bordered table-hover">
      <thead>
        <tr class="bg-secondary text-white">
          <th>ID</th>
          <th>Class</th>
          <th>Start: Date/Time</th>
          <th>End: Date/Time</th>
          <th>Update/Delete</th>
        </tr>
      </thead>
      <tbody>';

    while ($row = $result->fetch_assoc()) {
      echo '<tr>';
      echo '<td>' . $row["id"] . '</td>';
      echo '<td>' . $row["title"] . '</td>';
      echo '<td>' . $row["start"] . '</td>';
      echo '<td>' . $row["end"] . '</td>';
      echo '<td>
        <form action="updateevent.php" method="POST" class="d-inline">
          <input type="hidden" name="id" value="' . $row["id"] . '">
          <button type="submit" class="btn btn-info mr-2" name="view"><i class="fas fa-pen"></i></button>
        </form>
        <form action="" method="POST" class="d-inline">
          <input type="hidden" name="id" value="' . $row["id"] . '">
          <button type="submit" class="btn btn-danger" name="delete"><i class="far fa-trash-alt"></i></button>
        </form>
      </td>';
      echo '</tr>';
    }

    echo '</tbody></table>';
  } else {
    echo "<div class='alert alert-warning'>No schedules found.</div>";
  }

  // Handle delete
  if (isset($_REQUEST['delete'])) {
    $del_id = $_REQUEST['id'];
    $sql = "DELETE FROM tbl_events WHERE id = $del_id";
    if ($conn->query($sql) === TRUE) {
      echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
    } else {
      echo "<div class='alert alert-danger'>Unable to delete schedule.</div>";
    }
  }
  ?>

</div>

<?php include('includes/footer.php'); ?>
