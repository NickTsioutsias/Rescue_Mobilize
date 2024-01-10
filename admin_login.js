document.getElementById('loginForm').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent the default form submission

  let username = document.getElementById('username').value;
  let password = document.getElementById('password').value;

  let xhr = new XMLHttpRequest();
  xhr.open('POST', 'admin_login.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    if (xhr.status === 200) {
      let response = JSON.parse(this.responseText);
      document.getElementById('message').innerHTML = response.message;

      if (response.success) {
        // Redirect or perform other actions after successful login
        window.location.href = 'main_admin.php';
      }
    }
};
// send as associative arrays to admin_login.php file
xhr.send('username=' + username + '&password=' + password);
});