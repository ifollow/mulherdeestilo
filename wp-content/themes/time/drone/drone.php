<?php
 if (!class_exists('Drone')): class Drone { const VERSION = '4.1.1'; const DIRECTORY = 'drone'; } require TEMPLATEPATH.'/'.Drone::DIRECTORY.'/dronefunc.class.php'; require TEMPLATEPATH.'/'.Drone::DIRECTORY.'/dronehtml.class.php'; require TEMPLATEPATH.'/'.Drone::DIRECTORY.'/droneoptions.class.php'; require TEMPLATEPATH.'/'.Drone::DIRECTORY.'/dronetheme.class.php'; require TEMPLATEPATH.'/'.Drone::DIRECTORY.'/dronewidget.class.php'; endif;