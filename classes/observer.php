<?php

class local_ildmotivationbot_observer {

	public static function user_loggedin(\core\event\user_loggedin $event) {
		global $CFG;
		require_once($CFG->dirroot.'/local/ildmotivationbot/locallib.php');
		log_next_acccess($event->userid, $event->timecreated);
		return;
	}

	public static function course_viewed(\core\event\course_viewed $event) {
		global $CFG;
		require_once($CFG->dirroot.'/local/ildmotivationbot/locallib.php');
		if ($event->courseid != 1) {
			log_next_course_acccess($event->userid, $event->timecreated, $event->courseid);
		}
		return;
	}
}

?>