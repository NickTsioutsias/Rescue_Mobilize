// Load Categories
let xhr = new XMLHttpRequest();
xhr.open('GET', 'includes/Categories.json', true);
xhr.onload = function(){
  if(xhr.status == 200){
    // Get response as JSON data
    let jsonData = JSON.parse(this.responseText);

      
      let output = '<option>Choose a category</option>';
    for(let i in jsonData){
      output += 
      '<option>'+jsonData[i].categ_name+'</option>';
    }
    document.getElementById('category').innerHTML = output;
  }
  else {
    // Handle the error
    console.error('Error fetching JSON data. Status code: ' + xhr.status);
  }
};
// Send as associative arrays
xhr.send();

document.getElementById('category').addEventListener('input', function(){
  document.getElementById('item').disabled = !this.value; // if category is falsy, item is disabled
  document.getElementById('item').disabled = this.value === 'Choose a category';
});

// Register item
document.getElementById('item-form').addEventListener('submit', function(event){
  event.preventDefault();

  let category = document.getElementById('category').value;
  console.log(category);
  let item = document.getElementById('item').value;

  let xhr = new XMLHttpRequest();
  xhr.open('POST', 'includes/insert_item.inc.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    if(xhr.status == 200){
      console.log(this.responseText);
      // Get response as JSON data
      let response = JSON.parse(this.responseText);
      document.getElementById('message').innerHTML = response.message;

      if(response.success){
        window.location.href = response.redirect;
      }
    }
  };
xhr.send('category=' + category + '&item=' + item);  
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
