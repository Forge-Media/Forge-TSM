<?PHP

header("ContentType:application/json");

//$mydata = $_POST['channeldata'];

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

print_r(json_encode($request));
//echo $mydata;
