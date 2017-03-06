function onRegistrationSubmit() {
  if (!validateFirstName()) {
    displayFormErrors("name");
    return false;
  } else if(!validateLastName()){
    displayFormErrors("name");
    return false;
  } else if (!validateUsername()) {
    displayFormErrors("username");
    return false;
  } else if (!validatePassword()) {
    displayFormErrors("password");
    return false;
  } else if (!validateEmail()) {
    displayFormErrors("email");
    return false;
  } else if (!validateTelephone()) {
    displayFormErrors("telephone");
    return false;
  } 
  return true;
}

function validateFirstName() {
  var e = document.getElementById("firstName").value;
  if (e.length < 2 || e.length > 30) {
    return false;
  } else {
    return true;
  }
}

function validateLastName() {
  var e = document.getElementById("lastName").value;
  if (e.length < 2 || e.length > 30) {
    return false;
  } else {
    return true;
  }
}

//connect to database and check if available - needs to be finished
function validateUsername() {
  return true;
}

function validatePassword() {
  var e = document.getElementById("password1").value;
  var t = document.getElementById("password2").value;
  if (e == t) {
    if (e.length > 5) {
      return true
    } else {
      return false
    }
  } else {
    return false
  }
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

function validateTelephone() {
  var e = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
  var t = document.getElementById("phoneNumber").value;
  if (t.match(e)) {
    return true
  } else {
    return false
  }
}


function isNumber(e) {
  return !isNaN(parseFloat(e)) && isFinite(e)
}


function displayFormErrors(e) {
  var t = document.getElementById("errorElement");
  switch (e) {
    case "name":
      t.innerHTML = "Name must be between 2 - 30 characters.";
      break;
    case "email":
      t.innerHTML = "Please provide a valid e-mail address.";
      break;
    case "telephone":
      t.innerHTML = "Please provide a valid telephone number.";
      break;
    case "password":
      t.innerHTML = "Invalid Password. <br>Make sure password is at least 6 characters long and check that both passwords match.";
      break;
    case "username":
      t.innerHTML = "Username already in use, please select a different Username.";
      break;
    default:
      correctionsElement.innerHTML = "";
      break
  }
}
