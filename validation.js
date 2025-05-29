document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("adminForm");

    form.addEventListener("submit", function (e) {
        let isValid = true;

        
        document.querySelectorAll(".error-msg").forEach(el => el.innerHTML = "");

        const fullname = document.getElementById("fullname").value.trim();
        const adminID = document.getElementById("ID").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const dob = document.getElementById("DOB").value;
        const gender = form.querySelector('input[name="gender"]:checked');
        const religion = form.querySelectorAll('input[name="religion"]:checked');

       
        if (fullname === "") {
            document.getElementById("fullnameError").innerHTML = "Full Name is required.";
            isValid = false;
        }

        
        if (adminID === "") {
            document.getElementById("idError").innerHTML = "Admin ID is required.";
            isValid = false;
        }

        
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            document.getElementById("emailError").innerHTML = "Enter a valid email address.";
            isValid = false;
        }

        
        if (password.length < 6) {
            document.getElementById("passwordError").innerHTML = "Password must be at least 6 characters.";
            isValid = false;
        }

        
        if (!dob) {
            document.getElementById("dobError").innerHTML = "Date of Birth is required.";
            isValid = false;
        }

        
        if (!gender) {
            document.getElementById("genderError").innerHTML = "Please select your gender.";
            isValid = false;
        }

        
        if (religion.length === 0) {
            document.getElementById("religionError").innerHTML = "Please select at least one religion.";
            isValid = false;
        }

       
        if (!isValid) {
            e.preventDefault(); 
        }
    });
});
