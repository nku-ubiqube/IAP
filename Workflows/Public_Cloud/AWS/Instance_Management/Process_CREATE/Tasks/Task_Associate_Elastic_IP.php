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

#$context["device_ip_address"] = $elastic_ip;

task_success('Task OK: Elastic IP".$elastic_ip_id." associated');

?>
