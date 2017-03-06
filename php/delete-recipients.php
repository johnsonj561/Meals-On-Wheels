<?php
require_once('connect.php');

$selectedRecipients= $_POST['selectedRecipients'];

if(empty($selectedRecipients)){   //if no users were selected - return to recipients page and notify user
  echo"<meta http-equiv='refresh' content='0; url=../view/edit-recipients.php?message=noselection'>";
}
else{   //else remove each selected recipient from Recipients table
  $count = count($selectedRecipients);
  for($i = 0; $i < $count; $i++){
    $query = "DELETE FROM Schedule WHERE ClientID = '$selectedRecipients[$i]';";
    $result = mysqli_query($link, $query);
    $query = "DELETE FROM Clients WHERE ClientID = '$selectedRecipients[$i]';";
    $result = mysqli_query($link, $query);
  }
  echo"<meta http-equiv='refresh' content='0; url=../view/edit-recipients.php?message=clientsdeleted'>";
}

?>