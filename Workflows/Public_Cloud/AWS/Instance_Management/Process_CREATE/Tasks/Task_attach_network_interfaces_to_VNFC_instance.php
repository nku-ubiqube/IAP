<?php

/**
 * This file is necessary to include to use all the in-built libraries of /opt/fmc_repository/Reference/Common
 */
require_once '/opt/fmc_repository/Process/Reference/Common/common.php';
require '/opt/sms/bin/php/vendor/autoload.php';

use Aws\Ec2\Ec2Client;

/**
 * List all the parameters required by the task
 */
function list_args()
{
	//create_var_def('interface_id', 'String');
}

$context["interface_id"] = "eni-004b52afd89440c7d";

$ec2Client = Ec2Client::factory(array(
    'key'    => $context["key"],
    'secret' => $context["secret"],
    'region' => $context["region"]
));

logToFile("ec2 client successful");

logToFile(debug_dump($context, "DEBUG CONTEXT:\n"));


if (isset($context["interface_id"])) {
$interface_id = $context["interface_id"];

logToFile("Network Interfaces  ==> " . $interface_id . "\n");

$instanceId = $context["InstanceId"];

$array = array("DeviceIndex" => 1,
		"InstanceId" => $instanceId,
		"NetworkInterfaceId" => $interface_id);
					
$result = $ec2Client->attachNetworkInterface($array);

try {
	$res = $result->toArray();
	logToFile(debug_dump($res, "AWS response\n"));
}
catch (Exception $e) {
	task_exit(FAILED, "Error : $e");
}


task_exit(ENDED, 'Network interfaces '.$interface_id.' attached to instance'.$instanceId);
} else {
task_exit(ENDED, "No network interface where defined and attached to this instance. \n");
}
?>
