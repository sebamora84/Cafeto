<?php
function verifySession(){
	if (isset($_SESSION['discard_after']) && time() > $_SESSION['discard_after']) {
		session_start();
		// remove all session variables
		session_unset();
		// destroy the session
		session_destroy();
		header("Location:/notAuthorized.html?uri=".$uri."&m=expired");
		exit();
	}
	session_start();
	
	$uri=explode("?",substr($_SERVER['REQUEST_URI'], 1))[0];	
	
	
	
	//Session not started
	if(!isset($_SESSION["username"])){
		//Every body has access to the root. No need for a session
		if($uri==""){
			return;
		}
		else{
			header("Location:/login.html?uri=".$uri);
			exit();
		}
	}
	
	//The resource was already checked
	if($uri=="notAuthorized.html"||$uri=="underConstruction.html"){
		return;
	}
		
	//Must Reset Pwd
	if($_SESSION["reset"]=="1" && $uri!="profile.html"){
		header("Location:/profile.html?uri=".$uri."&m=reset");
		exit();
	}
	
	//Check the authorization to the resource	
	$resources = $_SESSION["resources"];
	foreach ($resources as &$resource){
		if($resource["uri"]==$uri){
			return;
		}
	}
	
	header("Location:/notAuthorized.html?uri=".$uri."&m=nauth");
	exit();
}

function destroySession(){
	session_start();
	// remove all session variables
	session_unset();
	// destroy the session
	session_destroy();
}

?>
