<?php 

require_once '/opt/fmc_repository/Process/Reference/Common/common.php';

function list_args()
{

}

// fake task to for IAP demo

sleep(10);

$response = prepare_json_response(ENDED, "Firewall config succesful", $context, true);
echo $response;


?>