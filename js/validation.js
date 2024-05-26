function registerValidation() {
    // Create variables for elements that are taken from the html
    var username = document.getElementById("username").value;
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone_number").value;
    var role = document.getElementById("role").value;
    var password = document.getElementById("password").value;

    if(username === "" || name === "" || email === "" || phone === "" || role === "" || password === "") {
        alert("Fill all fields")
        return false;
    }

    // Handle validatio of email ensures that the email has an @ and has a .(period as well)
    if (email.indexOf("@") === -1 || email.indexOf(".") === -1) {
        alert("Please enter a valid email address");
        return false;
      }

}

// this handle the validation of the login logic
function loginValidation() {
    var username= document.getElementById("username").value;  
    var password = document.getElementById("password").value;

    if(username === "" || password === "") {
        alert("Fill all fields")
        return false;
    }
    return true;
}