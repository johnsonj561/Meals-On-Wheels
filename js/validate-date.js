function onCheckin() {
  if (!validateDate()) {
    var t = document.getElementById("errorElement");
    t.innerHTML = "Please format date MM/DD/YYYY";
    return false;
  } 
}

function validateDate() {
  var t = document.getElementById("checkinDate").value;
  var e1 = /^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}$/;   //mm/dd/yyyy
  var e2 = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;     //yyyy-mm-dd
  if(t.match(e1) || t.match(e2)){
    return true;
  }
  else{
    return false;
  }
}