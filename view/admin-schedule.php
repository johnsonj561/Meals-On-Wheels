<?php 
require_once('../php/connect.php'); 

//if not logged in as admin - kick out to home page
if(!isset($_SESSION['username']) || strtolower($_SESSION['username']) != "admin"){
  echo"<meta http-equiv='refresh' content='0; url=../php/logout.php'>"; 
}

//placeholder for dynamic tables
$table1 = "";
$table2 = "";
$feedback = "";
$selectInput = "<select name='volunteerID' class='form-control'>";

//if directed here from another page and $_GET parameters are set
//assign them to $_POST variables, this will trick page into thinking form was submitted
//and auto populate the table with the passed in $_GET variables
if(isset($_GET['volunteerID']) && isset($_GET['day'])){
  $_POST['dayOfWeek'] = $_GET['day'];
  $_POST['volunteerID'] = $_GET['volunteerID'];
}

//if volunteerID was previously selected, display them first
if(isset($_POST['volunteerID'])){
  $volunteerID = $_POST['volunteerID'];
  $query = "SELECT FirstName, LastName FROM UserInfo WHERE UserID = $volunteerID;";
  $result = mysqli_query($link, $query);
  $row = mysqli_fetch_row($result);
  $selectInput .= "<option value='$volunteerID'>$row[0] $row[1]</option>\n";
}

//populate Select input with all possible volunteers for admin to choose from
$query = "SELECT Users.UserID, UserInfo.FirstName, UserInfo.LastName
          FROM Users, UserInfo
          WHERE Users.UserID = UserInfo.UserID
          ORDER BY UserInfo.LastName";
$result = mysqli_query($link, $query);
if($result){
  while($row = mysqli_fetch_row($result)){
    if(strtolower($row[1]) != "admin"){     //do not display Admin User as an option
      $selectInput .= "<option value='$row[0]'>$row[1] $row[2]</option>\n";
    }
  }
  $selectInput .= "</select>";
}
else{
  $feedback = "<p class='error-mssg text-center'>Error Selecting User, Please Try Again</p>"; 
}

//if add button was clicked, update Schedule table with the selected clients
//availableClients[] is array of ClientIDs that is connected to the tables check boxes
//these ClientIDS will be used to update Schedule on given day
if(isset($_POST['add']) && isset($_POST['dayOfWeek']) && isset($_POST['volunteerID'])){
  if(isset($_POST['availableClients'])){      //check that user has selected clients to add
    $availableClients = $_POST['availableClients'];
    $dayOfWeek = $_POST['dayOfWeek'];
    $volunteerID = $_POST['volunteerID'];
    foreach($availableClients as $selectedClient){
      $query = "INSERT INTO Schedule (UserID, ClientID, Day)
       VALUES ('$volunteerID', '$selectedClient', '$dayOfWeek');";
      $result = mysqli_query($link, $query);
      if($result){
        $feedback = "<p class='error-mssg text-center'>Schedule Updated</p>";
      }
      else{
        $feedback = "<p class='error-mssg text-center'>Error Updating Schedule, Please Try Again</p>";
      }
    }
  }
  else{
    $feedback = "<p class='error-mssg text-center'>Please select clients to add to schedule</p>";
  }
}

//if remove button clicked, update Schedule table by removing selected clients
//scheduledClients[] is an array of ClientIDs that are currently on users schedule
//these ClientIDs will be used to update Schedule on given day
if(isset($_POST['remove']) && isset($_POST['dayOfWeek']) && isset($_POST['volunteerID']) ){
  if(isset($_POST['scheduledClients'])){              //make sure user has selected clients to remove
    $scheduledClients = $_POST['scheduledClients'];   //array of clientIDs
    $dayOfWeek = $_POST['dayOfWeek'];
    $volunteerID = $_POST['volunteerID'];
    foreach($scheduledClients as $client){            //for each clientID, remove from Schedule
      $query = "DELETE FROM Schedule WHERE
                ClientID = '$client' AND 
                UserID = '$volunteerID' AND
                Day = '$dayOfWeek';";
      $result = mysqli_query($link, $query);
      if($result){    //if query was good
        $feedback = "<p class='error-mssg text-center'>Schedule Updated</p>";
      }
      else{
        $feedback = "<p class='error-mssg text-center'>Error Updating Schedule, Please Try Again</p>";
      }
    }
  }
  else{
    $feedback = "<p class='error-mssg text-center'>Please select clients to remove from schedule</p>";
  }
}


//if day of week has been selected, display available clients for given day
//also display users schedule for the given day
//this needs to be done after updating Schedule with Add/Remove functions or we may have duplicates
if(isset($_POST['dayOfWeek'])){
  $dayOfWeek = $_POST['dayOfWeek'];
  //select Unique Client IDs from schedule where day column is set to null
  //these are the Clients that have not yet been scheduled
  $query = "SELECT ClientID FROM Clients WHERE
            ClientID NOT IN(
              SELECT ClientID FROM Schedule WHERE
              Day = '$dayOfWeek');";
  //get array of available clients for this day
  $availableclients = mysqli_query($link, $query);

  while($client = mysqli_fetch_row($availableclients)){
    //var_dump($client); debug
    //get 1 client's info
    $query = "SELECT ClientID, FirstName, LastName, Address, City, Zip FROM Clients WHERE
              ClientID = '$client[0]';";
    $result = mysqli_query($link, $query);
    //append that client's info to row and insert into table
    $row = mysqli_fetch_row($result);
    $table1 .=  "<tr>\n
                <td class='text-center'><input type='checkbox' name='availableClients[]' value='$row[0]'></td>\n";
    for($column = 1; $column < 6; $column++){
      $table1 .=   "<td>$row[$column]</td>\n";
    }  
  }

  //display the selected Volunteer's schedule
  if(isset($_POST['volunteerID'])){
    $volunteerID = $_POST['volunteerID'];
    $query = "SELECT FirstName from UserInfo WHERE UserID = '$volunteerID';";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_row($result);
    $volunteerName = $row[0];
    //debugging
    $query = "SELECT ClientID FROM Schedule WHERE
            Day = '$dayOfWeek' AND UserID = '$volunteerID';";
    $scheduledclients = mysqli_query($link, $query);
    //while there are scheduled clients, get their first/last names
    while($scheduledclient = mysqli_fetch_row($scheduledclients)){
      $query = "SELECT FirstName, LastName FROM Clients WHERE
              ClientID = '$scheduledclient[0]';";
      $result = mysqli_query($link, $query);
      if($result){
        $row = mysqli_fetch_row($result);
        $table2 .= "<tr>\n
                    <td class='text-center'><input type='checkbox' name='scheduledClients[]'
                        value='$scheduledclient[0]'></td>\n
                  <td>$row[0]</td>\n<td>$row[1]</td></tr>";
      }
      else{
        $feedback = "<p class='error-mssg text-center'>Error Loading Your Schedule. Please <a href='#'>contact us</a> if problem continues</p>";
      }
    }
  }
}

//options to display to user only if a day of week and volunteer were selected
//these buttons will allow admin to add or remove clients from given volunteer's schedule
$options = '<div class="edit-recipient-options">
              <div class="col-md-8 text-center"></div>
              <div class="col-md-2 text-center">
                <button type="submit" name="add" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-plus" aria-hidden="true">
                </span>Add</button>
              </div>
              <div class="col-md-2 text-center">
                <button type="submit" name="remove" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-trash" aria-hidden="true">
                </span>Remove</button>
              </div>
            </div>';
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
    <?php require_once('../template/navbar.html'); ?>
    <div class="container">
      <div class="col-lg-12">
        <?php echo $feedback; ?>
        <h1 class="text-center header-spacing"><?php if(isset($_POST['dayOfWeek']) && $dayOfWeek != ""){echo "$dayOfWeek Schedule";}
else{echo"Admin Scheduling";} ?>
        </h1>
        <!-- SELECT DAY OF WEEK TO POPULATE AVAILABLE RECIPIENTS AND CURRENT ROUTE -->
        <form role="form" action="#" method="post" >
          <div class="col-lg-12 row">
            <div class="col-lg-2 text-center"></div>
            <div class="col-lg-4 text-center">
              <h4 class="text-center">Select Day of Week</h4>
              <select name="dayOfWeek" class="form-control">
                <?php if(isset($dayOfWeek)){echo"<option value='$dayOfWeek'>$dayOfWeek</option>";} ?>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
              </select>
            </div>
            <div class="col-lg-4 text-center">
              <h4 class="text-center">Select Volunteer View</h4>
              <?php echo $selectInput; ?>
            </div>
            <div class="col-lg-2 text-center"></div>
            <div class="col-lg-12 text-center">
              <button type="submit" class="btn btn-primary btn-lg">View Schedule</a>
            </div>
          </div>
          <?php if(isset($_POST['dayOfWeek'])){ echo $options; } ?>
          <div class="col-lg-8">
            <h4 class="text-center">Available Clients</h4>
            <table class="recipient-table">
              <tr>
                <th>Add</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Zip</th>
              </tr>
              <?php echo $table1; ?>
            </table>
            <p><a href="#top">Back To Top</a></p>
          </div>
          <div class="col-lg-4">
            <h4 class="text-center"><?php if(isset($_POST['volunteerID'])){ echo $volunteerName . "'s"; } ?> Schedule</h4>
            <table class="recipient-table">
              <tr>
                <th>Remove</th>
                <th>First Name</th>
                <th>Last Name</th>
              </tr>
              <?php echo $table2; ?>
            </table>
          </div>
        </form>
      </div>
    </div>
    <hr>
    <?php require_once('../template/footer.html'); ?>
  </body>
</html>