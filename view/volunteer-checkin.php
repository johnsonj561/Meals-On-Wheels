<?php require_once('../php/connect.php'); 
//get current username
if((isset($_SESSION['username']) && $_SESSION['username'] != -1)){
  $username = $_SESSION['username'];
  $userID = $_SESSION['userID'];

}
else{ //else username wasn't stored and user must log in again
  echo"<meta http-equiv='refresh' content='0; url=../index.php?error=session-ended'>"; 
}

//set default timeone
date_default_timezone_set('America/New_York');

$checkinButton = "";
$table = "";
$feedback = "";
$schedule = 0;

//if view-route button has been selected and checkInDate is valid
if(isset($_POST['selectDate']) && isset($_POST['checkinDate'])){
  //get today's date for comparison
  $today = date('m/d/Y');
  $unformattedCheckinDate = $_POST['checkinDate'];
  //create timestamp for checkin date - needed to get 'Day' of checkin date
  $checkinTimestamp = strtotime($_POST['checkinDate']);
  //format checkin date to match today's date
  $checkinDate = date('m/d/Y', $checkinTimestamp);
  //get day of week for checkin date
  $checkinDay = date('D', $checkinTimestamp);
  $checkinDay = getDayOfWeek($checkinDay);
  //verify that date selected is valid, can not check in for date that has passed!
  if($checkinDate >= $today){
    $feedback = "<h3 class='text-center'>Date selected: $checkinDay, $checkinDate</h3>";

    //display current users Schedule for given day of week
    //first get list of Clients scheduled for given user and day of week
    $query = "SELECT ClientID FROM Schedule WHERE
            Day = '$checkinDay' AND UserID = '$userID';";
    $scheduledclients = mysqli_query($link, $query);
    //while there are scheduled clients, get their first/last names
    while($scheduledclient = mysqli_fetch_row($scheduledclients)){
      //gather Client info to display to user
      $query = "SELECT FirstName, LastName, Phone, Address, City FROM Clients WHERE
              ClientID = '$scheduledclient[0]';";
      $result = mysqli_query($link, $query);
      $column_count = mysqli_field_count($link);
      //if query successful - build schedule table for given day
      if($result){
        while($row = mysqli_fetch_row($result)){
          $table .= "<tr>\n";
          for($column = 0; $column < $column_count; $column++){
            $table .=   "<td>$row[$column]</td>\n";
          }
          $table .= "</tr>\n";
          //$schedule contains total number of clients being seen on given day
          $schedule++;  
        }
      }
      //else - notify user of error
      else{
        $feedback = "<p class='error-mssg text-center'>Error Loading Your Schedule. Please <a href='#'>contact us</a> if problem continues</p>";
      }
      //if there is a user route, enable the "Check In" Button
      if($schedule > 0){
        $checkinButton = "<button type='submit' name='checkin' class='btn btn-primary btn-lg'>Click to Check In</button>";
      }
    }
  }
  //else - invalid date selected, notify user!
  else{
    $feedback = "<p class='error-mssg text-center'>You can not check in to a day that has passed</p>";
  }
}

//if Check In button is clicked - add entry to Checkin table
if(isset($_POST['checkin']) && isset($_POST['checkinDate'])){
  $unformattedCheckinDate = $_POST['checkinDate'];
  //create timestamp for checkin date - needed to get 'Day' of checkin date
  $checkinTimestamp = strtotime($_POST['checkinDate']);
  //get day of week for checkin date
  $checkinDay = date('D', $checkinTimestamp);
  $checkinDay = getDayOfWeek($checkinDay);

  //make sure volunteer has not already checkin in for this day!
  $query = "SELECT * FROM Checkins WHERE
            DATE = '$unformattedCheckinDate'
            AND UserID = '$userID';";
  $result = mysqli_query($link, $query);
  $rowCount = mysqli_num_rows($result);

  if($rowCount > 0){
    $feedback = "<p class='error-mssg text-center'>You've Already Checked In For This Date</p>";
  }
  else{
    $query = "INSERT INTO Checkins(Date, Day, UserID) VALUES
            ('$unformattedCheckinDate', '$checkinDay', '$userID');";
    $result = mysqli_query($link, $query);
    if($result){
      echo"<meta http-equiv='refresh' content='0; url=volunteer-landing.php?error=checkin-null'>"; 
    }
    else{
      $feedback = "<p class='error-mssg text-center'>An Error Occurred, Please Try Again</p>";
    }
  }
}

/*Converts day of week from DATE abbreviation to full string*/
function getDayOfWeek($day){
  switch($day){
    case "Sun":
    return "Sunday";
    break;
    case "Mon":
    return "Monday";
    break;
    case "Tue":
    return "Tuesday";
    break;
    case "Wed":
    return "Wednesday";
    break;
    case "Thu":
    return "Thursday";
    break;
    case "Fri":
    return "Friday";
    break;
    case "Sat":
    return "Saturday";
    break;
    default:
    return 0;
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Meals on Wheels Route Planning</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom-css.css" rel="stylesheet">
  </head>
  <body>
    <?php require_once('../template/navbar.html'); ?>
    <div class="container">
      <div class="row btn-row">
        <div class="col-med-12">
          <h1 class="text-center">Check In For Upcoming Delivery</h1>
          <h3 class="text-center">Please Check In Within 24 Hours of Your Scheduled Delivery Date</h3>
          <?php echo $feedback; ?>
          <div class="row">
            <p class = "intro-text text-center error-mssg" name = "errorElement" id = "errorElement"></p>
          </div>
          <form role="form" action="#" method = "post" onsubmit="return onCheckin()">
            <div class="col-md-2 text-center">
              <label class="pull-left">Select (<em>mm/dd/yyyy</em>)</label>
              <input type="date" class="form-control" name = "checkinDate" id = "checkinDate" 
                     <?php if(isset($_POST['selectDate'])){ echo "value='$unformattedCheckinDate'"; } ?>
                     required>
              <button type="submit" name="selectDate" class="btn btn-primary btn-lg">Select Date</button>
              <?php echo $checkinButton; ?>
            </div>
            <div class="col-md-10 text-center">
              <table class="recipient-table">
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Phone</th>
                  <th>Address</th>
                  <th>City</th>
                </tr>
                <?php echo $table; ?>
              </table>
            </div>
            </div>
          </form>
      </div>
    </div>
    <hr>
    </div>
  <?php require_once('../template/footer.html'); ?>
  <script src = "../js/validate-date.js"></script>
  </body>
</html>
