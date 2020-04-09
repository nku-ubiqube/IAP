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

$device_id=$context['device_id'];
$device_id=getIdFromUbiId ($device_id);


/**
* call to Microservice IMPORT to synchronize the MSA database with the managed AWS VIM
*/
synchronize_objects_and_verify_response($device_id);

task_success('VNF configuration synchronized');

?>

