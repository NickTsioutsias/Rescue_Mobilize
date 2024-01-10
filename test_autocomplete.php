<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Autocomplete</title>
  <style>
    *{
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }
  </style>
</head>
<body>
    <div class="search-box">
      <form action="">
        <input type="text" id="input-box" placeholder="Search" autocomplete="off">
        <button type="submit" name="submit-button" id="submit-button">Submit</button>
        <div class="result-box">
          <ul>
            <li>Javascript</li>
          </ul>
        </div> 
      </form>
    </div>




</body>
</html>