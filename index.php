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
require_once('select_date_form.php');
require_once('locallib.php');
require_login();

$action = optional_param('action', 'mails', PARAM_RAW);
$mtype = optional_param('mtype', 'latest_course_access', PARAM_RAW);
$ffrom = optional_param('ffrom', 0, PARAM_INT);
$fto = optional_param('fto', 0, PARAM_INT);

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/ildmotivationbot/index.php');
$PAGE->set_title(format_string(get_string('pluginname', 'local_ildmotivationbot')));
$PAGE->set_heading(format_string(get_string('pluginname', 'local_ildmotivationbot')));

global $DB, $USER, $COURSE;

$unit = 'days';
$units = array();
$out = '';

$js1 = '	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
			<script type="text/javascript">
				google.charts.load(\'current\', {\'packages\':[\'bar\']});
				google.charts.setOnLoadCallback(drawChart);

				function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  [\'Zeitraum\', \''.$action.'\']';
$js2 =			 ',
				  [\'2014\', 1000],
				  [\'2015\', 1170],
				  [\'2016\', 660],
				  [\'2017\', 1030]
				  ';
$js2 = '';
$js3 =			'
				 ]);

				var options = {
				  chart: {
					title: \''.get_string($mtype, 'local_ildmotivationbot').'\',
					subtitle: \''.get_string($action, 'local_ildmotivationbot').'\',
				  }
				};

				var chart = new google.charts.Bar(document.getElementById(\'columnchart_material\'));

				chart.draw(data, options);
				}
			</script>';

// TODO alle Textausgaben ins Sprachpaket

$out .= '<br />';

$out .= 'Alle versendeten Mails: '.$DB->count_records('local_ildmotivationbot').'<br />';
$out .= 'Alle geklickten Links: '.$DB->count_records('local_ildmotivationbot_click').'<br /><br />';

// Zeitraum von bis auswählbar machen ($startdate $enddate)
$mform = new select_date_form(new moodle_url('/local/ildmotivationbot/index.php?action='.$action.'&mtype='.$mtype));

if ($mform->is_cancelled()) {
	redirect(new moodle_url('/local/ildmotivationbot/index.php?action='.$action.'&mtype='.$mtype));
}
else if ($fromform = $mform->get_data()) {
	$from = $fromform->from;
	$to = $fromform->to;
	
	$startdate 	= $from;
	$enddate 	= strtotime(date('m/d/Y', $to)) + 3600 * 24;
}
else {
	$to_form = new stdclass();
	if ($ffrom == 0) {
		$from = $to_form->from = strtotime(date('01/01/Y', time())); // Erster des aktuellen Monats
	}
	else {
		$from = $to_form->from = $ffrom;
	}
	if ($fto == 0) {
		$to = $to_form->to = strtotime(date('m/d/Y', time())); // Aktuelles Datum
	}
	else {
		$to = $to_form->to = $fto;
	}
	$mform->set_data($to_form);
	
	$startdate 	= $from; // strtotime(Monat/Tag/Jahr)
	$enddate 	= $to + 3600 * 24;
}

$seconds = $enddate - $startdate;
$days = $seconds / (3600 * 24);

$types = $DB->get_records_sql('SELECT DISTINCT(type) FROM {local_ildmotivationbot}');
$sep = '';
foreach ($types as $type) {
	// links generieren mtype
	$out .= $sep.'<a href="'.$CFG->wwwroot.'/local/ildmotivationbot/index.php?action='.$action.'&ffrom='.$from.'&fto='.$to.'&mtype='.$type->type.'">'.get_string($type->type, 'local_ildmotivationbot').'</a>';
	$sep = ' / ';
}
$out .= '<br />';
$out .= '<a href="'.$CFG->wwwroot.'/local/ildmotivationbot/index.php?action=mails&ffrom='.$from.'&fto='.$to.'&mtype='.$mtype.'">'.get_string('mails', 'local_ildmotivationbot').'</a> / ';
$out .= '<a href="'.$CFG->wwwroot.'/local/ildmotivationbot/index.php?action=links&ffrom='.$from.'&fto='.$to.'&mtype='.$mtype.'">'.get_string('links', 'local_ildmotivationbot').'</a><br /><br />';

ob_start();
$mform->display();
$out .= ob_get_contents();
ob_end_clean();

$sql = 'SELECT ic.ildmotivationbotid 
		  FROM mdl_local_ildmotivationbot as i, mdl_local_ildmotivationbot_click as ic 
		 WHERE i.type = ? 
		   AND i.id = ic.ildmotivationbotid ';
		   
//foreach ($types as $type) {
	$out .= '<strong><br />';
	$out .= 'all emails sent ('.$mtype.'): '.$DB->count_records('local_ildmotivationbot', array('type' => $mtype)).'<br />';
	$out .= 'all links clicked ('.$mtype.'): '.count($DB->get_records_sql($sql, array($mtype))).'</strong><br />';
	if ($action == 'mails') {
	}
	elseif ($action == 'links') {
	}
	if ($days < 35) {
		$unit = 3600 * 24; //'days';
		for ($i = $startdate; $i < $enddate; $i = $i + 3600 * 24) {
			
			$sql2 = get_report_sql($action);
			$params = array($mtype, $i, $i + 3600 * 24);
			
			$js2 .= ',
			[\''.date('d.m.Y', $i).'\', '.count($DB->get_records_sql($sql2, $params)).']';
		}
	}
	elseif ($days < 63) {
		$unit = 3600 * 24 * 7; // weeks
		$monday = strtotime('monday this week', $startdate);
		for ($i = $monday; $i < $enddate; $i = $i + 3600 * 24 * 7) {
			$sql2 = get_report_sql($action);
			$start = $i;
			$end = $i + 3600 * 24 * 7;
			if ($start < $startdate) {
				$start = $startdate;
			}
			if ($end > $enddate) {
				$end = $enddate;
			}
			$params = array($mtype, $start, $end);
			$js2 .= ',
			[\''.get_string('week').date(' W Y', $i).'\', '.count($DB->get_records_sql($sql2, $params)).']';
		}
	}
	elseif ($days < 366) {
		$unit = 'months';
		$first = strtotime(date('m/01/Y', $startdate));
		for ($i = $first; $i < $enddate; $i = $i + 3600 * 24 * intval(date('t', $i))) {
			$sql2 = get_report_sql($action);
			$start = $i;
			$end = $i + 3600 * 24 * intval(date('t', $i));
			if ($start < $startdate) {
				$start = $startdate;
			}
			if ($end > $enddate) {
				$end = $enddate;
			}
			$params = array($mtype, $start, $end);
			$js2 .= ',
			[\''.date('F Y', $i).'\', '.count($DB->get_records_sql($sql2, $params)).']';
		}
		
	}
	else {
		$unit = 'years';
		$first = strtotime(date('01/01/Y', $startdate));
		for ($i = $first; $i < $enddate; $i = $i + (strtotime(date('12/31/Y', $i)) + (3600 * 24) - strtotime(date('01/01/Y', $i)))) {
			$sql2 = get_report_sql($action);
			$start = $i;
			$end = $i + (strtotime(date('12/31/Y', $i)) + (3600 * 24) - strtotime(date('01/01/Y', $i)));
			if ($start < $startdate) {
				$start = $startdate;
			}
			if ($end > $enddate) {
				$end = $enddate;
			}
			$params = array($mtype, $start, $end);
			$js2 .= ',
			[\''.date('Y', $i).'\', '.count($DB->get_records_sql($sql2, $params)).']';
		}
	}
//}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'local_ildmotivationbot'));

echo $js1.$js2.$js3;
echo $out;
echo '<br /><br /><div id="columnchart_material" style="width: 900px; height: 500px;"></div>';

echo $OUTPUT->footer();
//*/

?>
