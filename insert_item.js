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
