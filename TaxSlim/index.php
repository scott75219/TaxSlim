<html>
<link rel="stylesheet" type="text/css" href="files/css/Mine.css" />
<link rel="stylesheet" href="files/css/hive.css" type="text/css" />
<link rel="stylesheet" href="files/css/qpstyles.css" type="text/css" />
<link rel="stylesheet" href="files/css/menu.css" type="text/css" />
<link rel="stylesheet" href="files/old/oldcss/menu.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="files/old/oldcss/styles.css" />

<head>
	<title>Tax Slim</title>
	<script language='Javascript' type="text/javascript"src="files/js/heartcode-canvasloader-min-0.9.1.js"></script>
	<script language="JavaScript1.1" src="files/js/basic.js"> </script>
	<script language='Javascript' type="text/javascript">

var ajaxVariable = false;

            function ajaxFunction(param,url)
            {
                ajaxVariable = false;

                try {
                    ajaxVariable = new XMLHttpRequest();
                } catch(e){
                    try {
                        ajaxVariable = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch(e) {
                        try {
                            ajaxVariable = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch(e) {
                            alert("Your browser is not compatible")
                            return false;
                        }
                    }
                }

                var str = 'Name=' + param;

				alert(str);
                ajaxVariable.open('POST', url, false);

                ajaxVariable.send(str);

                return true;

            }

	function Start(){
	   setLocationTitle("Taxonomy Slim (Tax Slim)");
	}
	function SetText()
	{
	document.getElementById('textc').value == "";
	}
	function getTaxFile()
	{
		var name="files/"+getCookie("Taxfile");
		window.location.href = name;

	}
	function getHitsFile()
		{
			var name="files/"+getCookie("Hitsfile");
			window.location.href = name;

	}
	function getLogFile()
		{
			var name="files/"+getCookie("Logfile");
			window.location.href = name;

	}
	function checkCookie()
	{
	  var file=getCookie("Taxfile");
	  if(file!=null ){
	  document.getElementById('canvasloader-container').innerHTML = "";
     var frameElement=document.getElementById('frame');
     document.getElementById('DL').disabled=false;
     frameElement.style.height="100%";
     frameElement.style.visibility="visible";
     frameElement.src="/files/Results.php";
     var myResultDoc=frameElement.contentWindow.document;
	 //var resultDiv = myResultDoc.getElementById("result-div");
	 //frameElement.appendChild(resultDiv);
	  }

}

function DeleteAllCookies(){
	 	var TS=getCookie("Taxfile");
	 	var HI=getCookie("Hitsfile");
	 	var LO=getCookie("Logfile");
	 		if(TS !=null){
	 		var url="files/Delete.php";
	 		ajaxFunction(TS,url);
	 		ajaxFunction(HI,url);
	 		ajaxFunction(LO,url);}
	  delete_cookie("Taxfile");
	  delete_cookie("Hitsfile");
	  delete_cookie("Logfile");
}
var delete_cookie = function(name) {
    document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
}
function getCookie(c_name)
{
var c_value = document.cookie;
var c_start = c_value.indexOf(" " + c_name + "=");
if (c_start == -1)
  {
  c_start = c_value.indexOf(c_name + "=");
  }
if (c_start == -1)
  {
  c_value = null;
  }
else
  {
  c_start = c_value.indexOf("=", c_start) + 1;
  var c_end = c_value.indexOf(";", c_start);
  if (c_end == -1)
    {
    c_end = c_value.length;
    }
  c_value = unescape(c_value.substring(c_start,c_end));
  }
return c_value;
}

function AskAmount(type) {
							if (type=="number") document.getElementById('number').style.visibility='visible';
							else document.getElementById('number').style.visibility='hidden';
							}

	function CheckFileSelected(){
							if(document.getElementById('FileField').value != "") {
								document.getElementById('MyDrop').disabled=false;
							}
							else document.getElementById('MyDrop').disabled=true;
							{
								document.getElementById('number').style.visibility='hidden';
								document.getElementById('number').value="";
								document.getElementById('number').selectedIndex = 0;
							}
							}

function Enter(){
		document.getElementById('canvasloader-container').style.visibility='visible';
		var cl = new CanvasLoader('canvasloader-container');
		cl.setColor('#3a52b3'); // default is '#000000'
		cl.setShape('spiral'); // default is 'oval'
		cl.setSpeed(2); // default is 2
		cl.setFPS(24); // default is 24
		cl.show(); // Hidden by default

		  var loaderObj = document.getElementById("canvasLoader");
  		loaderObj.style.position = "absolute";
  		loaderObj.style["top"] = cl.getDiameter() * -0.5 + "px";
  		loaderObj.style["left"] = cl.getDiameter() * -0.5 + "px";
  		}

  	function validateForm(){
  	var CorrectDepth="";
  	var CorectInput="";
  	var x=document.forms["form1"]["depth"].value;

  	var DStatus = document.createElement('div');
  	var Ddiv = document.getElementById('DepthS');

  	var FStatus = document.createElement('div');
  	var Fdiv = document.getElementById('FileS');

  	if(isNaN(x) ||x<=2 ||document.forms["form1"]["depth"].value==""){
		if(!Ddiv.hasChildNodes()){
		DStatus.innerHTML = '<img src="files/img/warning.png" width="23" height="23" alt="Warning" title="Warning" align=left id="test">'+
						'<p align=left style="font-weight:bold;font-size:0.9em"> Please enter a numerical value for depth of at least 2</p>';
		Ddiv.appendChild(DStatus);
		}
	}
	else{
		if(Ddiv.hasChildNodes()){
			var test=document.getElementById('test');
			Ddiv.removeChild(Ddiv.lastChild);
		}
	}

	if(document.getElementById('FileField').value == "" && document.getElementById('textc').value == ' ')
	{
			if(!Fdiv.hasChildNodes()){
				FStatus.innerHTML = '<img src="files/img/warning.png" width="23" height="23" alt="Warning" title="Warning" align=left id="test">'+
						'<p align=left style="font-weight:bold;font-size:0.9em">Please enter data for input</p>';
						Fdiv.appendChild(FStatus);
			}
	}
	else{
	if(Fdiv.hasChildNodes()){
				Fdiv.removeChild(Fdiv.lastChild);
			}
	}
	if (!Fdiv.hasChildNodes() && !Ddiv.hasChildNodes())
	{
		document.getElementById('submit').disabled=false;
	}
	else
	{
	document.getElementById('submit').disabled=true;
	}
}
</script>

</head>
<div id="top_menu">
             <a id="logo" href="http://hive.biochemistry.gwu.edu/"><img border="0" src="files/img/hive-small-brand.png" height="35"/></a>
			<ul class="menu_panel">

				<li class="panel"><a class="menuitem" id="about" href ="http://hive.biochemistry.gwu.edu/about.php">ABOUT</a></li>
				<li class="panel"><a class="menuitem" id="tools" href ="http://hive.biochemistry.gwu.edu/tools.php">TOOLS</a></li>
				<li class="panel"><a class="menuitem" id="data" href ="http://hive.biochemistry.gwu.edu/data.php">DATA</a></li>
				<li class="panel"><a class="menuitem" id="grad" href ="http://www.gwumc.edu/biochem/academic/curriculumbioinformatics.html" target="_blank">COURSES</a></li>
				<li class="panel"><a class="menuitem" id="people" href ="http://hive.biochemistry.gwu.edu/people.php">PEOPLE</a></li>
				<li class="panel"><a class="menuitem" id="contact" href ="http://hive.biochemistry.gwu.edu/contact.php">CONTACT</a></li>
				<li class="panel"><a class="menuitem" id="external" href ="http://hive.biochemistry.gwu.edu/external.php">EXTERNAL LINKS</a></li>
				<li class="retrieve-panel">
					<form id="id-retrieval" method="GET" action="seq.cgi" name="retrieve" style="width:auto;float:right;margin:0px;">
						<input type="hidden" name="cmd" value="-qpCheck">
						<input type="text" class="inputEditable" name="req" value="">
						<input type="button" onclick='javascript:linkSelf("-qpCheck&req="+document.forms["retrieve"].elements["req"].value)' value="Go"/>
					</form>
				</li>
            </ul>
		</div>
		<div id="body_container">
			<div id="top_template">
				<h2 id='cgiLocation'>HIVE portal </h2>
			<div id="top_image"></top>
			</div>
			</div>

<body onunload="DeleteAllCookies()" onload="SetText();validateForm();Start()">
	<div id= "tool-parameter-holder">
		<b id="tool-heading">Input Parameters</b>
		<div id="inner-container2" >
			<div id="Left" style="width:25%;float:left">
				<GILabel>Gene Identifiers</GILabel>
				<form action="files/TaxSlim.pl"   id="form1" name="form1" method="POST" ENCTYPE="multipart/form-data" target="hidden_frame" >
				<GIs><textarea name="textcontent" id="textc" rows="9.5" cols="25" onchange="validateForm()"/> </textarea></GIs>
			</div>
			<div id="Center" style="margin-left:40px;width:25%;float:left; overflow:hidden">
				<b>Or File</b>
				<InputFile><input type="file" name="Input" id="FileField" onchange="CheckFileSelected();validateForm()" /></InputFile><br><br>
				Depth: <input type="text" name="depth" id="Dpth" style="width:30px" onchange="validateForm()" value="4"><br></Depth><br>
				<div data-role="controlgroup">
			    <Duplicates>
			    	<legend>Count and Scan Duplicates?</legend>
		         	<input type="radio" name="Dup" id="Duplicates" value="N" />
		         	<label for="radio-choice-1">No</label>
		         	<input type="radio" name="Dup"value="Y" checked="checked" />
		         	<label for="radio-choice-2">Yes</label>

			     </<Duplicates>
				</div><br>
				 <Amount><legend>How many GIs to scan?</legend>
						<select name="MyDropdown" id="MyDrop" onchange="AskAmount(this.value)" disabled>
							<option selected value="all">All</option>						<br>
							<option value="number">Type Number</option></Amount>
						</select>

				<input type="text" name="Size" id="number" style="width:65px;visibility: hidden">
					<input  type="submit"  id="submit" value="Submit" onclick="Enter()" style="font-weight:bolder;color:#555555" disabled />
			</div>
				<fieldset>
					    <legend style="font-weight:bold;" >Help</legend>
					    	<ul>
					    		<li>Enter gene identifiers, e.g. 2660724 46912264, one on each line</li>
					    	    <li>Or choose a premade new line deliminated file </li>
					    	    <li>Choose a depth of at least 2 </li>
					    	    <li>Choose Yes/No to scan duplicate GIs</li>
					    	    <li>Choose how many GIs to scan or  choose All </li>
					    	</ul>
			</form>
			<iframe name='hidden_frame' id="hidden_frame" style='display:none' onload="checkCookie()"></iframe>
	   </div>
	 </div>

		<div id= "tool-parameter-holder">
			<b id="tool-heading">Download Results</b>
			<div id="inner-container" >
				<fieldset id="DL" disabled style="border:0">
							<input  type="button"  id="TS" value="Tax Slim"onclick="getTaxFile()"/>
							<input  type="button"  id="HI" value="Hits" onclick="getHitsFile()"/>
							<input  type="button"  id="LO" value="Log" onclick="getLogFile()" />
				</fieldset>
			</div>
		</div>
						<div class = "Status" id="DepthS"></div>
						<div class = "Status" id="FileS"></div>
				</div>

		<div id="canvasloader-container" class="wrapper" target="frame" ></div>
		<div id="">
				<iframe name='frame' id="frame" frameBorder="0"style="visibility:hidden;padding:10px 10px 10px 10px;width:852px;margin-left:200px;border:1px solid #CCCCCC;font-size:85%"></iframe>
		</div>
</body>
</html>



