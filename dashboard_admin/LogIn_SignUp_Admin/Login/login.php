<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../design.css">
  <title>Meat Retailer Login</title>
</head>
<body>
  
<div class="container" id = "container">
  <div class="left-container">
    <img src="../Images/pork.png" class="pork">
  </div>
  <div class="right-container">
    <div class="login-container">
      <img src="../Images/mira.png" alt="Meat Retailer Logo" class="logo">
        <h2>Login</h2>
          <form method="POST">
            <input type="text" id="username" name="username" placeholder= "Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit"><span></span>Login</button>
            <div><a href="../ForgotPass/usercheck.php">Forgot Password?</a></div>
            <button type="button"><a href="../SignUp/signup.php">Sign Up</a></button>
          </form>
    </div>
  </div>
</div>

<div class="notification-container" id="notificationContainer">
  <div class="notification-icon" id="notificationIcon"></div>
  <div class="notification-message" id="notificationMessage"></div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const body = document.getElementById('container');
    const notificationContainer = document.getElementById('notificationContainer');
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationMessage = document.getElementById('notificationMessage');
    const form = document.querySelector('form');

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      // Get form data
      const formData = new FormData(form);

      fetch('login_database.php', {
        method: 'POST',
        body: formData,
      })
      .then(response => response.json())
      .then(data => {
        // Check the response from the server
        if (data.success) {
          // Show success notification
          showNotification('Log In successful! Redirecting to Dashboard.', true);
        } else {
          // Show error notification
          showNotification('Username and Password does not match.', false);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        // Show error notification
        showNotification('An unexpected error occurred. Please try again.', false);
      });
    });

    function showNotification(message, isSuccess) {
      // Blur the body
      body.classList.add('blurred');

      notificationContainer.style.display = 'flex';
      notificationMessage.innerText = message;

      // Set icon and background color based on success or error
      notificationIcon.innerText = isSuccess ? '✓' : '❌';
      notificationContainer.style.backgroundColor = isSuccess ? '#4CAF50' : '#FF0000';

      // Hide the notification after 3 seconds (adjust as needed)
      setTimeout(function () {
        notificationContainer.style.display = 'none';
        // Remove the blur effect
        body.classList.remove('blurred');
        isSuccess ? window.location.href = '../../Main_Dashboard/Dashboard/dashboard.php': window.location.href = '#';
      }, 1000);
    }
  });
</script>

</body>
</html>