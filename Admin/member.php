<?php
define('TITLE', 'Members');
define('PAGE', 'members');
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
  <!--Table-->
  <p class="bg-dark text-white p-2">Gym Members</p>

  <?php
  $sql = "SELECT * FROM memberlogin_tb ORDER BY m_login_id DESC";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo '<table class="table table-bordered table-hover">
      <thead>
        <tr class="active bg-secondary">
          <th scope="col">Gym Member ID</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Edit/Delete</th>
        </tr>
      </thead>
      <tbody>';

    while ($row = $result->fetch_assoc()) {
      echo '<tr>';
      echo '<th scope="row">' . $row["m_login_id"] . '</th>';
      echo '<td>' . $row["m_name"] . '</td>';
      echo '<td>' . $row["m_email"] . '</td>';
      echo '<td>
        <form action="editmeb.php" method="POST" class="d-inline">
          <input type="hidden" name="id" value="' . $row["m_login_id"] . '">
          <button type="submit" class="btn btn-success mr-3" name="view" value="View">
            <i class="fas fa-pen"></i>
          </button>
        </form>
        <form action="" method="POST" class="d-inline">
          <input type="hidden" name="id" value="' . $row["m_login_id"] . '">
          <button type="submit" class="btn btn-secondary" name="delete" value="Delete">
            <i class="far fa-trash-alt"></i>
          </button>
        </form>
      </td>';
      echo '</tr>';
    }

    echo '</tbody></table>';
  } else {
    echo '<div class="alert alert-info">No members found.</div>';
  }

  // Delete handler
  if (isset($_REQUEST['delete'])) {
    $id = $_REQUEST['id'];
    $sql = "DELETE FROM memberlogin_tb WHERE m_login_id = $id";
    if ($conn->query($sql) === TRUE) {
      echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
    } else {
      echo "<div class='alert alert-danger'>Unable to delete data.</div>";
    }
  }
  ?>
</div>

<!-- Add Member Button -->
<div>
  <a class="btn btn-success box" href="insertmeb.php">
    <i class="fas fa-plus fa-2x"></i>
  </a>
</div>

<?php include('includes/footer.php'); ?>
