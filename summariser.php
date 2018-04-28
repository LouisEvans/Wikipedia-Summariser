<?php
session_start();

//Assigns SESSION input to a variable
$textInp = $_SESSION["textInp"];
$inpSentences = $_SESSION["sentencesInp"];

//Makes it lowercase
//$lowerText = strtolower($inpText);

//Validation, checking the user isn't asking for too many sentences
if ($inpSentences > count($textInp)){
	//Redirect to toomanysentences.php
  header('Location: '."tooManySentences.php");
	//Stop all other processes on this page
  exit();
}

//Finds percentage decrease
$pcDecrease = findPercentage($inpSentences, $textInp);

//Declaring hashmap
$array = array();

//Finds uniqueness values
$array = findUniqueness($array, $textInp);

$finalSentences = findSentences($array, $textInp, $inpSentences);

function findPercentage($inpSentences, $textInp){
	//Finding percentage decrease
	return(number_format((float)($inpSentences / count($textInp)), 2, '.', ''));
}

function findUniqueness($array, $textInp){
	//For each sentence, calculate the uniqueness of the words inside the sentence.
	foreach ($textInp as $key => $value){
  	$wordScore = 0;
  	//Amount of words
  	$wordCount = str_word_count($value);
  	//For each unique word, add 1 to the word score.
  	foreach (array_count_values(str_word_count($value, 1)) as $key => $value){
    	$wordScore = $wordScore + 1;
  	}
  	//Total score is the amount of words, divided by the amount of unique words.
  	$uniqueness = $wordCount / $wordScore;

		//Pushes the uniqueness values into array $array
  	array_push($array, $uniqueness);
		
	}
	return($array);
}

function findSentences($array, $textInp, $inpSentences){
	
	//Finds how many sentences need to be removed
	$toRemove = count($array)-$inpSentences;

	//Sorts the array of scores from highest to lowest
	arsort($array);

	//Removes the sentences which aren't needed
	function array_psplice(&$array, $offset = 0, $length) {
		$return = array_slice($array, $offset, $length, true);

		foreach ($return as $key => $value) {
			unset($array[$key]);
		}

		return $return;
	}

	$newA = array_psplice($array, $toRemove);

	//Creating final sentences array
	$finalSentences = [];

	//For each sentence, reference this index to the original sentence list to append to $finalSentences
	foreach($newA as $key => $value){
		$sentenceValue = $textInp[$key];
		array_push($finalSentences, $sentenceValue);
	}
	
	return($finalSentences);
}

//Sending variables to summary.php
$_SESSION['sentences'] = $finalSentences;
$_SESSION['originalAmount'] = $inpSentences;
$_SESSION['afterAmount'] = count($textInp);
$_SESSION['pcDecrease'] = $pcDecrease;

//Redirects to summary.php
header('Location: '."summary.php");

?>