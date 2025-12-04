function userLogin(){
    var name = document.getElementById("name").value;
    var surname = document.getElementById("surname").value; 
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var roles = document.getElementById("roles").value;
        
    var xhr= new XMLHttpRequest(); 
    xhr.open("POST", "users.php", true); 
	xhr.setRequestHeader("Accept", "application/json");
	xhr.setRequestHeader('Content-type', 'application/json');
				
	xhr.onreadystatechange = function (){
		if (xhr.readyState === 4){
			if(xhr.status === 200){
                document.getElementById("name").value = "";
                document.getElementById("surname").value =""; 
                document.getElementById("email").value = "";
                document.getElementById("password").value = "";
                document.getElementById("roles"). value = "";
                
                alert("Vnos uporabnika uspešen!");
			}else{
				alert("Napaka pri vnosu uporabnika!");
			}
		}
    };  
	
    xhr.send(JSON.stringify({"name":name, "surname":surname, "email":email, "password":password, "roles":roles}));
}

function logout(){
    window.localStorage.clear();	
    window.location = "login.html";
}