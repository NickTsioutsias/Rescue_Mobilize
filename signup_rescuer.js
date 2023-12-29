// Logout request
document.getElementById('logout-form').addEventListener('submit', function(event){
  event.preventDefault();

  let xhr = new XMLHttpRequest();
  xhr.open('post', 'includes/logout.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    if(xhr.status == 200){
      console.log(this.responseText);
      // Get response as JSON data
      let response = JSON.parse(this.responseText);
      if(response.success){
        // Redirect or perform other actions after successfull sigunp
        window.location.href = response.redirect;
      }
    }
  };
// Send as associative arrays to signup.php
xhr.send();
});

// Register rescuer request
document.getElementById('rescuer-form').addEventListener('submit', function(event){
  event.preventDefault();

  let username = document.getElementById('username').value;
  let password = document.getElementById('password').value;
  let name = document.getElementById('name').value;
  let lastname = document.getElementById('lastname').value;
  let car_name = document.getElementById('car_name').value;
  let latitude = document.getElementById('latitude').value;
  let longtitude = document.getElementById('longtitude').value;

  let xhr = new XMLHttpRequest();
  xhr.open('POST', 'signup_rescuer.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    if(xhr.status == 200){
      console.log(this.responseText);
      // Get response as JSON data
      let response = JSON.parse(this.responseText);
      document.getElementById('message').innerHTML = response.message;

      if(response.success){
        // Redirect or perform other actions after successfull sigunp
        window.location.href = response.redirect;
      }
    }
  };
// Send as associative arrays to signup.php
xhr.send('username=' + username + '&password=' + password + '&name=' + name + '&lastname=' + lastname + 
'&car_name=' + car_name + '&latitude=' + latitude + '&longtitude=' + longtitude);  
});
