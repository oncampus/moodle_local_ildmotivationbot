<?php

function ildmotivationbot_cron() {
	require_once('motivationbot.php');
	
	mtrace(get_string('motivation_task', 'local_ildmotivationbot'));
	// motivationsmail mit link zum system /////////////////////////
	
	$next_motivation_in = get_config('local_ildmotivationbot', 'next_motivation_in');
	$max_mails_per_cron = get_config('local_ildmotivationbot', 'max_mails_per_cron');
	$motivationbot = new motivationbot($next_motivation_in, $max_mails_per_cron);
	
	global $USER;
	if ($motivationbot->dev_mode_enabled() and $USER->id != 0) {
		//print_object($USER);
		echo '<br />Your userid: '.$USER->id.'<br />';
	}
	
	// lastaccess < time() - user_inactive
	if (get_config('local_ildmotivationbot', 'user_inactive') != 0) {
		// TODO $motivationbot->motivate_by_latest_access(get_config('local_ildmotivationbot', 'user_inactive'));
	}
	
	// motivationsmail mit link zu best. Kurs //////////////////////
	// im Kurs angemeldet aber n Tage nicht aktiv gewesen
	if (get_config('local_ildmotivationbot', 'user_course_inactivity') != 0) {
		// course id aus settings/cfg holen
		$motivationbot->motivate_by_latest_course_access(get_config('local_ildmotivationbot', 'user_course_inactivity'), get_config('local_ildmotivationbot', 'user_course_inactivity_courseid'));
		$USER->value = get_config('local_ildmotivationbot', 'user_course_inactivity_courseid');
		$motivationbot->motivate_user($USER, 'latest_course_access');
	}
	
	if ($motivationbot->dev_mode_enabled() and $USER->id != 0) {
		echo '<br />mails not sent (because this is a test): '.$motivationbot->get_testmailcount().'<br />';
	}
	mtrace('mails sent: '.$motivationbot->get_mailcount());
}

// auf event beim login reagieren, in mdl_local_ildmotivationbot als nextlogin eintragen
// und level auf 0 zurücksetzen
function log_next_acccess($userid, $timecreated) {
	global $DB;
		
	$sql = 'SELECT id, time, nextlogin 
			  FROM {local_ildmotivationbot} 
			 WHERE userid = ? 
			   AND type = ? 
			 ORDER BY time DESC 
			 LIMIT 1 ';
	$logs = $DB->get_records_sql($sql, array($userid, 'latest_access'));
	foreach ($logs as $log) {		
		if (isset($log->nextlogin)) {
			return;
		}
		else {
			$log->nextlogin = $timecreated;
			$log->level = 0;
			$DB->update_record('local_ildmotivationbot', $log);
		}
	}
}

function log_next_course_acccess($userid, $timecreated, $courseid) {
	global $DB;
	$sql = 'SELECT id, time, nextlogin 
			  FROM {local_ildmotivationbot} 
			 WHERE userid = ? 
			   AND type = ? 
			   AND value = ?
			 ORDER BY time DESC 
			 LIMIT 1 ';
	$logs = $DB->get_records_sql($sql, array($userid, 'latest_course_access', $courseid));
	foreach ($logs as $log) {		
		if (isset($log->nextlogin)) {
			return;
		}
		else {
			$log->nextlogin = $timecreated;
			$log->level = 0;
			$DB->update_record('local_ildmotivationbot', $log);
		}
	}
}

function log_click($id) {
	global $DB;
	if ($DB->get_record('local_ildmotivationbot_click', array('ildmotivationbotid' => $id))) {
		return;
	}
	$click = new stdClass();
	$click->ildmotivationbotid = $id;
	$click->time = time();
	try {
		$DB->insert_record('local_ildmotivationbot_click', $click);
	}
	catch (Exception $e) {
		//
	}
}

function get_report_sql($action = 'mails') {
	$sql = '';
	if ($action == 'mails') {
		$sql = 'SELECT id 
				FROM {local_ildmotivationbot} 
			   WHERE type = ? 
				 AND time >= ? 
				 AND time < ? ';
	}
	elseif ($action == 'links') {
		$sql = 'SELECT ic.ildmotivationbotid 
				FROM mdl_local_ildmotivationbot as i, mdl_local_ildmotivationbot_click as ic 
			   WHERE i.type = ? 
				 AND i.id = ic.ildmotivationbotid
				 AND ic.time >= ?
				 AND ic.time < ? ';
	}
	return $sql;
}