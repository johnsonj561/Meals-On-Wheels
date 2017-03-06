<?php require_once('../php/connect.php'); 

//obtain username from $_SESSION variable
if((isset($_SESSION['username']) && $_SESSION['username'] != -1)){
  $username = $_SESSION['username'];
}
else{ //else username wasn't stored and user must log in again
  echo"<meta http-equiv='refresh' content='0; url=../index.php?error=session-ended'>"; 
}
//get UserInfo from database
$query = "SELECT * FROM UserInfo, Users WHERE 
          Users.Username = '$username' AND
          Users.UserID = UserInfo.UserID";
$result = mysqli_query($link, $query);
$userInfo = mysqli_fetch_assoc($result);
mysqli_free_result($result);

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
    <!-- Page Content -->
    <div class="container">
      <h1 class="intro-text text-center">Update Account</h1>
      <hr>
      <div class="sign-in-border">
        <form role="form" action="../php/update-account.php" method = "post" 
              onsubmit = "return onRegistrationSubmit()">
          <div class="row text-center">
            <div class="col-lg-12">
              <?php
                if(isset($_GET['error']) && $_GET['error'] == 'update-failed'){
                  echo "<p class='mssg-error text-center'>There was an error updating your account, please try again</p>";
                }
              ?>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">First Name</label>
                  <input autofocus type="text" class="form-control" name = "firstName" id = "firstName" required 
                         value="<?php echo $userInfo['FirstName']; ?>">
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="first_name_error" name="first_name_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Last Name</label>
                  <input autofocus type="text" class="form-control" name = "lastName" id = "lastName" required
                         value="<?php echo $userInfo['LastName']; ?>">
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="last_name_error" name="last_name_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Password</label>
                  <input type="password" class="form-control" name = "password1" id = "password1" required>
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="password1_error" name="password1_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Confirm Password</label>
                  <input type="password" class="form-control" name = "password2" id = "password2" required>
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="password2_error" name="password2_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Email Address</label>
                  <input type="email" class="form-control" name = "email" id = "email" required
                         value="<?php echo $userInfo['Email']; ?>">
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="email_error" name="email_error"></p></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Phone Number</label>
                  <input type="telephone" class="form-control" name = "phoneNumber" id = "phoneNumber" required
                         value="<?php echo $userInfo['Phone']; ?>">
                </div>
                <div class="col-lg-4"><p class="error-mssg" id="telephone_error" name="telephone_error"></p></div>
              </div>
              <div class="row">
                <p class = "intro-text text-center error-mssg" name = "errorElement" id = "errorElement"></p>
              </div>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-primary btn-sign-in">Update</button>
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