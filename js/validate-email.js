function onLostPasswordSubmit() {
  if (!validateEmail()) {
    var t = document.getElementById("errorElement");
    t.innerHTML = "Please provide a valid e-mail address.";
    return false;
  } 
  return true;
}

function validateEmail() {
  var e = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
  var t = document.getElementById("email").value;
  if (t.match(e)) {
    return true
  } else {
    return false
  }
}

//To be added:
//Look up email in database and if no match notify user that email is invalid
