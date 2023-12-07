const username = document.getElementById('username')
const password = document.getElementById('password')
const firstname = document.getElementById('firstname')
const lastname = document.getElementById('lastname')
const phone = document.getElementById('phone')
const country = document.getElementById('country')
const city = document.getElementById('city')
const address = document.getElementById('address')
const zip = document.getElementById('zip')





function FieldsTest(){
  if((username.value ==='' || username.value==null)&&(password.value ==='' || password.value==null)){
    alert('Both username and password is required');
  }else if(password.value ==='' || password.value==null){
    alert('Password is required');
  }else{
    alert('Username is required');
  }
}
