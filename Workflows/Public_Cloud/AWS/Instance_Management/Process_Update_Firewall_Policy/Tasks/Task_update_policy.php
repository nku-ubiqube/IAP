<?php 

require_once '/opt/fmc_repository/Process/Reference/Common/common.php';

function list_args()
{
  create_var_def('lan_ip', 'String');
}

$PROCESSINSTANCEID = $context['PROCESSINSTANCEID'];
$EXECNUMBER = $context['EXECNUMBER'];
$TASKID = $context['TASKID'];
$process_params = array('PROCESSINSTANCEID' => $PROCESSINSTANCEID,
						'EXECNUMBER' => $EXECNUMBER,
						'TASKID' => $TASKID);

$device_id = substr($context['device_id'], 3);
$lan_ip=$context['lan_ip'];

$command1 = "config system interface";
$command2 = "edit port2";
$command3 = "set mode static";
$command4 = "set ip {$lan_ip} 255.255.255.0";
$command5 = "set allowaccess ping http";	
$command6 = "end";
$command7 = "";

$commands = "$command1\n$command2\n$command3\n$command4\n$command5\n$command6\n$command7";
$configuration = "\n$commands";

$response = _device_do_push_configuration_by_id($device_id, $configuration);
$response = json_decode($response, true);
if ($response['wo_status'] !== ENDED) {
	$response = json_encode($response);
	echo $response;
	exit;
}
	
$response = wait_for_pushconfig_completion($device_id, $process_params);
$response = json_decode($response, true);
if ($response['wo_status'] !== ENDED) {
	$response = json_encode($response);
	echo $response;
	exit;
}
$pushconfig_status_message = $response['wo_comment'];


$response = prepare_json_response(ENDED, "LAN interface config successful.\n$pushconfig_status_message", $context, true);
echo $response;


?>