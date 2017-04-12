<?php

$observers = array (
    array (
        'eventname' => '\core\event\user_loggedin',
        'callback' => 'local_ildmotivationbot_observer::user_loggedin',
    ),
	array (
        'eventname' => '\core\event\course_viewed',
        'callback' => 'local_ildmotivationbot_observer::course_viewed',
    )
);

?>