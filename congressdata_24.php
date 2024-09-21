<?php
	
// SETUP VARIABLES
$sessions = 0;
$streamedSessions = 0;
$moreThanOneSpeaker = 0;
$types = array();
$longestTitleLength = 0;
$longestTitle = '';
$totalTitleLength = 0;
$averageTitleLength = 0;

// DATA
$url = 'https://puzzles.code100.dev/code100/puzzles/congressdata/data.json';
$json = file_get_contents($url);
$data = json_decode($json, true);

foreach($data as $session){
	// INCREMENT SESSIONS
	$sessions++;
	
	// CHECK IF STREAMED, INCREMENT 
	if($session['streamprovider']){
		$streamedSessions++;
	}
	
	$sessionSpeakers = 0;
	foreach($session['speakers'] as $speaker){
		$sessionSpeakers++;
	}
	// CHECK FOR MORE THAN ONE SPEAKER
	if($sessionSpeakers>1){
		$moreThanOneSpeaker++;
	}
	
	// ADD TYPE TO ARRAY
	array_push($types, $session['type']);
	
	// CHECK TITLE LENGTH AND UPDATE $longestTitle AS NECESSARY, STORE LENGTH
	if(strlen($session['title'])>$longestTitleLength){
		$longestTitle = $session['title'];
		$longestTitleLength = strlen($session['title']);
	}
	
	// UPDATE TOTAL TITLE LENGTH
	$totalTitleLength = $totalTitleLength+strlen($session['title']);
}

$averageTitleLength = round($totalTitleLength/$sessions);

// REMOVE DUPLICATED SESSION TYPES
$types = array_unique($types);

// RESET KEYS
$types = array_values($types);

// CREATE ARRAY WITH DATA
$results = array(
	'sessions' 		=> $sessions,
	'streamedSessions' 	=> $streamedSessions,
	'moreThanOneSpeaker' 	=> $moreThanOneSpeaker,
	'types'			=> $types,
	'longestTitle'		=> $longestTitle,
	'averageTitleLength'	=> $averageTitleLength
);
$json = json_encode($results);

// SET HEADER
header('Content-Type: application/json; charset=utf-8');

// OUTPUT DATA
print_r($json);

?>
