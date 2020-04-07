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
  create_var_def('AwsDeviceId', 'Device');
  create_var_def('deviceId', 'Device');
  create_var_def('ImageId', 'String');
  create_var_def('InstanceType', 'String');
  create_var_def('security_group', 'String');
  create_var_def('SubnetId', 'OBMFref');
  create_var_def('device_ip_address', 'String');
  create_var_def('interface_id', 'String');
}

check_mandatory_param('ImageId');
check_mandatory_param('InstanceType');
check_mandatory_param('SubnetId');
check_mandatory_param('device_ip_address');
check_mandatory_param('interface_id');

$PROCESSINSTANCEID = $context['PROCESSINSTANCEID'];
$EXECNUMBER = $context['EXECNUMBER'];
$TASKID = $context['TASKID'];
$process_params = array('PROCESSINSTANCEID' => $PROCESSINSTANCEID,
						'EXECNUMBER' => $EXECNUMBER,
						'TASKID' => $TASKID);
	
$AwsDeviceId = $context["AwsDeviceId"];
$device_id = substr($AwsDeviceId,3);

$response = _device_read_by_id($device_id);
$response = json_decode($response, true);
if ($response['wo_status'] !== ENDED) {
	$response = json_encode($response);
	echo $response;
	exit;
}

$key = $response['wo_newparams']['login'];
$context["key"] = $key;
$secret = $response['wo_newparams']['password'];
$context["secret"] = $secret;

$response = _device_get_hostname_by_id($device_id);
$response = json_decode($response, true);
if ($response['wo_status'] !== ENDED) {
	$response = json_encode($response);
	echo $response;
	exit;
}
$region = $response['wo_newparams']['hostname'];
$context["region"] = $region;
$region = $context["region"];

$ec2Client = Ec2Client::factory(array(
    'key'    => $key,
    'secret' => $secret,
    'region' => $region
));

logToFile("ec2 client successful");

$array = array("ImageId" => $context["ImageId"], 
               "MinCount" => 1, 
               "MaxCount" => 1,
               "InstanceType" => $context['InstanceType'], 
               "Placement.AvailabilityZone" => $context["region"], 
               "SubnetId" => $context["SubnetId"], 
               "SecurityGroupIds" => array ("1" => $context["security_group"]));

logToFile(debug_dump($array, "AWS request array\n"));

$result = $ec2Client->runInstances($array);

logToFile("run instances successful : $result");

try {
	$res = $result->toArray();
	logToFile(debug_dump($res, "AWS response\n"));

	$context["InstanceId"] = $res["Instances"][0]["InstanceId"];

	$ec2Client->waitUntilInstanceRunning(array(
		'InstanceIds' => array($context["InstanceId"])
	));

	$result = $ec2Client->describeInstances(array(
			'InstanceIds' => array($context["InstanceId"])
	));

	if (isset($result["Reservations"][0]["Instances"][0]["PublicIpAddress"]) && !empty($result["Reservations"][0]["Instances"][0]["PublicIpAddress"])) {
	  
	  $context["temp_device_ip_address"] = $result["Reservations"][0]["Instances"][0]["PublicIpAddress"];
	} else {
	  echo "FAILED: No \"$PublicIpAddress\" is assigned to the created instance from AWS.";
	  exit;
	}
} catch (Exception $e) {
   task_exit(FAILED, "Error : $e");
}


task_exit(ENDED, "instance ". $context["InstanceId"] . " / ".$context["temp_device_ip_address"]." created (temporary address)");

?>
