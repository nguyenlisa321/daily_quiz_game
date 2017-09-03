<!DOCTYPE html>
<html>
 <head>
  <title>Welcome to Quiz of the Day!</title>
 </head>
 <body>


 <?php

 session_start();
 date_default_timezone_set('America/New_York');
 //Set cookie to show that user has taken the quiz
 if (!isset($_COOKIE[$_SESSION["user"]]))
    setcookie($_SESSION["user"], "taken", strtotime("tomorrow"));

//Next questions 
if(isset($_POST["answer"]) && (int)$_SESSION["num"] <= (int) $_SESSION["size"]-1 ) {
	$s = (int)$_SESSION["num"];
	$r = (int)$_SESSION["right"];
	$w = (int)$_SESSION["wrong"];
	if(strcmp(rtrim($_POST["answer"]), rtrim($_SESSION["answer"])) == 0){
		echo "Correct";
		$r++;
		$_SESSION["right"] = $r;
		echo "<br/>Total Correct: ".$_SESSION["right"];
		echo "<br/>Total Wrong: ".$_SESSION["wrong"];

	} else {		
		echo "Incorrect <br/>";
		$answer_arr = explode("#", $_SESSION["arr2"][$s]);		
		echo  "Correct answer is ".$answer_arr[(int)$_SESSION["answer"]];
		$w++;
		$_SESSION["wrong"] = $w;
		echo "<br/>Total Correct: ".$_SESSION["right"];
		echo "<br/>Total Wrong: ".$_SESSION["wrong"];

	}// Last question
	if ((int)$_SESSION["num"] == (int) $_SESSION["size"]-1){
		echo "<br/><h3>You have completed the quiz</h3>";
		$percent = ($r/($r + $w)) *100.0;
		echo "<br/>Your Quiz Average: %".$percent;
		$s = (int)$_SESSION["num"];
		$s++;
		$_SESSION["num"] = $s;
		writeBack();
		displayAvg();
		exit;
	} else {
	$s = (int)$_SESSION["num"];
	$s++;
	$_SESSION["num"] = $s;

	$question = explode("#", $_SESSION["arr2"][$s]);
    echo "<h3>".$question[0]."</h4>";

    $_SESSION["answer"] = $question[5];
    //Iterate through answers
    echo  '<form name = "infoform" action = "quiz.php" method = "POST">';

	for ($i = 1 ; $i <= (int) $_SESSION["size"]; $i++){
		echo '<input type = "radio" name ="answer" value = '.$i.'>'.$question[$i].'<br />';
	}
	echo '<input type="submit" name="formSubmit" value="Submit" />';
    

echo "</form>";
	exit;
}
	
}
//If already taken quizz for the day
if (isset($_COOKIE[$_SESSION["user"]])){
	echo "<h4>You have taken the quiz for today. Come back tommorrow!";
	echo '<br /><a href = "index.php" >Go Back To Login</a><br />';
	exit;
}
//Generate Random Number
$date = date('m/d/Y');
mt_srand(strtotime($date));
$randval = mt_rand();
//Read in quizzes
$arr = array();
$fileptr = fopen("quizzes.txt", "r");
 	if(flock($fileptr, LOCK_SH)){
 		while ($line = fgets($fileptr)) {
 			array_push($arr, $line); 
 		}

 		}
fclose($fileptr);
//Get the correct Quiz
 $_SESSION["quiz_num"] = $randval % count($arr);
 $quiz = explode("#", $arr[ $_SESSION["quiz_num"]]);
 $_SESSION["arr2"] = array();
 $fileptr2 = fopen($quiz[0].".txt", "r");
 	if(flock($fileptr2, LOCK_SH)){
 		while ($line = fgets($fileptr2)) {
 		array_push($_SESSION["arr2"], $line); 
 	}
 }

fclose($fileptr2);
//Set initial session variables
$_SESSION["start"] = true;
$_SESSION["num"] = 0;
$_SESSION["right"] = 0;
$_SESSION["wrong"] = 0;
$_SESSION["size"] = $quiz[1];
//Get first question
$question = explode("#", $_SESSION["arr2"][$_SESSION["num"]]);
echo "<h3>".$question[0]."</h4>";

$_SESSION["answer"] = $question[5];
//Iterate through all the answers
echo  '<form name = "infoform" action = "quiz.php" method = "POST">';

	for ($i = 1 ; $i <= 4; $i++){
		echo '<input type = "radio" name ="answer" value = '.$i.'>'.$question[$i].'<br />';
	}
	echo '<input type="submit" name="formSubmit" value="Submit" />';
    

echo "</form>";
//write results back in to file
function writeBack(){
	$arr = array();
	$fileptr = fopen("quizzes.txt", "r");
 	if(flock($fileptr, LOCK_SH)){
 		while ($line = fgets($fileptr)) {
 			array_push($arr, $line); 
 		}

 		}
fclose($fileptr);

 $quiz = explode("#", $arr[ $_SESSION["quiz_num"]]);
 $quiz[2] = (int)$quiz[2] + 1;
 $quiz[3] = (int)$quiz[3] + (int)$_SESSION["right"];
 $quiz[4] = (int)$quiz[4] + (int)$_SESSION["wrong"];
 $new_quiz = implode("#", $quiz); 
 $arr[ $_SESSION["quiz_num"]] = $new_quiz;
 $fp = fopen("quizzes.txt", "w");
 if (flock($fp, LOCK_EX)){
 	for($i = 0; $i < count($arr); $i++){
 	fwrite($fp, $arr[$i]);
}
 }
 fclose($fp);
}
// Display overall average
function displayAvg(){
	$arr = array();
	$fileptr = fopen("quizzes.txt", "r");
 	if(flock($fileptr, LOCK_SH)){
 		while ($line = fgets($fileptr)) {
 			array_push($arr, $line); 
 		}

 		}
fclose($fileptr);

 $quiz = explode("#", $arr[ $_SESSION["quiz_num"]]);
 $r = (int)$quiz[3];
 $w = (int)$quiz[4];
 $overall_avg = ($r / ($r + $w))* 100.0;
 echo "<br/>Overal average for quiz: %".$overall_avg;

}
?>
 </body>
</html>






