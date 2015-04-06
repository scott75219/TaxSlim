
function CallServer ()
{
 this.xhr_object;
 this.server_response;

 this.createXMLHTTPRequest = createXMLHTTPRequest;
 this.sendDataToServer = sendDataToServer;
 this.displayAnswer = displayAnswer;
 this.launch = launch;
}


// XMLHttpRequest object

function createXMLHTTPRequest()
{
 this.xhr_object = null;

 if(window.XMLHttpRequest)
 {
    this.xhr_object = new XMLHttpRequest();
 }
 else if(window.ActiveXObject)
 {
    this.xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
 }
 else
 {
    alert("Your browser doesn't provide XMLHttprequest functionality");
    return;
 }
}

//We send data to the server and it receives the response in synchronous mode from this.server_response

function sendDataToServer (data_to_send)
{
 var xhr_object = this.xhr_object;

 xhr_object.open("POST", "/cgi-bin/TaxSlim.pl", false);

 xhr_object.setRequestHeader("Content-type", "text/plain");

 xhr_object.send(data_to_send);

 if(xhr_object.readyState == 4)
 {
  this.server_response = xhr_object.responseText;
 }
}



// Inject the server response in the div named result

function displayAnswer ()
{
 document.getElementById("resultat").innerHTML = this.server_response;
}

//Exectute Javascript

function launch ()
{
  this.sendDataToServer(document.getElementById("text").value);
  this.sendDataToServer(document.getElementById("Dpth").value);
  this.sendDataToServer(document.getElementById("MyDrop").value);
    this.sendDataToServer(document.getElementById("Duplicates").value);


  this.displayAnswer();
}

//create call_server object

var call_server = new CallServer();
call_server.createXMLHTTPRequest();