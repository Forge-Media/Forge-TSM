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
	//Check that there is channel information to be used
	if (!empty($channel_info)) {
		
		//Loop through array to create each channel
		foreach ($channel_info as $key => $value) {
			
			//Merge custom channel information with default settings
			$merged_array = $value + $channel_deafults; // preserves keys
			
			//If first channel create Master-Parent channel
			if ($key == 0) {
				
				//Create Channel Function: Create channel + record channels CID for parenting
				$parent_id = createChannel($tsAdmin, $merged_array, $permissions_deafult);
			
			//Create sub-channels of Master-Parent channel
			} else {

				//Add Master-Parent CID to channel information
				$merged_array = $merged_array + $arrayName = array("cpid" => $parent_id['data']['cid']);
				
				//Create Channel Function: create sub-channel
				createChannel($tsAdmin, $merged_array, $permissions_deafult); //Will require an extra IF check to see if channel is also a parent channel!
			}
		}
	
	} else {
		echo 'API Error: No channel information has been entered, cannot create channels <br>';
	}
	

	#Set message for TS & Web
	#$tsmessage = 'Success';
	#send message to Teamspeak
	#$tsAdmin->sendMessage($mode, $target, $tsmessage);
	} else{

	 echo 'Application could not establish a connection to the TS Server with IP: '.$ts3_ip.'<br>';

}

/*-------Functions-------*/

	//Create Channel Function (TS_Object, Channel_Information, Channel_Permissions)
	function createChannel($tsAdminF, $array, $perms) {
		
		//Create + Record the created channel's CID
		$result = $tsAdminF->channelCreate($array);
		
		//If channel creation succeeds continue
		if ($result['success'] === TRUE){
			
			echo 'Channel: '.$array['channel_name'].' - successfully created';
			
			setPerms($tsAdminF, $result, $perms);
			
		//If channel creation fails echo error
		} else {
			echo 'Function Error: Channel creation failure';
		}
		return $result;
			
	}
	
	
	//Set Permissions Function (TS_Object, Channel_ID, Channel_Permissions)
	function setPerms($tsAdminF, $cid, $perms) {

		//Set created channel's permissions
		$result = $tsAdminF->channelAddPerm($cid['data']['cid'], $perms);
		

		//If set perms fails echo error
		if($result['success'] === TRUE){
			
			echo ' and permissions successfully set <br>';
			
		} else {
			echo ' Function Error: Channel set permission failure <br>'; //	
		}
	}


#This code retuns all errors from the debugLog
if(count($tsAdmin->getDebugLog()) > 0) {

	$errors = 'TS Server Error Log:<br>';

	foreach($tsAdmin->getDebugLog() as $logEntry) {

		$errors = $errors.$logEntry.'<br>';
	}

	echo $errors;

}