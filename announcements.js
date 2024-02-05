// Load Categories
let xhr = new XMLHttpRequest();
xhr.open('GET', 'includes/Categories.json', true);
xhr.onload = function () {
  if (xhr.status == 200) {
    // Get response as JSON data
    let jsonData = JSON.parse(this.responseText);

    let output = '<option>Choose a category</option>';
    for (let i in jsonData) {
      output += '<option>' + jsonData[i].categ_name + '</option>';
    }
    document.getElementById('category').innerHTML = output;
  } else {
    // Handle the error
    console.error('Error fetching JSON data. Status code: ' + xhr.status);
  }
};
// Send as associative arrays
xhr.send();

document.getElementById('category').addEventListener('input', function () {
  document.getElementById('item').disabled = !this.value; // if category is falsy, item is disabled
  document.getElementById('item').disabled = this.value === 'Choose a category';
});

let selectedText;
let selectedText2;

// Load Items based on category selected
document.getElementById('category').addEventListener('change', function () {
  let dropdown = document.getElementById("category");

  // Get the selected option
  let selectedOption = dropdown.options[dropdown.selectedIndex];

  // Get the text content of the selected option
  selectedText = selectedOption.textContent;

  // Log the selected text to the console
  console.log("Selected category:", selectedText);

  let xhr1 = new XMLHttpRequest();
  xhr1.open('POST', 'includes/select_items.inc.php', true);
  xhr1.onload = function () {
    if (xhr1.status == 200) {
      // Get response as JSON data
      let jsonData = JSON.parse(this.responseText);
      let output = '<option>Choose an item</option>';
      for (let i in jsonData) {
        output += '<option>' + jsonData[i].name + '</option>';
      }
      document.getElementById('item').innerHTML = output;
    } else {
      // Handle the error
      console.error('Error fetching JSON data. Status code: ' + xhr1.status);
    }
  };

  // Create FormData object
  let formData = new FormData();

  // Append selectedText to the FormData object
  formData.append('selectedText', selectedText);

  // Send the FormData object in the POST request
  xhr1.send(formData);
});

// Register announcement
document.getElementById('anouncement_form').addEventListener('submit', function(event){
  event.preventDefault();

  let description = document.getElementById('description').value;

  let dropdown = document.getElementById("category");

  // Get the selected option
  let selectedOption = dropdown.options[dropdown.selectedIndex];

  // Get the text content of the selected option
  selectedText = selectedOption.textContent;

  // Log the selected category to the console
  console.log("Selected category:", selectedText); 

  let dropdown2 = document.getElementById("item");

  // Get the selected option
  let selectedOption2 = dropdown2.options[dropdown2.selectedIndex];

  // Get the text content of the selected option
  selectedText2 = selectedOption2.textContent;

  // Log the selected item to the console
  console.log("Selected item:", selectedText2);

  let quantity = document.getElementById('quantity').value;

  let xhr2 = new XMLHttpRequest();
  xhr2.open('POST', 'includes/insert_announcement.inc.php', true);
  xhr2.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr2.onload = function(){
    if(xhr2.status == 200){
      console.log(this.responseText);
      // Get response as JSON data
      let response = JSON.parse(this.responseText);
      document.getElementById('message').innerHTML = response.message;

      // if(response.success){
      //   window.location.href = response.redirect;
      // }
    }
  };
xhr2.send('description=' + description + '&quantity=' + quantity + '&selectedText=' + selectedText + '&selectedText2=' + selectedText2);  
});

// announcements history table
let xhr3 = new XMLHttpRequest();
  xhr3.open('GET', 'includes/announcements-history.inc.php', true);
  xhr3.setRequestHeader('Content-type', 'application/json');

  xhr3.onload = function(){
    if(xhr3.status == 200){
      let jsonData = JSON.parse(this.responseText);

      let output = '<tr>' + '<th>' + 'Description' + '</th>' + '<th>' + 'Category' + '</th>' + '<th>' + 'Item' + 
      '</th>' + '<th>' + 'Quantity' + '</th>' + '</tr>';
      for (let i in jsonData) {
        output += '<tr>' + '<td>' + jsonData[i].description + '</td>' + '<td>' + jsonData[i].categ_name + '</td>' +
        '<td>' + jsonData[i].item_name + '</td>' + '<td>' + jsonData[i].quantity + '</td>' + '</tr>';
      }
      document.getElementById('announcements-history').innerHTML = output;
    } 
    else {
      // Handle the error
      console.error('Error fetching JSON data. Status code: ' + xhr.status);
    }
  };
xhr3.send();

// Logout request
document.getElementById('logout-form').addEventListener('submit', function(event){
  event.preventDefault();

  let xhr4 = new XMLHttpRequest();
  xhr4.open('post', 'includes/logout.php', true);
  xhr4.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr4.onload = function(){
    if(xhr4.status == 200){
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
xhr4.send();
}); 

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

