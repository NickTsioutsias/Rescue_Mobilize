document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
  
    // Fetch input values
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
  
    // Simple validation (you should perform secure validation on the server-side)
    if (username === 'user' && password === 'password') {
      // Successful login
      alert('Login successful!');
    } else {
      // Display error message
      document.getElementById('error-message').innerText = 'Invalid username or password. Please try again.';
    }
  });
  