// Create offer
document.getElementById('offer-form').addEventListener('submit', function(event){
  event.preventDefault();

  let item = document.getElementById('item').value;
  let quantity = document.getElementById("quantity").value;
  let latitude = document.getElementById("latitude").value;
  let longitude = document.getElementById("longitude").value;

  console.log(item, quantity, latitude, longitude);

  let xhr = new XMLHttpRequest();
  xhr.open('POST', 'includes/insert_offer.inc.php', true);
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
xhr.send('item=' + item + '&quantity=' + quantity + '&latitude=' + latitude + '&longitude=' + longitude);  
});