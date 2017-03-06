<?php require_once('../php/connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Add Client</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom-css.css" rel="stylesheet">
  </head>
  <body>
    <?php require_once('../template/navbar.html'); ?>
    <!-- Page Content -->
    <div class="container"><a name="top"></a>
      <h1 class="intro-text text-center">Add New Client to Database</h1>
      <hr>
      <div class="sign-in-border">
        <form role="form" action="../php/add-recipient.php" method = "post" 
              onsubmit = "return onAddClient()">
          <div class="row text-center">
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">First Name</label>
                  <input autofocus type="text" class="form-control" name = "firstName" id = "firstName" required>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Last Name</label>
                  <input autofocus type="text" class="form-control" name = "lastName" id = "lastName" required>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Phone</label>
                  <input autofocus type="text" class="form-control" name = "phone" id = "phone" required>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Address</label>
                  <input type="text" class="form-control" name = "address" id = "address">
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">City</label>
                  <input type="text" class="form-control" name = "city" id = "city">
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-4">
                  <label class="pull-left">Zip Code</label>
                  <input type="text" class="form-control" name = "zip" id = "zip">
                </div>
                <div class="col-lg-4"></div>
              </div>
              <div class="row">
                <div class="col-lg-4"></div>
                <div class="form-group col-lg-2">
                  <label class="pull-left">Latitude</label>
                  <input type="text" class="form-control" name = "latitude" id = "latitude">
                </div>
                <div class="form-group col-lg-2">
                  <label class="pull-left">Longitude</label>
                  <input type="text" class="form-control" name = "longitude" id = "longitude">
                </div>
                <div class="col-lg-4"></div>
              </div>
              <div class="row">
                <p class = "intro-text text-center error-mssg" name = "errorElement" id = "errorElement"></p>
              </div>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add Client</button>
            </div>
            <p>
          </div>
        </form>
      </div>
    </div>
    <hr>
    </div>
  <?php require_once('../template/footer.html'); ?>
  <script src = "../js/validate-new-client.js"></script>
  </body>
</html>