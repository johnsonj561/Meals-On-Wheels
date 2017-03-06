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
    <!-- Page Content -->
    <div class="container">
      <h1 class="intro-text text-center">Routing Services</h1>
      <h1 class="intro-text text-center">Registration</h1>
      <hr>
      <div class="sign-in-border">
        <form role="form" action="../php/process-registration.php" method = "post" 
              onsubmit = "return onRegistrationSubmit()">
          <div class="row text-center">
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">First Name</label>
                  <input autofocus type="text" class="form-control" name = "firstName" id = "firstName" required>
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="first_name_error" name="first_name_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Last Name</label>
                  <input autofocus type="text" class="form-control" name = "lastName" id = "lastName" required>
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="last_name_error" name="last_name_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Username</label>
                  <input autofocus type="text" class="form-control" name = "username" id = "username" required>
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="username_error" name="username_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Password</label>
                  <input type="password" class="form-control" name = "password1" id = "password1">
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="password1_error" name="password1_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Confirm Password</label>
                  <input type="password" class="form-control" name = "password2" id = "password2">
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="password2_error" name="password2_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Email Address</label>
                  <input type="email" class="form-control" name = "email" id = "email">
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="email_error" name="email_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Phone Number</label>
                  <input type="telephone" class="form-control" name = "phoneNumber" id = "phoneNumber">
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="telephone_error" name="telephone_error"></p></div>
              </div>
              <div class="row">
                <p class = "intro-text text-center error-mssg" name = "errorElement" id = "errorElement"></p>
              </div>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-primary btn-sign-in">Register</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <hr>
    </div>
  <?php require_once('../template/footer.html'); ?>
  <script src = "../js/validate-registration.js"></script>
  </body>
</html>