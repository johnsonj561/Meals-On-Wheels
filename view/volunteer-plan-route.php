<?php 
require_once('../php/connect.php'); 
require_once('../php/route-calculator.php'); 

//get current userid from SESSION
if(isset($_SESSION['userID'])){
  $userID = $_SESSION['userID'];
}
//else re-direct to home page to prompt logging in
else{
  echo"<meta http-equiv='refresh' content='0; url=../php/logout.php'>"; 
}

$table1 = "";
$table2 = "";
$feedback = "";
$options2 = "";
$options = "";
//establish array of clientIDs, to be passed to getCoordinates() at a later time
$userRoute = array();


//if add button was clicked, update Schedule table with the selected clients
//availableClients[] is array of ClientIDs that is connected to the tables check boxes
//these ClientIDS will be used to update Schedule on given day
if(isset($_POST['add']) && isset($_POST['dayOfWeek'])){
  if(isset($_POST['availableClients'])){
    $availableClients = $_POST['availableClients'];
    $dayOfWeek = $_POST['dayOfWeek'];
    foreach($availableClients as $selectedClient){
      $query = "INSERT INTO Schedule (UserID, ClientID, Day)
       VALUES ('$userID', '$selectedClient', '$dayOfWeek');";
      $result = mysqli_query($link, $query);
      if($result){
        $feedback = "<p class='error-mssg text-center'>Schedule Updated</p>";
      }
      else{
        $feedback = "<p class='error-mssg text-center'>Error Updating Schedule, Please Try Again</p>";
      }
    }
  }
}

//if remove button clicked, update Schedule table by removing selected clients
//scheduledClients[] is an array of ClientIDs that are currently on users schedule
//these ClientIDs will be used to update Schedule on given day
if(isset($_POST['remove']) && isset($_POST['dayOfWeek'])){
  if(isset($_POST['scheduledClients'])){
    $scheduledClients = $_POST['scheduledClients'];   //array of clientIDs
    $dayOfWeek = $_POST['dayOfWeek'];
    foreach($scheduledClients as $client){            //for each clientID, remove from Schedule
      $query = "DELETE FROM Schedule WHERE
                ClientID = '$client' AND 
                UserID = '$userID' AND
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

  //display current users Schedule for given day of week
  //first get list of Clients scheduled for given user and day of week
  $query = "SELECT ClientID FROM Schedule WHERE
            Day = '$dayOfWeek' AND UserID = '$userID';";
  $scheduledclients = mysqli_query($link, $query);
  //while there are scheduled clients, get their first/last names
  while($scheduledclient = mysqli_fetch_row($scheduledclients)){
    //add clientID to $userRoute array, to be passed to coordinateRoute() at later time
    $userRoute[] = $scheduledclient[0];// 
    //gather Client info to display to user
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
    //if there is a user route, enable the "Get Route" Button
    if(count($userRoute) > 0){
    $options2 = '<button type="submit" name="plan" class="btn btn-primary btn-lg">
                <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>Get Route</button>';
    }
  }
}

//if plan route button was clicked, direct the user to the map page and generate their route.
if(isset($_POST['plan']) && isset($_POST['dayOfWeek'])){
  if(count($userRoute) > 0){
    
    //we initialize $coordinateArray with the coordinates of the meal pick-up point
    $coordinateArray  = array("26.701864,-80.051957");
    //Associate ClientIDs with coordinates
    for($i = 0; $i < count($userRoute); $i++)
    { 
      array_push($coordinateArray, getCoordinates($userRoute[$i], $link));
    }
    //google API has a function to optimize the route for us automatically
    //so now that $coordinateArray contains the route, we send it to the map.
    //we will optimize the route there, and then assemble the waypoints,
    //and finally direct the user to a map.
    $_SESSION['route'] = $coordinateArray;
    //var_dump($_SESSION['route']);
    header("Location: volunteer-route.php", false, 302);
  }       //external conditional
}         //function


//options to display to user only if a day of week is selected
//these buttons will allow user to add or remove users from their schedule
//there is also a button that will direct the user to the map with a planned route
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
        <h1 class="text-center"><?php if(isset($_POST['dayOfWeek']) && $dayOfWeek != ""){echo "$dayOfWeek Schedule";}
else{echo"Select a Day to Schedule Route";} ?>
        </h1>
        <!-- SELECT DAY OF WEEK TO POPULATE AVAILABLE RECIPIENTS AND CURRENT ROUTE -->
        <form role="form" action="#" method="post" >
          <div class="col-lg-12 row">
            <div class="col-lg-4 text-center"></div>
            <div class="col-lg-4 text-center">
              <select name="dayOfWeek" class="form-control">
                <?php if(isset($dayOfWeek)){echo"<option value='$dayOfWeek'>$dayOfWeek</option>";} ?>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
              </select>
            </div>
            <div class="col-lg-4 text-center"></div>
            <div class="col-lg-12 text-center">
              <button type="submit" class="btn btn-primary btn-lg">Select Day</a>
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
          <div class="col-lg-4 text-center">
            <h4 class="text-center"><?php echo $_SESSION['username']; ?>'s Route</h4>
            <table class="recipient-table">
              <tr>
                <th>Remove</th>
                <th>First Name</th>
                <th>Last Name</th>
              </tr>
              <?php echo $table2; ?>
            </table>
            <?php if(isset($_POST['dayOfWeek'])){ echo $options2; } ?>
          </div>
        </form>
      </div>
    </div>
    <hr>
    <?php require_once('../template/footer.html'); ?>
  </body>
</html>
