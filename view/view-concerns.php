<?php require_once('../php/connect.php'); 
$table = "";

//if not logged in, re-direct to home page
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == false){
  $admin = false;
  echo"<meta http-equiv='refresh' content='0; url=../php/logout.php'>"; 
}
//if not logged in as administrator, redirect to home page
if((isset($_SESSION['username']) && strtolower($_SESSION['username']) != "admin")){
  $admin = false;
  echo"<meta http-equiv='refresh' content='0; url=../php/logout.php'>"; 
}
else{
  $admin = true;
  //placeholder variable for table
  $table = "";
  //populate table with concerns
  $query = "SELECT Concerns.ConcernID, Clients.FirstName, Clients.LastName, Concerns.Message, UserInfo.FirstName
          FROM Clients, Concerns, UserInfo
          WHERE Clients.ClientID = Concerns.ClientID
          AND UserInfo.UserID = Concerns.UserID
          ORDER BY Clients.ClientID;";
  $result = mysqli_query($link, $query);
  //if query was good, build table
  if($result){
    $column_count = mysqli_field_count($link);
    while($row = mysqli_fetch_row($result)){
      $concernID = $row[0];
      $table .= "<tr>\n";
      for($i = 1; $i < $column_count; $i++){
        $table .= "<td>$row[$i]</td>\n";
      }
      $table .= "<td><a href='../php/delete-concern.php?ConcernID=$concernID'>Delete</a></td>\n</tr>\n";
    }
  }
  //else, alert user and return to admin landing
  else{
    echo"<meta http-equiv='refresh' content='0; url=admin-landing.php?message=error'>"; 
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
    <h1 class="text-center header-spacing">Client Concerns</h1>
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
        <table class="recipient-table">
          <th>First Name</th>
          <th>Last Name</th>
          <th>Concern</th>
          <th>Submitted By</th>
          <th>Delete</th>
          <?php 
echo $table;
          ?>
        </table>
      </div>
    </div>
    <?php require_once('../template/footer.html'); ?>
  </body>

</html>