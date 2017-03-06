<?php 
require_once('../php/connect.php'); 

//get current username
if((isset($_SESSION['username']) && $_SESSION['username'] != -1)){
  $username = $_SESSION['username'];
}
else{ //else username wasn't stored and user must log in again
  echo"<meta http-equiv='refresh' content='0; url=../index.php?error=session-ended'>"; 
}

//get current userid
if(isset($_SESSION['userID']) && $_SESSION['userID'] != -1){
  $userID = $_SESSION['userID'];
}
//else re-direct to home page to prompt logging in
else{
  echo"<meta http-equiv='refresh' content='0; url=../php/logout.php'>"; 
}

//get userinfo from Clients table only if on user's schedule
$query = "SELECT Clients.ClientID, Clients.FirstName, Clients.LastName, Clients.Address,
          Clients.City, Clients.Zip FROM Clients, Schedule
          WHERE Clients.ClientID = Schedule.ClientID
          AND Schedule.UserID = '$userID'
          GROUP BY Clients.ClientID";
$result = mysqli_query($link, $query);
$column_count = mysqli_field_count($link);
$table = "";


while($row = mysqli_fetch_row($result)){
  $table .=  "<tr>\n
                <td class='text-center'><p class='error-mssg'>
                  <a href='report-concern-form.php?clientID=$row[0]'>Report</a></p>
                
                </td>\n";
  $userID = $row[0];
  for($column = 1; $column < $column_count; $column++){
    $table .=   "<td>$row[$column]</td>\n";
  }

  $table .= "</tr>\n";
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
    <title>Volunteer Controls</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom-css.css" rel="stylesheet">
  </head>
  <body>
    <?php require_once('../template/navbar.html'); ?>
    <div class="container"><a name="top"></a>
      <div class="row btn-row">
        <div class="col-med-12">
          <h1 class="text-center"><?php echo $username;?>'s Current Recipients</h1>
          <form role="form" action="../php/delete-recipients.php" method = "post" >
            <div class="edit-recipient-options">
              <div class="col-md-3 text-center">
              </div>
              <div class="col-md-6">
                <?php
                  if(isset($_GET['message'])){
                    if($_GET['message'] == 'success'){
                      echo "<p class='error-mssg text-center'>Your concern has been submitted for review, Thank You!</p>";
                    }
                    else if($_GET['message'] == 'error'){
                      echo "<p class='error-mssg text-center'>Error Updating Database, Please Try Again</p>";
                    }
                  }
                ?>
              </div>
              <div class="col-md-3 text-center">
              </div>
            </div>
            <table class="recipient-table">
              <tr>
                <th>Select</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Zip</th>
              </tr>
              <?php echo $table; ?>
            </table>
            <a href="#top">Back To Top</a></p>
            </div>
        </div>
        <hr>
      </div>
      <?php require_once('../template/footer.html'); ?>
      </body>
    </html>
