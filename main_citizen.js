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

// announcements history table
let xhr2 = new XMLHttpRequest();
  xhr2.open('GET', 'includes/announcements-history.inc.php', true);
  xhr2.setRequestHeader('Content-type', 'application/json');

  xhr2.onload = function(){
    if(xhr2.status == 200){
      let jsonData = JSON.parse(this.responseText);

      let output = '<tr>' + '<th>' + 'Description' + '</th>' + '<th>' + 'Category' + '</th>' + '<th>' + 'Item' + 
      '</th>' + '<th>' + 'Quantity' + '</th>' + '<th>' + 'Make an Offer' + '</th>' + '</tr>';
      for (let i in jsonData) {
        output += '<tr>' + '<td>' + jsonData[i].description + '</td>' + '<td>' + jsonData[i].categ_name + '</td>' +
        '<td>' + jsonData[i].item_name + '</td>' + '<td>' + jsonData[i].quantity + '</td>' + '<td>' +  
        '<button class="offer-button" data-index="' + i + '">' + 'Offer' + '</button>' + '</td>' + '</tr>';
      }
      document.getElementById('announcements-history').innerHTML = output;
      // Add click event listener to offer buttons
      let offerButtons = document.getElementsByClassName('offer-button');
      for (let button of offerButtons) {
        button.addEventListener('click', function () {
        // Get the index from the data-index attribute
        let index = this.getAttribute('data-index');
        // Access the corresponding JSON data
        let selectedAnnouncement = jsonData[index];
        console.log(selectedAnnouncement);
        window.location.href = 'offer.php?announcement=' + encodeURIComponent(JSON.stringify(selectedAnnouncement));
      });
    } 
    } 
    else {
      // Handle the error
      console.error('Error fetching JSON data. Status code: ' + xhr.status);
    }
  };
xhr2.send();

// requests history table
let xhr3 = new XMLHttpRequest();
  xhr3.open('GET', 'includes/requests-history.inc.php', true);
  xhr3.setRequestHeader('Content-type', 'application/json');

  xhr3.onload = function(){
    if(xhr3.status == 200){
      let jsonData = JSON.parse(this.responseText);

      let output = '<tr>' + '<th>' + 'Item name' + '</th>' + '<th>' + 'People' + '</th>' + '<th>' + 'Date' + 
      '</th>' + '<th>' + 'Situation' + '</th>' + '</tr>';
      for (let i in jsonData) {
        output += '<tr>' + '<td>' + jsonData[i].name + '</td>' + '<td>' + jsonData[i].quantity + '</td>' +
        '<td>' + jsonData[i].publish_date + '</td>' +  '<td>' + '<button>' +
        'Offer' + '</button>' + '</td>' + '</tr>';
      }
      document.getElementById('requests-history').innerHTML = output;
    }
    else {
      // Handle the error
      console.error('Error fetching JSON data. Status code: ' + xhr.status);
    }
  };
xhr3.send();

// offers history table
let xhr4 = new XMLHttpRequest();
  xhr4.open('GET', 'includes/offers-history.inc.php', true);
  xhr4.setRequestHeader('Content-type', 'application/json');

  xhr4.onload = function(){
    if(xhr4.status == 200){
      let jsonData = JSON.parse(this.responseText);

      let output = '<tr>' + '<th>' + 'Item name' + '</th>' + '<th>' + 'People' + '</th>' + '<th>' + 'Date' + 
      '</th>' + '<th>' + 'Situation' + '</th>' + '</tr>';
      for (let i in jsonData) {
        output += '<tr>' + '<td>' + jsonData[i].name + '</td>' + '<td>' + jsonData[i].quantity + '</td>' +
        '<td>' + jsonData[i].publish_date + '</td>' +  '<td>' + '<button>' +
        'Offer' + '</button>' + '</td>' + '</tr>';
      }
      document.getElementById('offers-history').innerHTML = output;
    }
    else {
      // Handle the error
      console.error('Error fetching JSON data. Status code: ' + xhr.status);
    }
  };
xhr4.send();