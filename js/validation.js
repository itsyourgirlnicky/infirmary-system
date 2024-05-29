function registerValidation() {
    var username = document.getElementById('username').value.trim();
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    var role = document.getElementById('role').value;

    // Client-side validation
    if (!username || !name || !email || !password || !confirmPassword || !role) {
        alert("All fields are required.");
        return false;
    }
  // Handle validation of email ensures that the email has an @ and has a .(period as well)
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Invalid email format.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    return true;
}


// this handle the validation of the login logic
function loginValidation() {
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();

    // Client-side validation
    if (!username || !password) {
        alert("All fields are required.");
        return false;
    }

    return true;
}

// this handle the validation of the AddPatient logic
function addpatientValidation() {
    var name = document.getElementById('name').value.trim();
    var age = document.getElementById('age').value.trim();
    var gender = document.getElementById('gender').value.trim();
    var contact_number = document.getElementById('contact_number').value.trim();
    var student_employee_number = document.getElementById('student_employee_number').value.trim();
    var address = document.getElementById('address').value.trim();

    // Client-side validation
    if (!name || !age || !gender || !contact_number || !student_employee_number || !address) {
        alert("All fields are required.");
        return false;
    }

    if (isNaN(age) || age <= 0) {
        alert("Invalid age.");
        return false;
    }

    if (isNaN(contact_number)) {
        alert("Invalid contact number.");
        return false;
    }

    return true;
}

function triageformvalidation() {
    
}