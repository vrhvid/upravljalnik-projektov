window.onload = function(){
    document.getElementById(window.localStorage.getItem("status")).setAttribute("selected", "selected");
}
        
function addTask(){
    var user = window.localStorage.getItem("id");
    var name = document.getElementById("name").value;
    var description = document.getElementById("description").value; 
    var status = Number(document.getElementById("status").value);
    
    var extResources = document.getElementById("extResources").value;
    if(extResources == ""){
        extResources = null;
    }
    
    var intResources = document.getElementById("intResources").value;
    if(intResources == ""){
        intResources = null;
    }
    
    if(window.localStorage.getItem("subTaskParent") === null){
        var parent = window.localStorage.getItem("parent");
    } else {
        var parent = window.localStorage.getItem("subTaskParent");
    }
        
    var xhr= new XMLHttpRequest();

    var url = new URL("http://localhost/upravljalnik-projektov/tasks.php"); 
    url.searchParams.set("action", 3);

    xhr.open("POST", url, true); 
	xhr.setRequestHeader("Accept", "application/json");
	xhr.setRequestHeader('Content-type', 'application/json');
				
	xhr.onreadystatechange = function (){
		if (xhr.readyState === 4){
			if(xhr.status === 200){
                document.getElementById("name").value = "";
                document.getElementById("description").value = ""; 
                document.getElementById(window.localStorage.getItem("status")).setAttribute("selected", "selected");
                document.getElementById("extResources").value = "";
                document.getElementById("intResources").value = "";

                alert("Vnos projekta uspešen!");
			}else{
				alert("Napaka pri vnosu projekta!");
			}
		}
    };  
	
    xhr.send(JSON.stringify({"user":user, "name":name, "description":description, "parent":parent, "status":status, "priority":1, "extResources":extResources, "intResources":intResources}));
}