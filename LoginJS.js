const username = document.getElementById('username')
const password = document.getElementById('password')
const submit = document.getElementById('submit-button')



function FieldsTest(){
  if((username.value ==='' || username.value==null)&&(password.value ==='' || password.value==null)){
    alert('Both username and password is required');
  }else if(password.value ==='' || password.value==null){
    alert('Password is required');
  }else if(username.value ==='' || username.value==null){
    alert('Username is required');
  }
}
