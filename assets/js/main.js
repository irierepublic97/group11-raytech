document.addEventListener('DOMContentLoaded', function() {
    var signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                event.preventDefault();
            }
        });
    }
});