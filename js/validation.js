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

//triage Validation
function triageformvalidation() {
    const temperature = document.getElementById('temperature').value.trim();
    const bloodPressure = document.getElementById('blood_pressure').value.trim();
    const weight = document.getElementById('weight').value.trim();
    const height = document.getElementById('height').value.trim();

    if (!temperature || isNaN(temperature) || temperature <= 0) {
        alert("Please enter a valid temperature.");
        return false;
    }

    if (!bloodPressure || isNaN(bloodPressure) || bloodPressure <= 0) {
        alert("Please enter a valid blood pressure.");
        return false;
    }

    if (!weight || isNaN(weight) || weight <= 0) {
        alert("Please enter a valid weight.");
        return false;
    }

    if (!height || isNaN(height) || height <= 0) {
        alert("Please enter a valid height.");
        return false;
    }

    return true;
}

// Consultation Clientside validation
function validateConsultationForm() {
    const consultationType = document.getElementById('consultation_type').value;
    const notes = document.getElementById('notes').value.trim();
    const diagnosis = document.getElementById('diagnosis').value.trim();
    const treatmentPlan = document.getElementById('treatment_plan').value.trim();

    if (consultationType === '') {
        alert('Please select a consultation type.');
        return false;
    }
    if (notes === '') {
        alert('Please enter notes.');
        return false;
    }
    if (diagnosis === '') {
        alert('Please enter a diagnosis.');
        return false;
    }
    if (treatmentPlan === '') {
        alert('Please enter a treatment plan.');
        return false;
    }

    return true;
}
