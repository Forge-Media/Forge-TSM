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

header("ContentType:application/json");

try {
    //Gets posted data from Angular
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) {
        $_POST = json_decode(file_get_contents('php://input'), true);

        $errorResponse = array();
        $successResponse = array();

        $channel_info = $_POST['data'];

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
            "i_channel_needed_join_power" => '35',
            "i_channel_needed_modify_power" => '75',
            "i_channel_needed_delete_power" => '75',
            "i_channel_needed_permission_modify_power" => '70'
        );

        /*
            $channel_info = array(
                '0' => array (
                    'channel_name' => "[cspacer000] Channel Name",
                    'channel_topic' => "Throws on the Go",
                ),
                '1' => array (
                    'channel_name' => "Channel 01",
                    'channel_topic' => "This is a sub-level channel",
                ),
                '2' => array (
                    'channel_name' => "Channel 02",
                    'channel_topic' => "This is a sub-level channel",
                ),
                '3' => array (
                    'channel_name' => "Channel 03",
                    'channel_topic' => "This is a sub-level channel",
                )

            );
        */

        /*-------TS3 Object-------*/

#Include ts3admin.class.php
        require("../vendor/autoload.php");

#build a new ts3admin object
        $tsAdmin = new ts3admin($ts3_ip, $ts3_queryport);

        if ($tsAdmin->getElement('success', $tsAdmin->connect())) {

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
                        //Create Top Spacer
//                    createSpacer($tsAdmin);
                        //Create Channel Function: Create channel + record channels CID for parenting
                        $parent_id = createChannel($tsAdmin, $merged_array, $permissions_deafult);
                        //moveClients($tsAdmin, $parent_id);

                        //Create sub-channels of Master-Parent channel
                    } else {

                        //Add Master-Parent CID to channel information
                        $merged_array = $merged_array + $arrayName = array("cpid" => $parent_id['data']['cid']);

                        //Create Channel Function: create sub-channel
                        createChannel($tsAdmin, $merged_array, $permissions_deafult); //Will require an extra IF check to see if channel is also a parent channel!
                    }
                }
                //Create Bottom Spacer
                createSpacer($tsAdmin);

                if (empty($errorResponse)) {
                    array_push($successResponse, array('status' => 'success', 'message' => 'Succesfully created all channels.'));

                    echo json_encode(array("status"=>"success", "data"=>$successResponse));
                } else {
                    array_push($errorResponse, array('status' => 'error', 'message' => 'Channels creation was unsuccessful, please check the server and try again '));

                    echo json_encode(array("status"=>"error", "data"=>$errorResponse));
                }


            } else {
                array_push($errorResponse, array("status"=>"error", "data"=>array('status' => 'error', 'message' => 'API Error: No channel information has been entered, cannot create channels')));
                echo json_encode(array("status"=>"error", "data"=>$errorResponse));
            }


            #Set message for TS & Web
            #$tsmessage = 'Success';
            #send message to Teamspeak
            #$tsAdmin->sendMessage($mode, $target, $tsmessage);
        } else {
            array_push($errorResponse, array('status' => 'error', 'message' => 'Application could not establish a connection to the TS Server with IP: ' . $ts3_ip));
            echo json_encode(array("status"=>"error", "data"=>$errorResponse));
        }


#This code retuns all errors from the debugLog
        if (count($tsAdmin->getDebugLog()) > 0) {

            $errors = '<br>TS Server Error Log:<br>';

            foreach ($tsAdmin->getDebugLog() as $logEntry) {

                $errors = $errors . $logEntry . '<br>';
            }

           // echo $errors;

            array_push($errorResponse, array('status' => 'error', 'message' => $errors));

        }

#Logout
        $tsAdmin->logout();

    } else {
        array_push($errorResponse, array("status"=>"error", "data"=>array('status' => 'error', 'message' => 'Error communicating with the server, please try again later.')));
        echo json_encode(array("status"=>"error", "data"=>$errorResponse));
    }

} catch (Exception $e) {
    array_push($errorResponse, array('status' => 'error', 'message' => 'Error communicating with the server, please try again later.'));
    echo json_encode(array("status"=>"error", "data"=>$errorResponse));
}
/*-------Functions-------*/

//Create Channel Function (TS_Object, Channel_Information, Channel_Permissions)
function createChannel($tsAdminF, $array, $perms)
{
    global $successResponse;
    global $errorResponse;
    //Create + Record the created channel's CID
    $result = $tsAdminF->channelCreate($array);

    //If channel creation succeeds continue
    if ($result['success'] === TRUE) {

        array_push($successResponse, array('status' => 'success', 'message' => 'Channel: ' . $array['channel_name'] . ' - successfully created'));

        setPerms($tsAdminF, $result, $perms);

        //If channel creation fails echo error
    } else {
        array_push($errorResponse, array('status' => 'error', 'message' => 'Function Error: Channel creation failure'));
    }
    return $result;

}

//Create Channel Function (TS_Object, Channel_Information, Channel_Permissions)
function createSpacer($tsAdminF)
{
    global $successResponse;
    global $errorResponse;

    $n = rand(0, 9999);

    $Fchannel_deafults = array(
        "channel_flag_permanent" => 1,
        "channel_name" => '[*cspacer' . $n . ']_',
        "channel_topic" => 'Spacer',
        "channel_max_users" => 0,
        "channel_flag_maxclients_unlimited" => 2,
        "channel_flag_maxfamilyclients_unlimited" => 2,
        "channel_codec" => 1,
        "channel_codec_quality" => 1
    );

    $Fpermissions_deafult = array(
        "i_channel_needed_delete_power" => '75',
        "i_channel_needed_permission_modify_power" => '70'
    );

    //Create + Record the created channel's CID
    $result = $tsAdminF->channelCreate($Fchannel_deafults);

    //If channel creation succeeds continue
    if ($result['success'] === TRUE) {
        array_push($successResponse, array('status' => 'success', 'message' => 'Channel spacer successfully created'));

        setPerms($tsAdminF, $result, $Fpermissions_deafult);

        //If channel creation fails echo error
    } else {
        array_push($errorResponse, array('status' => 'error', 'message' => 'Function Error: Spacer creation failure'));
    }
    return $result;

}

//Set Permissions Function (TS_Object, Channel_ID, Channel_Permissions)
function setPerms($tsAdminF, $cid, $perms)
{
    global $successResponse;
    global $errorResponse;

    //Set created channel's permissions
    $result = $tsAdminF->channelAddPerm($cid['data']['cid'], $perms);


    //If set perms fails echo error
    if ($result['success'] === TRUE) {
        array_push($successResponse, array('status' => 'success', 'message' => 'With permissions successfully set'));

    } else {
        array_push($errorResponse, array('status' => 'error', 'message' => 'Function Error: Channel set permission failure'));
    }
}


function moveClients($tsAdminF, $cid)
{

    //Set channel group (to be passed in future)
    $sgid = 46;

    //Set up the array of all members in the set channel group (must be true)
    $clientsarray = $tsAdminF->serverGroupClientList($sgid, $names = true);

    //If the client array is populated succesfully continue
    if ($clientsarray['success'] === TRUE) {

        //Loop through client array's data array
        foreach ($clientsarray['data'] as $value) {

            //If the index value is not empty continue
            if (!empty($value['client_unique_identifier'])) {

                //Get each Client Unique ID from the client array
                $cuid = $value['client_unique_identifier'];

                //Find the Client ID Array based on the Client Unique ID
                $clinetidsarray = $tsAdminF->clientGetIds($cuid);

                //Make sure user is online
                if (empty($clinetidsarray['errors'])) {
                    //Set the Client ID
                    $clid = $clinetidsarray['data'][0]['clid'];

                    //Move the selected client to the new Parent channel & return result
                    $tsAdminF->clientMove($clid, $cid['data']['cid']);
                }
            }
        }
    } else {
        array_push($errorResponse,array('status' => 'error', 'message' => 'Function Error: Could not populate ($clientsarray) with provided server Group ID'));
    }
}
