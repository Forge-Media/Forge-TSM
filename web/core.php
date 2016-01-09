<?PHP

/*
    ______                    ___________ __  ___
   / ____/___  _________ ____/_  __/ ___//  |/  /_
  / /_  / __ \/ ___/ __ `/ _ \/ /  \__ \/ /|_/ /__ 
 / __/ / /_/ / /  / /_/ /  __/ /  ___/ / /  / /____  
/_/    \____/_/   \__, /\___/_/  /____/_/  /_/______   
                 /____/                          

Simple web-front which allows the creation of channels on a Teamspeak 3 server
by Jeremy Paton & Marc Berman

Don't forget to edit & rename the CONFIG.PHP.Template to config.php!
*/

$configs = include('config.php');

/*-------Please edit config.php-------*/
$ts3_ip = $configs['ip'];
$ts3_queryport = $configs['queryport'];
$ts3_user = $configs['user']; #avoid serveradmin
$ts3_pass = $configs['pass'];
$ts3_port = $configs['port'];
$mode = $configs['mode']; #1: send to client | 2: send to channel | 3: send to server
$target = $configs['target']; #serverID
$botName = $configs['botName'];
/*------------------------------------*/

#Include ts3admin.class.php
require("../vendor/autoload.php");

#build a new ts3admin object
$tsAdmin = new ts3admin($ts3_ip, $ts3_queryport);

if($tsAdmin->getElement('success', $tsAdmin->connect())) {

	#login as serveradmin
	$tsAdmin->login($ts3_user, $ts3_pass);

	#select teamspeakserver
	$tsAdmin->selectServer($ts3_port);

	#set bot name
	$tsAdmin->setName($botName);
	
	$data = array(
		"channel_name" => "Test3",
 		"channel_topic" => "This is a top-level channel",
		"channel_flag_permanent" => 1);
	
	#Set message for TS & Web
	$tsmessage = 'Success';
  	print_r($tsmessage);
	
	#Create channel
	$output = $tsAdmin->channelCreate($data);
	print_r($output['data']);
    
  	#send message to Teamspeak
	$tsAdmin->sendMessage($mode, $target, $tsmessage);
  }

  else{

	 echo 'Connection could not be established.';

}


#This code retuns all errors from the debugLog
if(count($tsAdmin->getDebugLog()) > 0) {

	$errors = 'Errors:<br>';

	foreach($tsAdmin->getDebugLog() as $logEntry) {

		$errors = $errors.$logEntry.'<br>';
	}

	echo $errors;

}

?>