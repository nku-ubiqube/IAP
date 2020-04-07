<?php

/**
 * This file is necessary to include to use all the in-built libraries of /opt/fmc_repository/Reference/Common
 */
require_once '/opt/fmc_repository/Process/Reference/Common/common.php';
require '/opt/sms/bin/php/vendor/autoload.php';

use Aws\Ec2\Ec2Client;

function list_args()
{
} 

$instance_id = $context["InstanceId"];
$elastic_ip = $context["device_ip_address"];
logToFile("instance ID: ".$instance_id);
logToFile("elastic IP: ".$elastic_ip);

$ec2Client = Ec2Client::factory(array(
    'key'    => $context["key"],
    'secret' => $context["secret"],
    'region' => $context["region"]
));

logToFile("ec2 client successful");
logToFile(debug_dump($context, "DEBUG CONTEXT:\n"));

$array = array("PublicIp" => $elastic_ip, 
               "InstanceId" => $instance_id, 
               "AllowReassignment" => true,
                );

logToFile(debug_dump($array, "AWS request array\n"));

$result = $ec2Client->associateAddress($array);

try {
    $res = $result->toArray();
    logToFile(debug_dump($res, "AWS response\n"));
}
catch (Exception $e) {
    task_exit(FAILED, "Error : $e");
}

task_success('Task OK: Elastic IP '.$elastic_ip.' associated to instance '.$instance_id);

?>
