// script.js
document.addEventListener("DOMContentLoaded", () => {

  // Fade-in animation for the form
  const container = document.querySelector(".container");
  container.style.opacity = 0;
  setTimeout(() => {
    container.style.transition = "opacity 1s ease-in-out";
    container.style.opacity = 1;
  }, 100);

  // === LOGIN PAGE VALIDATION ===
  const loginForm = document.querySelector("form[action='login.php']");
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      const username = loginForm.querySelector("input[name='username']").value.trim();
      const password = loginForm.querySelector("input[name='password']").value.trim();

      if (username === "" || password === "") {
        alert("Please enter both username/email and password.");
        e.preventDefault();
      }
    });
  }

  // === REGISTER PAGE VALIDATION ===
  const registerForm = document.querySelector("form[action='register.php']");
  if (registerForm) {
    registerForm.addEventListener("submit", (e) => {
      const username = registerForm.querySelector("input[name='username']").value.trim();
      const email = registerForm.querySelector("input[name='email']").value.trim();
      const password = registerForm.querySelector("input[name='password']").value;
      const confirm = registerForm.querySelector("input[name='confirm_password']").value;

      // Check empty fields
      if (!username || !email || !password || !confirm) {
        alert("All fields are required!");
        e.preventDefault();
        return;
      }

      // Email format validation
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        e.preventDefault();
        return;
      }

      // Password confirmation
      if (password !== confirm) {
        alert("Passwords do not match!");
        e.preventDefault();
      }

      // Password strength check
      if (password.length < 6) {
        alert("Password should be at least 6 characters long.");
        e.preventDefault();
      }
    });
  }

});
