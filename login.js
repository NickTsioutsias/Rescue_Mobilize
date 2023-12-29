document.getElementById('login-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent the default form submission

  let username = document.getElementById('username').value;
  let password = document.getElementById('password').value;

  let xhr = new XMLHttpRequest();
  xhr.open('POST', 'login.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    if (xhr.status === 200) {
      let response = JSON.parse(this.responseText);
      console.log(this.responseText);
      document.getElementById('message').innerHTML = response.message;

      if (response.success && response.redirect) {
        window.location.replace(response.redirect);
      }    
    }
};
// send as associative arrays to login.php file
xhr.send('username=' + username + '&password=' + password);
});