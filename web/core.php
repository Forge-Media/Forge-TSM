<?PHP

/*
    ______                    ___________ __  ___
   / ____/___  _________ ____/_  __/ ___//  |/  /_
  / /_  / __ \/ ___/ __ `/ _ \/ /  \__ \/ /|_/ /__ 
 / __/ / /_/ / /  / /_/ /  __/ /  ___/ / /  / /____  
/_/    \____/_/   \__, /\___/_/  /____/_/  /_/______   
                 /____/                          

Version: 0.1.0

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

$channel_deafults = array(
 	"channel_flag_permanent" => 1,
 	"channel_codec" => 4,
 	"channel_codec_quality" => 6
 	);
 		
$permissions_deafult = array(
	"i_channel_needed_delete_power" => '75',
	"i_channel_needed_permission_modify_power" => '70'
	);


	$channel_info = array(
		'0' => array (
        	'channel_name' => "[cspacer000] Clan Name",
        	'channel_topic' => "This is a sub-level channel",
		),
    	'1' => array (
        	'channel_name' => "Clan Channel 01",
        	'channel_topic' => "This is a sub-level channel",
    	),
    	'2' => array (
        	'channel_name' => "Clan Channel 02",
        	'channel_topic' => "This is a sub-level channel",
    	),
		'3' => array (
        	'channel_name' => "Clan Channel 03",
        	'channel_topic' => "This is a sub-level channel",
    	)
 		
	);
	
/*-------TS3 Object-------*/

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
	
	#Create channel
	if (!empty($channel_info)) {
		foreach ($channel_info as $key => $value) {
			$merged_array = $value + $channel_deafults; // preserves keys
			if ($key === 0) {
				$parent_id = createChannel($tsAdmin, $merged_array);
				setPerms($parent_id, $tsAdmin, $permissions_deafult);
			} else {
				$merged_array = $merged_array + $arrayName = array("cpid" => $parent_id['data']['cid']);
				$output = createChannel($tsAdmin, $merged_array);
				setPerms($output, $tsAdmin, $permissions_deafult);
			}
		}
	
	} else {
		echo 'Error: No channel information has been entered';
	}
	

	#Set message for TS & Web
	#$tsmessage = 'Success';
	#send message to Teamspeak
	#$tsAdmin->sendMessage($mode, $target, $tsmessage);
	} else{

	 echo 'Connection could not be established.';

}

/*-------Functions-------*/
	function createChannel($tsAdminF, $array) {
		return	$tsAdminF->channelCreate($array);	
	}
	
	function setPerms($cid, $tsAdminF, $perms) {
		if($cid['success'] === TRUE){
			return $tsAdminF->channelAddPerm($cid['data']['cid'], $perms);
		}
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