<?php
require_once('connect.php');
$firstName = strip_tags($_POST['firstName']);
$lastName = strip_tags($_POST['lastName']);
$phone = strip_tags($_POST['phone']);
$address = strip_tags($_POST['address']);
$city = strip_tags($_POST['city']);
$zip = strip_tags($_POST['zip']);
$longitude = strip_tags($_POST['longitude']);
$latitude = strip_tags($_POST['latitude']);

$query = "INSERT INTO Clients(FirstName, LastName, Phone, Address, City, Zip, Latitude, Longitude) VALUES(
          '$firstName', '$lastName', '$phone', '$address', '$city', '$zip', '$latitude', '$longitude');";
$result = mysqli_query($link, $query);
if($result){  //if query worked, notify user
    echo "<meta http-equiv='refresh' content='0; url=../view/edit-recipients.php?message=clientadded'>";
}
else{
    echo"<meta http-equiv='refresh' content='0; url=../view/edit-recipients.php?message=error'>";
}
mysqli_free_result($result);
?>