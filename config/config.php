<?php
/**
 * GAMESERVERWATCHER
 * 	coded by Ben Weidenhofer
 * Published under the open-source GNU GPLv3 licence.
 *
 * GitHub repo: https://github.com/KiloooNL/GameServerWatcher
 *
 * config.php
 */

########################################
# Global IP for the following:
#   : All game servers (it is recommended game servers are on a different IP to reduce lag under heavy load.
#   : FTP
########################################
define("GLOBAL_IP", "127.0.0.1");

########################################
# Database settings
#	: MySQL + Site-Wide DB settings.
#	: change $db_ip if different from localhost.
########################################
define("DB_IP", "localhost");
define("DB_USER", "root");
define("DB_PASS", "password");

########################################
# Server directories
#	: include trailing slash
#	: leave blank for default
#
#   STATS_DIR: the directory you will want to display server statistics
#   CONFIG_DIR: the directory for this file.
########################################
define("STATS_DIR", "/stats/");
define("CONFIG_DIR","/config");

/************************************************
 *  WARNING!
 *
 * DO NOT EDIT CODE BELOW THIS LINE
 * UNLESS YOU KNOW WHAT YOU'RE DOING
 ***********************************************/


/************************
 * Page load time diagnostic function
 * not used right now, but implemented for future use
 *
 */
function pageLoadTime() {
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $start = time; // start
}
