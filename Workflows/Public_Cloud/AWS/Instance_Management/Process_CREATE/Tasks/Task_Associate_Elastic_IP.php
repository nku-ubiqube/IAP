<?php

/**
 * This file is necessary to include to use all the in-built libraries of /opt/fmc_repository/Reference/Common
 */
require_once '/opt/fmc_repository/Process/Reference/Common/common.php';

function list_args()
{
} 

$instance_id = $context["InstanceId"];
$elastic_ip_id = $context["elastic_ip_id"];
logToFile("instance ID: ".$instance_id);
logToFile("elastic IP ID: ".$elastic_ip_id);

$ec2Client = Ec2Client::factory(array(
    'key'    => $context["key"],
    'secret' => $context["secret"],
    'region' => $context["region"]
));

logToFile("ec2 client successful");
logToFile(debug_dump($context, "DEBUG CONTEXT:\n"));

$result = $ec2Client->associate-address($elastic_ip_id, $instance_id);
try {
    $res = $result->toArray();
    logToFile(debug_dump($res, "AWS response\n"));
}
catch (Exception $e) {
    task_exit(FAILED, "Error : $e");
}
#$context["device_ip_address"] = $elastic_ip;

task_success('Task OK: Elastic IP".$elastic_ip_id." associated');

?>
