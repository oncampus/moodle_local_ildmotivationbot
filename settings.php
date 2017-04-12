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

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    //global $CFG, $USER, $DB;

    //$moderator = get_admin();
    //$site = get_site();

    $settings = new admin_settingpage('local_ildmotivationbot', get_string('pluginname', 'local_ildmotivationbot'));
    $ADMIN->add('localplugins', $settings);
	
	// General settings
	$settings->add(new admin_setting_heading('local_ildmotivationbot/general_settings', get_string('general_settings_heading', 'local_ildmotivationbot'), get_string('general_settings_info', 'local_ildmotivationbot')));
	
	$name = 'local_ildmotivationbot/next_motivation_in';
	$title = get_string('next_motivation_in_title', 'local_ildmotivationbot');
	$description = get_string('next_motivation_in_desc', 'local_ildmotivationbot');
	$setting = new admin_setting_configtext($name, $title, $description, 7, PARAM_INT);
	$settings->add($setting);
	
	$name = 'local_ildmotivationbot/max_mails_per_cron';
	$title = get_string('max_mails_per_cron_title', 'local_ildmotivationbot');
	$description = get_string('max_mails_per_cron_desc', 'local_ildmotivationbot');
	$setting = new admin_setting_configtext($name, $title, $description, 1000, PARAM_INT);
	$settings->add($setting);
	
	// MOTIVATION_BY_LATEST_ACCESS
	$settings->add(new admin_setting_heading('local_ildmotivationbot/latest_access', get_string('latest_access_heading', 'local_ildmotivationbot'), get_string('latest_access_info', 'local_ildmotivationbot')));
	
	$name = 'local_ildmotivationbot/user_inactive';
	$title = get_string('user_inactive_title', 'local_ildmotivationbot');
	$description = get_string('user_inactive_desc', 'local_ildmotivationbot');
	$setting = new admin_setting_configtext($name, $title, $description, 0, PARAM_INT);
	$settings->add($setting);
	
	// MOTIVATION_BY_LATEST_COURSE_ACCESS
	$settings->add(new admin_setting_heading('local_ildmotivationbot/latest_course_access', get_string('latest_course_access_heading', 'local_ildmotivationbot'), get_string('latest_course_access_info', 'local_ildmotivationbot')));
	
	$name = 'local_ildmotivationbot/user_course_inactivity_next';
	$title = get_string('user_course_inactivity_next_title', 'local_ildmotivationbot');
	$description = get_string('user_course_inactivity_next_desc', 'local_ildmotivationbot');
	$setting = new admin_setting_configtext($name, $title, $description, 7, PARAM_INT);
	$settings->add($setting);
	
	$name = 'local_ildmotivationbot/user_course_inactivity';
	$title = get_string('user_course_inactivity_title', 'local_ildmotivationbot');
	$description = get_string('user_course_inactivity_desc', 'local_ildmotivationbot');
	$setting = new admin_setting_configtext($name, $title, $description, 0, PARAM_INT);
	$settings->add($setting);
	
	$name = 'local_ildmotivationbot/user_course_inactivity_courseid';
	$title = get_string('user_course_inactivity_courseid_title', 'local_ildmotivationbot');
	$description = get_string('user_course_inactivity_courseid_desc', 'local_ildmotivationbot');
	$setting = new admin_setting_configtext($name, $title, $description, 0, PARAM_INT);
	$settings->add($setting);
	
	// Course progress
	$settings->add(new admin_setting_heading('local_ildmotivationbot/course_progress_settings', get_string('course_progress_settings_heading', 'local_ildmotivationbot'), get_string('course_progress_settings_info', 'local_ildmotivationbot')));
	
	$name = 'local_ildmotivationbot/course_progress';
	$title = get_string('course_progress_title', 'local_ildmotivationbot');
	$description = get_string('course_progress_desc', 'local_ildmotivationbot');
	$setting = new admin_setting_configtext($name, $title, $description, 50, PARAM_INT);
	$settings->add($setting);
	
	// other settings
	$settings->add(new admin_setting_heading('local_ildmotivationbot/other_settings', get_string('other_settings_heading', 'local_ildmotivationbot'), get_string('other_settings_info', 'local_ildmotivationbot')));

	$name = 'local_ildmotivationbot/from_firstname';
	$title = get_string('from_firstname_title', 'local_ildmotivationbot');
	$description = get_string('from_firstname_desc', 'local_ildmotivationbot');
	$setting = new admin_setting_configtext($name, $title, $description, get_string('from_firstname_default', 'local_ildmotivationbot'));
	$settings->add($setting);
	
	$name = 'local_ildmotivationbot/from_lastname';
	$title = get_string('from_lastname_title', 'local_ildmotivationbot');
	$description = get_string('from_lastname_desc', 'local_ildmotivationbot');
	$setting = new admin_setting_configtext($name, $title, $description, get_string('from_lastname_default', 'local_ildmotivationbot'));
	$settings->add($setting);
    
	$name = 'local_ildmotivationbot/mail_html';
    $title = get_string('mail_html_title', 'local_ildmotivationbot');
    $description = get_string('mail_html_desc', 'local_ildmotivationbot');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $settings->add($setting);
}

