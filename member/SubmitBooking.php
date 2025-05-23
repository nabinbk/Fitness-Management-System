<?php 
define('TITLE', 'Submit Booking'); 
define('PAGE', 'SubmitBooking'); 
include('includes/header.php');  
include('../dbConnection.php'); 
session_start(); 

// Redirect if not logged in
if (!isset($_SESSION['is_login'])) {
    echo "<script> location.href='memberLogin.php'; </script>";
    exit();
}

$mEmail = $_SESSION['mEmail'];

// Booking form submission
if (isset($_POST['Submitbooking'])) {
    // Check required fields
    if (empty($_POST['membername']) || empty($_POST['memberemail']) || empty($_POST['membermobile']) || 
    empty($_POST['bookingtype']) || empty($_POST['trainer']) || 
    empty($_POST['bookingdate']) || empty($_POST['memberadd1'])) {
    $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> All Fields Are Required </div>';
}else {
        // Clean inputs
        $mname = $_POST['membername'];
        $memail = $_POST['memberemail'];
        $mmobile = $_POST['membermobile'];
        $btype = $_POST['bookingtype'];
        $trai = $_POST['trainer'];
        $madd1 = $_POST['memberadd1'];
        $bdate = $_POST['bookingdate'];

        // Use prepared statement
        $stmt = $conn->prepare("INSERT INTO submitbookingt_tb 
        (member_name, member_email, member_mobile, member_add1, booking_type, trainer, member_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $mname, $memail, $mmobile, $madd1, $btype, $trai, $bdate);
    
        if ($stmt->execute()) {
            $genid = $conn->insert_id;
            $_SESSION['myid'] = $genid;
            echo "<script>alert('Booking Successful!'); location.href='mybooking.php';</script>";
            exit();
        } else {
            $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to make booking: ' . $conn->error . '</div>';
        }

        $stmt->close();
    }
}
?>

<!-- Booking Form -->
<div class="col-sm-6 mt-5 mx-3 jumbotron">
    <h3 class="text-center"><b><u>Make Booking</u></b></h3>
    <div class="container col-sm-9 col-md-10 mt-5">
        <form class="mx-5" action="SubmitBooking.php" method="POST">
            <?php if (isset($msg)) { echo $msg; } ?>

            <div class="form-group">
                <label for="inputName">Full Name</label>
                <input type="text" class="form-control" id="inputName" placeholder="Enter name" name="membername" required>
            </div>

            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="email" class="form-control" id="inputEmail" placeholder="Enter email" name="memberemail" required>
            </div>

            <div class="form-group">
    <label for="inputMobile">Mobile</label>
    <input type="text" class="form-control" id="inputMobile" name="membermobile"
        placeholder="Enter mobile number" required maxlength="10" pattern="^(98|97)\d{8}$"
        title="Enter a valid 10-digit Nepali mobile number starting with 98 or 97"
        onkeypress="return isInputNumber(event)">
</div>



            <div class="form-group">
                <label for="inputbookingtype">Booking Type</label>
                <select class="form-control" id="inputbookingtype" name="bookingtype" required>
                    <option value="">Select</option>
                    <option>Yoga class</option>
                    <option>Zumba class</option>
                    <option>Cardio class</option>
                    <option>Weight lifting</option>
                    <option>Endurance Training</option>
                </select>
            </div>
            <div class="form-group">
    <label for="inputTrainer">Select Trainer</label>
    <select class="form-control" id="inputTrainer" name="trainer" required>
        <option value="">Select</option>
        <option>Aashish Thapa</option>
        <option>Bikash Thapa</option>
        <option>Anupama</option>
        <option>Santoshi</option>
    </select>
</div>


            <div class="form-group">
                <label for="inputAddress">Address</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="Add address" name="memberadd1" required>
            </div>

            <div class="form-group">
                <label for="inputDate">Date</label>
                <input type="date" class="form-control" id="inputDate" name="bookingdate" required>
            </div>

            <button type="submit" class="btn btn-primary btn ml-5" name="Submitbooking">Submit</button>
        </form>
    </div>
</div>

<!-- Input restriction and date min -->
<script>
function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
        evt.preventDefault();
        return false;
    }
}

    // Set min date to today
    window.onload = function () {
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
