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
      <h1 class="intro-text text-center">Lost Password</h1>
      <h3 class="intro-text text-center">Please provide us with the email address associated with 
        your account</h3>
      <hr>
      <form role="form" action="../php/lost-password.php" method = "post" 
            onsubmit = "return onLostPasswordSubmit()">
        <div class="row text-center">
          <div class="col-lg-12">
            <div class="row">
              <div class="col-lg-4"></div>
              <div class="form-group col-lg-4">
                <label class="pull-left">Email</label>
                <input autofocus type="email" class="form-control" name = "email" id = "email" required>
              </div>
            </div>
          </div>
          <div class="row">
            <p class = "intro-text text-center error-mssg" name = "errorElement" id = "errorElement"></p>
            <?php
              if(isset($_GET['error'])){
                if($_GET['error'] == 'invalid-email'){
                  echo "<p class='error-mssg'>Email Does Not Match Our Records</p>";
                }
                if($_GET['error'] == 'new-password-failed'){
                  echo "<p class='error-mssg'>Error Occurred During Password Update</p><p class='error-mssg'>Please Try Again</p>";
                }
              }
            ?>
          </div>
        </div>
        <div class="form-group text-center">
          <button type="submit" class="btn btn-primary btn-sign-in">Submit</button>
        </div>
      </form>
    </div>
    <hr>
    <?php require_once('../template/footer.html'); ?>
    <script src = "../js/validate-email.js"></script>
  </body>
</html>