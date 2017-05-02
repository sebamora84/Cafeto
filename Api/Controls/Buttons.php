<?php
function menuButtons(){
	if(!isset($_SESSION["username"])){
		return;
	}
	$uri=explode("?",substr($_SERVER['REQUEST_URI'], 1))[0];
	//Draw the buttons accordingly with the authorization
	$resources = $_SESSION["resources"];
	foreach ($resources as &$resource){
		if ($resource["visible"]){
			echo '<button '.getButtonClass($uri, "login.html").' onclick="window.location.href=\'./'.$resource["uri"].'\'">'.$resource["resource"].'</button>';
		}
	}
}

function profileButton(){
	if(isset($_SESSION["username"])){
		$username=$_SESSION["username"];
		$redirect = 'onclick="window.location.href=\'./profile.html\'"';		
	}
	else{
		$username ="Anónimo";
		$redirect ='';
	}
	$uri=explode("?",substr($_SERVER['REQUEST_URI'], 1))[0];
	
	echo '<button  '.getButtonClass($uri, "profile.html").'  '.$redirect.'  ><img src="Images/Icons/avatar.png" alt="user" width="auto" height="12">   '.$username.'</button>';
}

function sessionButton(){
	if(isset($_SESSION["username"])){
		$sessionAction="Cerrar Sesion";
	}
	else{
		$sessionAction="Iniciar Sesion";
	}
	$uri=explode("?",substr($_SERVER['REQUEST_URI'], 1))[0];
	echo '<button   '.getButtonClass($uri, "login.html").' onclick="window.location.href=\'./login.html\'">'.$sessionAction.'</button>';
}

function getButtonClass($uri, $matchUri){
	if($uri==$matchUri){
		$buttonClass = 'class="currentButton"';
	}
	else{
		$buttonClass='';
	}
	return $buttonClass;
}
?>
