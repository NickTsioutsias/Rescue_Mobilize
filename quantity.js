// Data for autocomplete
let xhr = new XMLHttpRequest();
xhr.open('GET', 'includes/Items.json', true);
xhr.onload = function(){
  if(xhr.status == 200){
    // Get response as JSON data
    let jsonData = JSON.parse(this.responseText);
    console.log(jsonData);

    // Extracting only the "name" values from the JSON data
    const data = jsonData.map(item => item.name);

    // Logging the result
    console.log(data);

    const resultLimit = 5; // Set the maximum number of results to display

    // Get the input and results container
    const input = document.getElementById('autocomplete');
    const resultsContainer = document.getElementById('autocomplete-results');

    // Event listener for input changes
    input.addEventListener('input', function(){
      // Clear previous results
      resultsContainer.innerHTML = '';
      
      // Get input value
      const inputValue = input.value.toLowerCase();

      // Check if input is empty
      if (inputValue == '') {
        // Clear results container when input is empty
        resultsContainer.innerHTML = '';
        return; // Exit the function, no need to proceed further
      }

      // Filter data based on input value
      const filteredData = data.filter(item => item.toLowerCase().includes(inputValue)).slice(0, resultLimit);;

      // Display filtered results
      filteredData.forEach(item => {
        const resultElement = document.createElement('div');
        resultElement.classList.add('result');
        resultElement.textContent = item;

        // Event listener for result click
        resultElement.addEventListener('click', function () {
          input.value = item;
          resultsContainer.innerHTML = ''; // Clear results after selection
        });

        resultsContainer.appendChild(resultElement);
      });
    });
    
  }
  else {
    // Handle the error
    console.error('Error fetching JSON data. Status code: ' + xhr.status);
  }
};
// Send as associative arrays
xhr.send();

// Change quantity
document.getElementById('quantity-form').addEventListener('submit', function(event){
  event.preventDefault();

  let quantity = document.getElementById('quantity').value;
  let item = document.getElementById('autocomplete').value;

  let xhr = new XMLHttpRequest();
  xhr.open('POST', 'includes/quantity.inc.php', true);
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
xhr.send('quantity=' + quantity + '&item=' + item);  
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