<?php

/**
 * This file is necessary to include to use all the in-built libraries of /opt/fmc_repository/Reference/Common
 */
require_once '/opt/fmc_repository/Process/Reference/Common/common.php';

/**
 * List all the parameters required by the task
 */
function list_args()
{
}



$PROCESSINSTANCEID = $context['PROCESSINSTANCEID'];
$EXECNUMBER = $context['EXECNUMBER'];
$TASKID = $context['TASKID'];
$process_params = array('PROCESSINSTANCEID' => $PROCESSINSTANCEID,
						'EXECNUMBER' => $EXECNUMBER,
						'TASKID' => $TASKID);


$a = ".";
for ($index = 0; $index < 5; $index++) {
    $a .= ".";
    update_asynchronous_task_details($context, "Wait for device Ping" . $a . "\nloop no : " . ($index+1));
    sleep(2);
}


$device_ip_address = $context["device_ip_address"];

/* test Ping */
$response = wait_for_ping_status($device_ip_address, $process_params);
$response = json_decode($response, true);
if ($response['wo_status'] !== ENDED) {
	$response = json_encode($response);
	echo $response;
	exit;
}
$ping_status_message = $response['wo_comment'];

/* test SSH */
$port_no = SSH_DEFAULT_PORT_NO;
$response = wait_for_ssh_status($device_ip_address, $port_no, $process_params);
$response = json_decode($response, true);
if ($response['wo_status'] !== ENDED) {
	$response = json_encode($response);
	echo $response;
	exit;
}


task_exit(ENDED, "Task OK");


?>