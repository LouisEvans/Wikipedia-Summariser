<?php

//Starts session
session_start();

//Takes variables
$sentences = $_SESSION['sentences'];
$originalAmount = $_SESSION['originalAmount'];
$afterAmount = $_SESSION['afterAmount'];
$pcDecrease = $_SESSION['pcDecrease'];

//Prints top line
echo "Your text, summarised to ", $originalAmount, " sentence(s), from ",$afterAmount," sentences (",$pcDecrease,"%):";
echo "<br>";
echo "<br>";

//Prints sentences array
foreach ($sentences as $key => $value){
  echo "• ",$value;
  echo "<br>";
}

?>

<br>

<!--
<form action="summary.php" method="post">
How was our summary? <input type="number" min="0" max="5" step="1" name="rating"/>
<input type="submit">
</form>
-->
  
<?php

//$myFile = fopen("rating.txt", "w");
//fwrite($myFile, $_POST["rating"]);
//fclose($myFile);

 
?>

<br>
<a href="index.php">Back</a>