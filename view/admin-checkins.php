<?php require_once('../php/connect.php'); 

//if not logged in as administrator, redirect to home page
if((isset($_SESSION['username']) && strtolower($_SESSION['username']) != "admin")){
  echo"<meta http-equiv='refresh' content='0; url=../index.php?error=session-ended'>"; 
}

//placeholder variable for table
$table = "";

//get today's date and day
date_default_timezone_set("America/New_York");
$todaysDate = date("Y-m-d");
$todaysDay = date("D");
$todaysDay = getDayOfWeek($todaysDay);


//populate table with Volunteers not yet checked in
//get all Unique UserIDs from schedule where Day = Checkin Day and
//not exists UserID in Checkins
$query = "SELECT U.FirstName, U.LastName, U.Phone, U.UserID FROM UserInfo U, Schedule S
          WHERE U.UserID = S.UserID AND S.Day = '$todaysDay' AND U.UserID NOT IN(
            SELECT C.UserID FROM Checkins C WHERE C.Date = '$todaysDate')
          GROUP BY U.UserID;";
$result = mysqli_query($link, $query);

//if query was good, build table
if($result){
  $column_count = mysqli_field_count($link);
  while($row = mysqli_fetch_row($result)){
    $table .= "<tr>\n<td>$row[0]</td>\n<td>$row[1]</td>\n<td>$row[2]</td>\n
                <td><a href='admin-schedule.php?volunteerID=$row[3]&day=$todaysDay'>View Route</a></td></tr>";
  }
}
//else, alert user and return to admin landing
else{
    echo"<meta http-equiv='refresh' content='0; url=admin-landing.php?message=error'>"; 
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
  <head><a name="top"></a>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Volunteer Controls</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom-css.css" rel="stylesheet">
  </head>
  <body>
  	<h1 class="text-center header-spacing">Volunteers Not Yet Checked In</h1>
    <h2 class="text-center"><?php echo $todaysDay . ", " . $todaysDate; ?></h2>
    <?php require_once('../template/navbar.html'); ?>
    <div class="container">
    	<div class="col-lg-12">
          <?php
            if(isset($_GET['message'])){
              if($_GET['message'] == 'delete-success'){
                echo "<p class='error-mssg text-center'>Concern Succesfully Deleted</p>";
              }
              if($_GET['message'] == 'delete-error'){
                echo "<p class='error-mssg text-center'>There was an error deleting concern, Please try again</p>";
              }
            }
          ?>
		    <table class="recipient-table col-lg-12">
			    <th>First Name</th>
			    <th>Last Name</th>
                <th>Telephone</th>
                <th>View Schedule</th>
				<?php 
				  echo $table;
				?>
			</table>
		</div>
  	</div>
  	 <?php require_once('../template/footer.html'); ?>
  </body>
	  
</html>