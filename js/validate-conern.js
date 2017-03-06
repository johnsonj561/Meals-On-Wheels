function onSubmitConcern() {
  if (validateMessage()) {
    return true;
  }
  else{
    return false;
  }
}

function validateMessage(){
  var e = document.getElementById("message").value;
  if(e.length < 30){
    displayFormErrors("message-short");
    return false;
  }
  else if(e.length > 65000){
    displayFormErrors("message-long");
    return false;
  }
  else{
    return true;
  }
}

function displayFormErrors(e) {
  var t = document.getElementById("errorElement");
  switch (e) {
    case "message-short":
      t.innerHTML = "Please provide more information so we can better assist our client";
      break;
    case "message-long":
      t.innerHTML = "Your message must be less than 65,000 characters";
      break;
    default:
      correctionsElement.innerHTML = "";
      break;
  }
}