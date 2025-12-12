var taskId = window.localStorage.getItem("taskId");
var oldStatus;

window.onload = function(){
    var xhr= new XMLHttpRequest();
                
    var url = new URL("http://localhost/upravljalnik-projektov/tasks.php"); 
    url.searchParams.set("action", 2);

    xhr.open("POST", url, true); 
    xhr.setRequestHeader("Accept", "application/json");
    xhr.setRequestHeader('Content-type', 'application/json');
                    
    xhr.onreadystatechange = function (){
        if (xhr.readyState === 4){
            if(xhr.status === 200){
                var response = JSON.parse(xhr.responseText);

                document.getElementById("name").value = response["name"];
                document.getElementById("description").value = response["description"];
                
                oldStatus = response["numericStatus"];
                console.log(oldStatus)
                document.getElementById(Number(oldStatus)).setAttribute("selected", "selected");

                document.getElementById("priority").innerHTML = response["priority"];
                document.getElementById("extResources").value = response["externalresources"];
                document.getElementById("intResources").value = response["internalResources"];
                
                if(response["completed"]){
                    document.getElementById("completed").setAttribute("selected", "selected");
                } else {
                    document.getElementById("notCompleted").setAttribute("selected", "selected");
                }
            }
        }
    }
    
    xhr.send(JSON.stringify({"taskId":taskId}));
}

function updateTask(){
    var name = document.getElementById("name").value;
    var description = document.getElementById("description").value;
    
    var status = Number(document.getElementById("status").value);
    if(status == 3 || status == 4){
        status = oldStatus;
        alert("Željeni status ni podprt!");
    }
    var extResources = document.getElementById("extResources").value;
    var intResources = document.getElementById("intResources").value;
    
    if(document.getElementById("isCompleted").value == 1){
        var completed = false;
    } else {
        var completed = true;
    }

    var xhr= new XMLHttpRequest();

    var url = new URL("http://localhost/upravljalnik-projektov/tasks.php"); 

    xhr.open("PUT", url, true); 
    xhr.setRequestHeader("Accept", "application/json");
	xhr.setRequestHeader('Content-type', 'application/json');
				
	xhr.onreadystatechange = function (){
		if (xhr.readyState === 4){
			if(xhr.status === 200){
                location.reload();
            }
        }
    }
    
    param = JSON.stringify({"id":taskId, "name":name, "description":description, "status":status, "extResources":extResources, "intResources":intResources, "completed": completed});
    xhr.send(param);
}

function deleteTask(){
    var xhr= new XMLHttpRequest();

    var url = new URL("http://localhost/upravljalnik-projektov/tasks.php");
            
    xhr.open("DELETE", url, true); 
    xhr.setRequestHeader("Accept", "application/json");
	xhr.setRequestHeader('Content-type', 'application/json');
				
	xhr.onreadystatechange = function (){
		if (xhr.readyState === 4){
			if(xhr.status === 200){
                window.location = "tasks.html";
            }
        }
    }
    
    param = JSON.stringify({"id":taskId});
    xhr.send(param);
}