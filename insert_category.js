document.getElementById('category-form').addEventListener('submit', function(event){
  event.preventDefault();
  let category = document.getElementById('category').value;
  let xhr = new XMLHttpRequest();
  xhr.open('post', 'includes/insert_category.inc.php', true);
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
// Send as associative arrays
xhr.send('category=' + category);
});