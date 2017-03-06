<?php 

require_once('../php/connect.php'); 

//if not logged in as admin user - re-direct to home page
if(strtolower($_SESSION['username']) != 'admin'){
 echo"<meta http-equiv='refresh' content='0; url=../index.php'>"; 
}

//get all userinfo from database
$query = "SELECT * FROM Clients";
$result = mysqli_query($link, $query);
$column_count = mysqli_field_count($link);
$table = "";


while($row = mysqli_fetch_row($result)){
  $table .=  "<tr>\n
                <td class='text-center'><input type='checkbox' name='selectedRecipients[]' value='$row[0]'/></td>\n";
  $userID = $row[0];
  for($column = 1; $column < $column_count; $column++){
    $table .=   "<td>$row[$column]</td>\n";
  }

  $table .= "<td><a href='update-recipient.php?UserID=$userID'>Edit</a></td>\n</tr>\n";
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
          <h1 class="text-center">Current Recipients</h1>
          <form role="form" action="../php/delete-recipients.php" method = "post" >
            <div class="edit-recipient-options">
              <div class="col-md-3 text-center">
                <a class="btn btn-primary btn-lg" href="add-recipient.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>New Client</a>
              </div>
              <div class="col-md-6">
                <?php
                  if(isset($_GET['message'])){
                    if($_GET['message'] == 'noselection'){
                      echo "<p class='error-mssg text-center'>No clients selected for deletion</p>";
                    }
                    else if($_GET['message'] == 'clientsdeleted'){
                      echo "<p class='error-mssg text-center'>Selected clients succesfully deleted</p>";
                    }
                    else if($_GET['message'] == 'clientadded'){
                      echo "<p class='error-mssg text-center'>New Client Added</p>";
                    }
                    else if($_GET['message'] == 'clientupdated'){
                      echo "<p class='error-mssg text-center'>Client Updated</p>";
                    }
                    else if($_GET['message'] == 'error'){
                      echo "<p class='error-mssg text-center'>Error Updating Database, Please Try Again</p>";
                    }
                  }
                ?>
              </div>
              <div class="col-md-3 text-center">
                <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>Delete Clients</a>
              </div>
            </div>
            <table class="recipient-table col-lg-12">
              <tr>
                <th>Select</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>City</th>
                <th>Zip</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Edit</th>
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
