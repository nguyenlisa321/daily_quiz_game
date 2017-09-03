<!DOCTYPE html>
<html>
 <head>
  <title>Welcome to Quiz of the Day!</title>
 </head>
 <body>
 <?php

    session_start();
    //If revisiting page
 	if(isset($_COOKIE["login"])){
  echo "<b>Welcome Back ".$_COOKIE["login"]."</b><br />";
  echo '<a href = "index.php?change=true" >Change User</a><br />';
  echo "To start the quiz, <a href = 'quiz.php'>Click Here</a>";
} else {
	$_SESSION["user"] = $_POST["username"];
	$_SESSION["pass"] = $_POST["password"];
	if(isset($_POST["login"])){
	$_SESSION["login"] = $_POST["login"];
}
	$entry = $_SESSION["user"]."#".$_SESSION["pass"];
 	$correct= false;

 	$fileptr = fopen("users.txt", "r");
 	if(flock($fileptr, LOCK_SH)){
    while ($line = fgets($fileptr)) {
    	        if (rtrim($entry) == rtrim($line)){
        			$correct = true;
        }
    }
}


//Visiting page for the first time and correct logic in formation
if($correct){
	if(isset($_SESSION["login"])){
	if(strcmp($_SESSION["login"], "true") == 0){
		setcookie("login", $_SESSION["user"] , time()+600);
	} 
}

	echo "<b>Welcome ".$_POST["username"]."</b><br />";
	echo '<a href = "index.php?change=true" >Change User</a><br />';
	echo "To start the quiz, <a href = 'quiz.php'>Click Here</a>";
} else {
	
	echo "Incorrect password or username<br />";
	echo "<a href = 'index.php'>Try Again</a>";
}
 fclose($fileptr);
}

 ?>


</body>
</html>
