<?php require_once('../php/connect.php'); 
//get client ID from $_GET
if(isset($_GET['clientID'])){
  $clientID = $_GET['clientID'];
}
//else re-direct to report-concerns.php with error mssg
else{
  echo"<meta http-equiv='refresh' content='0; url=report-concerns.php?message=error'>"; 
}

//get current userid
if(isset($_SESSION['userID']) && $_SESSION['userID'] != -1){
  $userID = $_SESSION['userID'];
}
//else re-direct to home page to prompt logging in
else{
  echo"<meta http-equiv='refresh' content='0; url=../php/logout.php'>"; 
}


//if submitted, update database
if(isset($_POST['submit']) && isset($_POST['message'])){
  $message = $_POST['message'];
  $message = mysqli_real_escape_string($link, $message);
  $query = "INSERT INTO Concerns(UserID, ClientID, Message) VALUES
            ('$userID', '$clientID', '$message');";
  $result = mysqli_query($link, $query);
  if($result){    //provide user feedback
    echo"<meta http-equiv='refresh' content='0; url=report-concerns.php?message=success'>"; 
  }
  else{
    echo"<meta http-equiv='refresh' content='0; url=report-concerns.php?message=error'>"; 
  }
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
    <title>Meals on Wheels Route Planning</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom-css.css" rel="stylesheet">
  </head>
  <body>
    <?php require_once('../template/navbar.html'); ?>
    <div class="container">
      <form role="form" action="#" method = "post" onsubmit = "return onSubmitConcern()">
        <div class="row">
          <div class="form-group col-lg-12">
            <h1 class="text-center">Report Concerns</h1>
            <h4 class="text-center">Please provide an accurate description so we can best assist our clients</h4>
            <textarea class="form-control" rows="6" name = "message" id = "message" required></textarea>
          </div>
          <div class="form-group col-lg-12">
            <p class = "intro-text text-center error-mssg" name = "errorElement" id = "errorElement"></p>
          </div><br>
          <div class="form-group col-lg-12 text-center">
            <input type="hidden" name="save" value="contact">
            <button type="submit" name="submit" class="btn btn-primary btn-lg">Submit Concerns</button>
          </div>
        </div>
      </form>
    </div>
    <?php require_once('../template/footer.html'); ?>
    <script src = "../js/validate-conern.js"></script>
  </body>
</html>