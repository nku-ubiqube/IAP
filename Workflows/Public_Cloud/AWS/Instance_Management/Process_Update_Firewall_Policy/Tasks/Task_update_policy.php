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

/**
* NOTE
* This task doesn't do anything, the FW policy is updated by the process Launch Instance for the moment
* This can be changed once BPM is aware of WF instances and context can be passed from one BPM task to another
*/

sleep(5);


task_success('Firewall policy updated');

?>