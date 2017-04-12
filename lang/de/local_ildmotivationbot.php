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

$string['pluginname'] = 'Motivationsbot';
$string['standard_mail_text_title'] = 'Standard E-Mail-Text';
$string['standard_mail_text_default'] = 'Schau doch mal wieder rein';
$string['standard_mail_text_desc'] = 'Dieser Text wird als Standard-Motivation versendet.';
$string['standard_mail_subject_title'] = 'Standard E-Mail-Betreff';
$string['standard_mail_subject_default'] = 'Schau doch mal wieder rein';
$string['standard_mail_subject_desc'] = 'Dieser Betreff wird für die Standard-Motivation verwendet.';

$string['course_progress_title'] = 'Kursfortschritt';
$string['course_progress_desc'] = 'Erreichter Kursfortschritt in Prozent, nach dem Motivations-Email versendet werden soll';
$string['from_firstname_title'] = 'Absender-Vorname';
$string['from_firstname_desc'] = 'Vorname, des Absenders der Email';
$string['from_firstname_default'] = 'cat';
$string['from_lastname_title'] = 'Absender-Nachname';
$string['from_lastname_desc'] = 'Nachname, des Absenders der Email';
$string['from_lastname_default'] = 'bot';
$string['mail_html_title'] = 'Email html-template';
$string['mail_html_desc'] = 'Html Grundgerüst für alle versendeten Emails. Als Platzhalter für den eigentlichen Inhalt muss {content} eingefügt werden';
$string['max_mails_per_cron_title'] = 'Maximale Emails pro Task';
$string['max_mails_per_cron_desc'] = 'Anzahl der Emails, die maximal pro Ausführung des geplanten Vorgangs (scheduled task) versendet werden';
$string['next_motivation_in_title'] = 'Tage bis zur nächsten Motivations-Email.';
$string['next_motivation_in_default'] = '7';
$string['next_motivation_in_desc'] = 'Mindestdauer bis zur nächsten Motivations-Email pro Teilnehmer/in in Tagen.';
$string['user_course_inactivity_courseid_desc'] = 'Inaktive Kursteilnehmer werden nur in einem speziellen Kurs motiviert, wenn eine ID angegeben wird, die nicht 0 ist.';
$string['user_course_inactivity_courseid_title'] = 'Kurs ID (Kurs-Inaktivität)';
$string['user_course_inactivity_title'] = 'Anzahl inaktiver Tage im Kurs';
$string['user_course_inactivity_desc'] = 'Anzahl inaktiver Tage von Teilnehmer/innen in einem konkreten Kurs, nach deren verstreichen eine Motivations-Email versendet werden soll.<br />0 bedeutet, es werden keine Emails aufgrund dieses Kriteriums versendet.';
$string['user_inactive_title'] = 'Anzahl inaktiver Tage';
$string['user_inactive_desc'] = 'Anzahl inaktiver Tage von Teilnehmer/innen, nach deren verstreichen eine Motivations-Email versendet werden soll.<br />0 bedeutet, es werden keine Emails aufgrund dieses Kriteriums versendet.';

$string['latest_access'] = 'Letzter Zugriff auf das System';
$string['latest_course_access'] = 'Letzter Zugriff auf einen Kurs';
$string['links'] = 'Angeklickte Motivations-Links';
$string['mails'] = 'Versendete emails';
$string['motivation_task'] = 'Teilnehmer per Email motivieren';

// email texts
$string['latest_access_subject_1'] = 'Schau mal wieder vorbei 1';
$string['latest_access_subject_2'] = 'Schau mal wieder vorbei 2';
$string['latest_access_subject_3'] = 'Schau mal wieder vorbei 3';
$string['latest_access_1'] = 'Hallo {$a->fullname}, 

Schön, dass du dich bei uns auf der Plattform angemeldet hast. Schau dich doch mal um, es gibt viel zu entdecken. {$a->link}';
$string['latest_access_2'] = 'Hallo {$a->fullname}, 

Schön, dass du dich bei uns auf der Plattform angemeldet hast. Schau dich doch mal um, es gibt viel zu entdecken. {$a->link}';
$string['latest_access_3'] = 'Hallo {$a->fullname}, 

Schön, dass du dich bei uns auf der Plattform angemeldet hast. Schau dich doch mal um, es gibt viel zu entdecken. {$a->link}';
$string['latest_access_html_1'] = '<h1 class="occontent">Schau mal wieder vorbei</h1>
   <h3 class="occontent">Hallo {$a->fullname},</h3>
   <h3 class="occontent">Sch&ouml;n, dass du dich bei uns auf der Plattform angemeldet hast. Schau dich doch mal um, es gibt viel zu entdecken.</h3>      
   <br /><div class="occontentimg"><img class="ocimage" alt="" width="560" src="https://meinmoodle.de/katzenbild.jpg" /></div><br /><br />
   <p class="occontent">Besuche uns und entdecke spannende Kurse.<br>
	  <div class="ocbtn"><a class="news-button" href="{$a->link}" target="_blank">moodle entdecken</a></div><br /><br />
   </p>';
$string['latest_access_html_2'] = '<h1 class="occontent">Schau mal wieder vorbei</h1>
   <h3 class="occontent">Hallo {$a->fullname},</h3>
   <h3 class="occontent">Sch&ouml;n, dass du dich bei uns auf der Plattform angemeldet hast. Schau dich doch mal um, es gibt viel zu entdecken.</h3>      
   <br /><div class="occontentimg"><img class="ocimage" alt="" width="560" src="https://meinmoodle.de/katzenbild.jpg" /></div><br /><br />
   <p class="occontent">Besuche uns und entdecke spannende Kurse.<br>
	  <div class="ocbtn"><a class="news-button" href="{$a->link}" target="_blank">moodle entdecken</a></div><br /><br />
   </p>';
$string['latest_access_html_3'] = '<h1 class="occontent">Schau mal wieder vorbei</h1>
   <h3 class="occontent">Hallo {$a->fullname},</h3>
   <h3 class="occontent">Sch&ouml;n, dass du dich bei uns auf der Plattform angemeldet hast. Schau dich doch mal um, es gibt viel zu entdecken.</h3>      
   <br /><div class="occontentimg"><img class="ocimage" alt="" width="560" src="https://meinmoodle.de/katzenbild.jpg" /></div><br /><br />
   <p class="occontent">Besuche uns und entdecke spannende Kurse.<br>
	  <div class="ocbtn"><a class="news-button" href="{$a->link}" target="_blank">moodle entdecken</a></div><br /><br />
   </p>';

$string['latest_course_access_subject_1'] = 'Kommst Du lernen?';
$string['latest_course_access_subject_2'] = 'Bist Du schon müde?';
$string['latest_course_access_subject_3'] = 'Kein Bock mehr auf Wissen?';
$string['latest_course_access_1'] = 'Kommst Du lernen?
Hallo {$a->fullname},
Weißt Du, was da im {$a->coursename} alles passiert? Du bist ja noch nicht sooo lange weg, daher komm schnell wieder zurück, der Einstieg ist nach so einer kurzen Zeit ein Klacks – vor allem für Dich!
Komm zurück in den Kurs {$a->coursename}!
{$a->link}';
$string['latest_course_access_2'] = 'Kommst Du lernen?
Hallo {$a->fullname},
Weißt Du, was da im {$a->coursename} alles passiert? Du bist ja noch nicht sooo lange weg, daher komm schnell wieder zurück, der Einstieg ist nach so einer kurzen Zeit ein Klacks – vor allem für Dich!
Komm zurück in den Kurs {$a->coursename}!
{$a->link}';
$string['latest_course_access_3'] = 'Kommst Du lernen?
Hallo {$a->fullname},
Weißt Du, was da im {$a->coursename} alles passiert? Du bist ja noch nicht sooo lange weg, daher komm schnell wieder zurück, der Einstieg ist nach so einer kurzen Zeit ein Klacks – vor allem für Dich!
Komm zurück in den Kurs {$a->coursename}!
{$a->link}';
$string['latest_course_access_html_1'] = '<h1 class="occontent">Kommst Du lernen?</h1>
   <p class="occontent">Hallo {$a->fullname},</p>
   <p class="occontent">Weißt Du, was da im Kurs "{$a->coursename}" alles passiert? Du bist ja noch nicht sooo lange weg, daher komm schnell wieder zurück, der Einstieg ist nach so einer kurzen Zeit ein Klacks – vor allem für Dich!</p>
   <br /><div class="occontentimg"><img class="ocimage" alt="" width="560" src="https://meinmoodle.de/local/ildmotivationbot/images/cat_01.png" /></div><br /><br />
   <p class="occontent">Komm zurück in den Kurs "{$a->coursename}"! Wir freuen uns auf Dich.<br>
	  <div class="ocbtn"><a class="news-button" style="font-family:Arial,Helvetica,sans-serif;" href="{$a->link}" target="_blank">Zum Kurs</a></div><br />
	</p>';
$string['latest_course_access_html_2'] = '<h1 class="occontent">Kommst Du lernen?</h1>
   <p class="occontent">Hallo {$a->fullname},</p>
   <p class="occontent">Weißt Du, was da im Kurs "{$a->coursename}" alles passiert? Du bist ja noch nicht sooo lange weg, daher komm schnell wieder zurück, der Einstieg ist nach so einer kurzen Zeit ein Klacks – vor allem für Dich!</p>
   <br /><div class="occontentimg"><img class="ocimage" alt="" width="560" src="https://meinmoodle.de/local/ildmotivationbot/images/cat_01.png" /></div><br /><br />
   <p class="occontent">Komm zurück in den Kurs "{$a->coursename}"! Wir freuen uns auf Dich.<br>
	  <div class="ocbtn"><a class="news-button" style="font-family:Arial,Helvetica,sans-serif;" href="{$a->link}" target="_blank">Zum Kurs</a></div><br />
	</p>';
$string['latest_course_access_html_3'] = '<h1 class="occontent">Kommst Du lernen?</h1>
   <p class="occontent">Hallo {$a->fullname},</p>
   <p class="occontent">Weißt Du, was da im Kurs "{$a->coursename}" alles passiert? Du bist ja noch nicht sooo lange weg, daher komm schnell wieder zurück, der Einstieg ist nach so einer kurzen Zeit ein Klacks – vor allem für Dich!</p>
   <br /><div class="occontentimg"><img class="ocimage" alt="" width="560" src="https://meinmoodle.de/local/ildmotivationbot/images/cat_01.png" /></div><br /><br />
   <p class="occontent">Komm zurück in den Kurs "{$a->coursename}"! Wir freuen uns auf Dich.<br>
	  <div class="ocbtn"><a class="news-button" style="font-family:Arial,Helvetica,sans-serif;" href="{$a->link}" target="_blank">Zum Kurs</a></div><br />
	</p>';