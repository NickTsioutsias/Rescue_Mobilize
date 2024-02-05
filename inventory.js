// Load Base inventory
let output = '';
let xhr = new XMLHttpRequest();
xhr.open('GET', 'includes/base-inventory.inc.php', true);
xhr.onload = function () {
  if (xhr.status == 200) {
    // Get response as JSON data
    let response = JSON.parse(this.responseText);

    let output = '';
    for (let i in response) {
      output += '<tr><td>' + response[i].name + '</td>' + '<td>' + response[i].quantity + '</td></tr>';
    }
    document.getElementById('base-inventory').innerHTML = output;
  } else {
    // Handle the error
    console.error('Error fetching JSON data. Status code: ' + xhr.status);
  }
};
// Send as associative arrays
xhr.send();


let xhr4 = new XMLHttpRequest();
xhr4.open('GET', 'includes/car-inventory.inc.php', true);
xhr4.setRequestHeader('Content-type', 'application/json');

xhr4.onload = function () {
  if (xhr4.status == 200) {
    let responseObject = JSON.parse(this.responseText);

    // Create an object to store quantities and names for each resc_id
    let quantitiesAndNames = {};

    Object.keys(responseObject).forEach(function (rescId) {
      let arrayForRescId = responseObject[rescId];

      arrayForRescId.forEach(function (item) {
        let itemName = item.name;
        let quantity = item.quantity;
        let carName = item.car_name;

        // Initialize the array for each resc_id if not already done
        quantitiesAndNames[rescId] = quantitiesAndNames[rescId] || [];

        // Push quantity and name to the array
        quantitiesAndNames[rescId].push({ car_name: carName, name: itemName, quantity: quantity });
      });

      // Construct content for the bindPopup
      let content = '';
      Object.keys(quantitiesAndNames).forEach(function (rescId) {
        content += '<h2>' + quantitiesAndNames[rescId][0].car_name + ' Inventory</h2>' +
          '<table class="history"><tr><th>Item</th><th style="width:41%;">Quantity</th></tr>';

        quantitiesAndNames[rescId].forEach(function (item) {
          content += '<tr><td>' + item.name + '</td><td>' + item.quantity + '</td></tr>';
        });

        content += '</table><div class="announcements1"></div>';
      });

      document.getElementById('car-inventory').innerHTML = content;
    });
  }
};

xhr4.send();

// Logout request
document.getElementById('logout-form').addEventListener('submit', function(event){
  event.preventDefault();

  let xhr1 = new XMLHttpRequest();
  xhr1.open('post', 'includes/logout.php', true);
  xhr1.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr1.onload = function(){
    if(xhr1.status == 200){
      console.log(this.responseText);
      // Get response as JSON data
      let response = JSON.parse(this.responseText);
      if(response.success){
        // Redirect 
        window.location.href = response.redirect;
      }
    }
  };
// Send as associative arrays to logout.php
xhr1.send();
}); 
