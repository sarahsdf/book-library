<?php
	session_start();

	if( isset($_SESSION['username']) ){
		$username = $_SESSION['username']; //Want to re-instate this after we destroy the session.
	}   

	unset($_SESSION);
	session_destroy();

     header("Location: index.php");

?>