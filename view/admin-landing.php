<?php require_once('../php/connect.php'); ?>
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
    <div class="container">
      <div class="row btn-row">
        <div class="col-med-12">
          <div class="col-md-12">
            <?php
              if(isset($_GET['message'])){
                if($_GET['message'] == 'error'){
                  echo "<p class='error-mssg text-center'>Error Accessing Database, Please Try Again</p>";
                }
              }
            ?>
          </div>
          <div class="col-md-3 text-center">
            <div class="btn-border">
              <a class="btn btn-primary btn-lg" href="edit-recipients.php">Edit Recipients</a>
              <p class="btn-description">Add new or remove old recipients from the program.</p>
            </div>
          </div>
          <div class="col-md-3 text-center">
            <div class="btn-border">
              <a class="btn btn-primary btn-lg" href="admin-schedule.php">Scheduling</a>
              <p class="btn-description">View schedule and check that recipients have assigned volunteers.</p>
            </div>
          </div>

          <div class="col-md-3 text-center">
            <div class="btn-border">
              <a class="btn btn-primary btn-lg" href="view-concerns.php">View Concerns</a>
              <p class="btn-description">View a list of recipients that volunteers are concerned about.</p>
            </div>
          </div>
          <div class="col-md-3 text-center">
            <div class="btn-border">
              <a class="btn btn-primary btn-lg" href="admin-checkins.php">Today's Checkins</a>
              <p class="btn-description">View list of volunteers that have not checked in for today's schedule.</p>
            </div>
          </div>
        </div>
      </div>
      <hr>
    </div>
    <?php require_once('../template/footer.html'); ?>
  </body>
</html>
