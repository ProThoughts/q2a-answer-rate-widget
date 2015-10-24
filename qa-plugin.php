<?php

/*
	Plugin Name: Answer Rate Widget
	Plugin URI: 
	Plugin Description: Displays the answer rate in a widget
	Plugin Version: 0.1
	Plugin Date: 2015-10-20
	Plugin Author:
	Plugin Author URI:
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI:
*/

if ( !defined('QA_VERSION') )
{
	header('Location: ../../');
	exit;
}

// widget
qa_register_plugin_module('widget', 'qa-answer-rate-widget.php', 'qa_answer_rate_widget', 'Answer Rate Widget');

// language file
qa_register_plugin_phrases('qa-answer-rate-widget-lang.php', 'qa_answer_rate_widget_lang');

/*
	Omit PHP closing tag to help avoid accidental output
*/
