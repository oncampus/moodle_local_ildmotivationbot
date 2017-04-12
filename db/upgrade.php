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
 * Upgrade code for ildmotivationbot.
 *
 * @package    local_ildmotivationbot
 * @copyright  2017 Jan Rieger Fachhochschule Lübeck
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade the local_ildcourseinfo plugins.
 *
 * @param int $oldversion The old version of the local_ildcourseinfo module
 * @return bool
 */
function xmldb_local_ildmotivationbot_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();
	
	if ($oldversion < 2017021300) {

        // Define table local_ildmotivationbot to be created.
        $table = new xmldb_table('local_ildmotivationbot');

        // Adding fields to table local_ildmotivationbot.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('type', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('value', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        $table->add_field('logtext', XMLDB_TYPE_CHAR, '200', null, null, null, null);
        $table->add_field('time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('nextlogin', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table local_ildmotivationbot.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for local_ildmotivationbot.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Ildmotivationbot savepoint reached.
        upgrade_plugin_savepoint(true, 2017021300, 'local', 'ildmotivationbot');
    }
	
	if ($oldversion < 2017021400) {

        // Define field level to be added to local_ildmotivationbot.
        $table = new xmldb_table('local_ildmotivationbot');
        $field = new xmldb_field('level', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1', 'logtext');

        // Conditionally launch add field level.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Ildmotivationbot savepoint reached.
        upgrade_plugin_savepoint(true, 2017021400, 'local', 'ildmotivationbot');
    }
	
	if ($oldversion < 2017022400) {

        // Define table local_ildmotivationbot_click to be created.
        $table = new xmldb_table('local_ildmotivationbot_click');

        // Adding fields to table local_ildmotivationbot_click.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('ildmotivationbotid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_ildmotivationbot_click.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for local_ildmotivationbot_click.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Ildmotivationbot savepoint reached.
        upgrade_plugin_savepoint(true, 2017022400, 'local', 'ildmotivationbot');
    }

    return true;
}