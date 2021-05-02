function myFunction() {
  var x = document.getElementById("loginType");
  if (x.innerHTML === "Not a Customer? Click Here to Login as a Delivery Driver.") {
    x.innerHTML = "Not a Delivery Driver? Click Here to Login as a Customer.";
  } else {
    x.innerHTML = "Not a Customer? Click Here to Login as a Delivery Driver.";
  }
} 
