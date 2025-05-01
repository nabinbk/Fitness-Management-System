<?php
define('TITLE', 'Submit Booking');
define('PAGE', 'SubmitBooking');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();
if($_SESSION['is_login']){
  $mEmail = $_SESSION['mEmail'];
} else {
  echo "<script> location.href='memberLogin.php'; </script>";
}
if(isset($_REQUEST['Submitbooking'])){
  if(($_REQUEST['membername'] == "") || ($_REQUEST['memberemail'] == "") || ($_REQUEST['membermobile'] == "") || ($_REQUEST['bookingtype'] == "") || ($_REQUEST['bookingdate'] == "")){
    $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> All Fields Are Required </div>';
  } else {
    $mname = $_REQUEST['membername'];
    $memail = $_REQUEST['memberemail'];
    $btype = $_REQUEST['bookingtype']; 
    $madd1 = $_REQUEST['memberadd1'];
    $bdate = $_REQUEST['bookingdate'];
    
    $sql = "INSERT INTO submitbookingt_tb(member_name, member_email, member_add1, booking_type, member_date) 
            VALUES ('$mname', '$memail', '$mmobile', '$madd1', '$btype', '$bdate')";
    
    if ($conn->query($sql) === TRUE) {
      $genid = mysqli_insert_id($conn);
      $msg = '<div class="alert alert-success" role="alert"> Booking made Successfully </div>';
      $_SESSION['myid'] = $genid;
  } else {
      $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to make booking: ' . $conn->error . '</div>';
  }
  
  }
}
?>

<div class="col-sm-6 mt-5 mx-3 jumbotron">
  <h3 class="text-center"><b><u>Make Booking</u></b></h3>
  <div class="container col-sm-9 col-md-10 mt-5">
    <form class="mx-5" action="SubmitBooking.php" method="POST">
      <?php if(isset($msg)) { echo $msg; } ?>

      <div class="form-group">
        <label for="inputName" >Full Name</label>
        <input type="text" class="form-control" id="inputName" placeholder="Enter name" name="membername" required>
      </div>

      <div class="form-group">
        <label for="inputEmail">Email</label>
        <input type="email" class="form-control" id="inputEmail" placeholder="Enter email" name="memberemail" required> 
      </div>

      <div class="form-group">
        <label for="inputbookingtype">Booking Type</label>
        <select class="form-control" id="inputbookingtype" name="bookingtype" required>
          <option>Select list</option>
          <option>Yoga class</option>
          <option>Zumba class</option>
          <option>Cardio class</option>
          <option>Weight lifting</option>
          <option>Endurance Training</option>
        </select>
      </div>
      <div class="form-group">
        <label for="inputAddress">Address</label>
        <input type="text" class="form-control" id="inputAddress" placeholder="Add address" name="memberadd1" required>
      </div>

      <div class="form-group">
        <label for="inputDate">Date</label>
        <input type="date" class="form-control" id="inputDate" placeholder="Select" name="bookingdate" required>
      </div>

      <button type="submit" class="btn btn-primary btn ml-5" name="Submitbooking">Submit</button>
    </form>
  </div>
</div>


<script>
  
  function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
      evt.preventDefault();
    }
  }
  window.onload = function() {
    var today = new Date();
    var day = ("0" + today.getDate()).slice(-2);
    var month = ("0" + (today.getMonth() + 1)).slice(-2);
    var year = today.getFullYear();
    var todayDate = year + "-" + month + "-" + day;
    document.getElementById("inputDate").setAttribute("min", todayDate);
  };
</script>

<?php
include('includes/footer.php'); 
$conn->close();
?>
