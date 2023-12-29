document.getElementById('login-form').addEventListener('submit', function(event){
  event.preventDefault();

  let username = document.getElementById('username').value;
  let password = document.getElementById('password').value;
  let name = document.getElementById('name').value;
  let lastname = document.getElementById('lastname').value;
  let phone = document.getElementById('phone').value;
  let country = document.getElementById('country').value;
  let city = document.getElementById('city').value;
  let address = document.getElementById('address').value;
  let zip = document.getElementById('zip').value;

  // Create AJAX object
  let xhr = new XMLHttpRequest();
  // Open xhr to signup.php
  xhr.open('POST', 'signup.php', true);
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
'&phone=' + phone + '&country=' + country + '&city=' + city + '&address=' + address + '&zip=' + zip);  
});

