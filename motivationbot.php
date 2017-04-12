<?php

define('DEV_USER', 4); // id
define('DEV_MODE', true);
define('MOTIVATION_BY_LATEST_ACCESS', 'latest_access');
define('MOTIVATION_BY_LATEST_COURSE_ACCESS', 'latest_course_access');

class motivationbot {
	var $wait_days = 0;
	var $max_emails = 1000;
	var $mailcount = 0;
	var $testmailcount = 0;
	var $mailed_users = array();
	
	function __construct($wait_days = 0, $max_emails = 1000) {
		$this->wait_days = $wait_days;
		$this->max_emails = $max_emails;
	}
	
	public function motivate_by_latest_access($days = 7) {
		global $DB;
		
		$latest_access = time() - ($days * 24 * 3600);
		// TODO extra setting für diesen type erfinden und abfragen:
		// Zeitraum, der abgewartet werden muss, bevor der Teilnehmer erneut zu diesem Thema angeschrieben wird
		$sql = 'SELECT id, firstname, lastname, email, lastaccess 
				  FROM {user} 
				 WHERE deleted = ? 
				   and lastaccess < ? ';
		$users_to_motivate = $DB->get_records_sql($sql, array(0, $latest_access));
		mtrace(count($users_to_motivate).' participants with lastest access to moodle, older than '.date('d.m.Y - H:i', $latest_access).':');
		$this->motivate_users($users_to_motivate, MOTIVATION_BY_LATEST_ACCESS);
	}
	
	public function motivate_by_latest_course_access($days = 7, $courseid = 0) {
		// select id, userid, courseid, min(timeaccess) from mdl_user_lastaccess where timeaccess < 1462281996 group by userid
		global $DB, $CFG;
		
		// cron für course completion einmal durchlaufen lassen um db (mdl_course_completion_crit_compl) einmal zu aktualisieren
		require_once($CFG->dirroot.'/completion/cron.php');
		completion_cron_criteria();

		$latest_course_access = time() - ($days * 24 * 3600);
		
		$and = '';
		if ($courseid > 1) {
			$and = ' AND ul.courseid = '.$courseid.' ';
			// TODO Dokumentieren !!!!!!!!!!!!!!!!!!!!!!
			//$and .= 'AND (SELECT COUNT(cc.id) FROM mdl_course_completions cc WHERE course = ul.courseid AND userid = ul.userid AND timecompleted IS NOT NULL) = 0 ';
		}
		$and .= 'AND (SELECT COUNT(cccc.id) FROM mdl_course_completion_crit_compl cccc WHERE course = ul.courseid AND userid = ul.userid) < 
					 (SELECT COUNT(ccc.id) FROM mdl_course_completion_criteria ccc WHERE course = ul.courseid) ';
		$sql = 'SELECT u.id, ul.userid, ul.courseid as value, min(ul.timeaccess), u.firstname, u.lastname 
		          FROM mdl_user_lastaccess ul, mdl_user u, mdl_enrol e, mdl_user_enrolments ue 
				 WHERE ul.timeaccess < ? 
				   AND ul.userid = u.id 
				   AND u.deleted = 0 '.
				   $and.' 
				   AND e.courseid = ul.courseid 
                   AND ue.enrolid = e.id 
                   AND ue.userid = u.id 
				 GROUP BY ul.userid ';
		$users_to_motivate = $DB->get_records_sql($sql, array($latest_course_access)); // TODO nur user die nicht deleted sind
		/* foreach ($users_to_motivate as $u) {
			mtrace($u->id);
		} */
		mtrace(count($users_to_motivate).' participants with lastest access to a course, older than '.date('d.m.Y - H:i', $latest_course_access).' ('.$latest_course_access.'):');
		$this->motivate_users($users_to_motivate, MOTIVATION_BY_LATEST_COURSE_ACCESS);
	}
	
	function get_motivation_subject($type, $level = 1) {
		$subject_text = get_string($type.'_subject_'.$level, 'local_ildmotivationbot');
		return $subject_text;
	}
	
	function get_motivation_text($type, $level, $logid, $user, $html = true) {
		global $CFG, $DB;
		$link = $CFG->wwwroot.'/local/ildmotivationbot/view.php?p='.$user->id.'&l='.$logid;
		$html_link = '<a href="'.$CFG->wwwroot.'/local/ildmotivationbot/view.php?p='.$user->id.'&l='.$logid.'">mooin.oncampus.de</a>';
		$a = new stdClass();
		$a->fullname = $user->firstname.' '.$user->lastname;
		if ($type == MOTIVATION_BY_LATEST_COURSE_ACCESS) {
			$a->coursename = $DB->get_field('course', 'fullname', array('id' => $user->value));
			//mtrace('debug: '.$a->coursename);
		}
		if ($html == true) {
			$a->link = $link;
			// Template aus cfg holen und Platzhalter durch $message_text ersetzen
			$message_text = get_string($type.'_html_'.$level, 'local_ildmotivationbot', $a);
			$message_text = str_replace('{content}', $message_text, get_config('local_ildmotivationbot', 'mail_html'));
		}
		else {
			$a->link = $link;
			$message_text = get_string($type.'_'.$level, 'local_ildmotivationbot', $a);
		}
		
		return $message_text;
	}
	
	function motivate_users($users_to_motivate, $type) {
		global $DB;
		foreach ($users_to_motivate as $utm) {
			if ($this->is_sending_mails_allowed()) {
				if ($this->motivation_allowed($utm->id, $type)) {
					$this->motivate_user($utm, $type);
				}
			}
			else {
				// maximum an versendeten Emails für diesen Task erreicht
				break;
			}
		}
	}
	
	public function motivate_user($utm, $type) {
		global $DB;
		$value = NULL;
		if (isset($utm->value)) {
			$value = $utm->value;
		}
		$last_escalation_level = $this->get_last_escalation_level($utm->id, $type);
		$escalation_level = $this->get_new_escalation_level($last_escalation_level);
		
		$logid = $this->log_motivation($utm->id, $type, $escalation_level, $value);
		
		if ($logid !== false) {
			$subject = $this->get_motivation_subject($type, $escalation_level);
			$motivation_message_html = $this->get_motivation_text($type, $escalation_level, $logid, $utm, true);
			$motivation_message = $this->get_motivation_text($type, $escalation_level, $logid, $utm, false);
			// motivieren: email_to_user();
			if ($this->motivate($utm->id, $subject, $motivation_message, $motivation_message_html)) {
				mtrace(' '.$type.' '.$utm->id.' '.$logid.' '.$value); // entfernen
				$this->mailcount++;
				//$this->mailed_users[] = $utm->id;
			}
			else {
				// logeintrag wieder löschen
				//mtrace('delete '.$logid);
				$DB->delete_records('local_ildmotivationbot', array('id' => $logid));
			}
		}
	}
	
	function motivate($userid, $subject, $message_text, $message_text_html) {
		global $CFG, $USER;
		$to = core_user::get_user($userid);
		$from = core_user::get_user(core_user::NOREPLY_USER);
		$from->firstname = get_config('local_ildmotivationbot', 'from_firstname');
		$from->lastname = get_config('local_ildmotivationbot', 'from_lastname');
		if (DEV_MODE) {
			if ($USER->id != 0) {
				echo '<br />'.$userid.' '.$to->firstname.' '.$to->lastname;
				if ($USER->id == $userid) {
					// 1 Testmail an mich versenden
					return email_to_user($to, $from, $subject, $message_text, $message_text_html);
				}
			}
			$this->testmailcount++;
		}
		if (!DEV_MODE) {
			return email_to_user($to, $from, $subject, $message_text, $message_text_html);
		}
		return false;
	}
	
	function get_new_escalation_level($last_level) {
		if ($last_level < 3) {
			$new_level = $last_level + 1;
			return $new_level;
		}
		return 1;
	}
	
	function get_last_escalation_level($userid, $type) {
		global $DB;
		$sql = 'SELECT level 
				  FROM {local_ildmotivationbot} 
				 WHERE userid = ? 
				   AND type = ? 
				 ORDER BY time DESC 
				 LIMIT 1 ';
		$logs = $DB->get_records_sql($sql, array($userid, $type));
		foreach ($logs as $log) {
			return $log->level;
		}
		return 0;
	}

	function motivation_allowed($userid, $mtype = '') {
		global $DB, $CFG;
		
		$sql = 'SELECT id, userid, time 
				  FROM {local_ildmotivationbot} 
				 WHERE userid = ? 
				 ORDER BY time DESC 
				 LIMIT 1 ';
		$logs = $DB->get_records_sql($sql, array($userid));
		foreach ($logs as $log) {

			$wait_seconds = $this->wait_days * 24 * 3600;

			// erst zusätzlich aus user config (opt out) ermitteln ob motivation erlaubt ist
			// Achtung custom profilefield "motivate" muss angelegt sein, damit mails versendet werden können !!!!!!!!!!!!!!!!!!!
			// TODO Dokumentieren !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			require_once($CFG->dirroot.'/user/profile/lib.php');
			$u = profile_user_record($userid);
			if (!isset($u->motivate) or $u->motivate == 0) {
				return false;
			}
			if ($log->time > time() - $wait_seconds) {
				return false;
			}
			if ($mtype != '') {
				// TODO prüfen ob die Wartezeit für den entsprechenden Motivationstyp erreicht ist
			}
		}
		return true;
	}

	function log_motivation($userid, $type, $level = 1, $value = '', $logtext = '') {
		global $DB;
		
		$record = new stdClass();
		$record->type = $type;
		$record->userid = $userid;
		if ($value != '') {
			$record->value = $value;
		}
		if ($logtext != '') {
			$record->logtext = $logtext;
		}
		$record->level = $level;
		$record->time = time();
		try {
			$logid = $DB->insert_record('local_ildmotivationbot', $record, true);
		}
		catch (Exception $e) {
			return false;
		}
		return $logid;
	}
	
	public function reset_mailcount() {
		$this->mailcount = 0;
	}
	
	public function get_mailcount() {
		return $this->mailcount;
	}
	
	public function get_testmailcount() {
		return $this->testmailcount;
	}
	
	public function dev_mode_enabled() {
		return DEV_MODE;
	}
	
	function is_sending_mails_allowed() {
		if ($this->mailcount < $this->max_emails) {
			return true;
		}
		return false;
	}
}