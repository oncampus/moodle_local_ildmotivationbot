<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Local ildmotivationbot
 *
 * @package    local
 * @subpackage local_ildmotivationbot
 * @copyright  2016 Jan Rieger Fachhochschule Lübeck
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
//* 
require_once('../../config.php');
require_once('locallib.php');
require_login();

$userid = optional_param('p', 0, PARAM_INT);
$logid = optional_param('l', 0, PARAM_INT);

if ($record = $DB->get_record('local_ildmotivationbot', array('id' => $logid, 'userid' => $userid))) {
	//print_object($record);

	if ($record->type == 'latest_access') {
		//echo 'latest_access';
		log_click($record->id);
		redirect($CFG->wwwroot);
	}
	elseif ($record->type == 'latest_course_access') {
		//echo 'latest_course_access';
		log_click($record->id);
		redirect($CFG->wwwroot.'/course/view.php?id='.$record->value);
	}
	
}
else {
	redirect($CFG->wwwroot);
}

//echo '*';