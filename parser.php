<?php

//Takes posted variables
$inpText = $_POST["textBox"];
$url = $_POST["url"];
$inpSentences = $_POST["sentencesInp"];

//Starts new session
session_start();

//Checks if user is using a URL or not. Prioritises URL if user inputted both URL and plain text
if(empty($url)){
  
  //Splits sentences and sends to summariser if user inputs plain text
  plainText_split($inpText);
  
}else{
  
  //If the term "wikipedia.org" is NOT found in the url name, then
  if (strpos($url, 'wikipedia.org') == false) {
    //Output error message
    echo "Sorry, we couldn't process that URL. Please input a Wikipedia article URL.";
    exit();
  }
  
  //Take URL content, split into sentences then send to summariser.php
  URL_split($url);
  
}


function plainText_split($inpText){
  //Splits sentences and sends to summariser if user inputs plain text
  $sentences = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $inpText);
  $_SESSION['textInp'] = $sentences;
}

function URL_split($url){
  //Explode URL into an array, split up using the "/" as a divider
  $URLexploded = explode('/', $url);
  //Finds the index of the element AFTER "/wiki/"
  $wikiIndex = array_search("wiki",$URLexploded) + 1;
  //Sets $articleName as the string in the element
  $articleName = $URLexploded[$wikiIndex];
  
  //Encodes characters to what the API likes (%20 etc)
  $encoded = rawurlencode($articleName);
  //Fixes underscores (they weren't being encoded for some reason)
  $underscoreFix = str_replace('_', '%20', $encoded);
  //Assigns the API prefixes
  $newUrl = "https://en.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&titles=".$underscoreFix;

  //Gets contents of API URL
  $file = file_get_contents($newUrl);
  //Gets the content part
  $explosion = explode( '"extract"' , $file);
  $content = $explosion[1];
  //Removes all the "\n"s
  $textInp = (str_replace("\\n", '', $content));
  //Splits into sentences
  $sentences = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $textInp);
  //Sends sentences
  $_SESSION['textInp'] = $sentences;
}

//Sends the number of sentences
$_SESSION['sentencesInp'] = $inpSentences;
//Redirects
header('Location: '."summariser.php");

?>