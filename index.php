<?php

session_start();

// Time zone is needed for the date() function used below
date_default_timezone_set('America/New_York');
//Check of user is being changed
if(isset($_GET["change"])){
  setcookie("login", $user , time()-600);
  header("Location: index.php");
  exit;
}
//If keep login was checked earliar, log the same user in
if(isset($_COOKIE["login"])){
  show_header();
  header("Location: home.php");
  show_end();
} else{
//Get login information
    show_header();
    get_info();
    show_end();
}

function show_end()
{
    echo "</html>";
}

function show_header()
{
?>
<!DOCTYPE html>

<html>
<head>
<title>Quiz of the Day</title>
</head>
<?php
}
   

//Form for login information
function get_info()
{
    echo "<b>Welcome to Quiz of the Day!</b> <br />";
    echo "Login Here <br />";
?>

    <form name = "infoform"
         action = "home.php"
         method = "POST">
    Username: <input type = "text" name = "username"><br />
    Password: <input type = "text" name = "password"><br />
    Keep me login?:<input type = "checkbox" name ="login" value = true><br />
    
    <input type = "submit" value = "Submit">
    </form>
<?php
}


