<?php

// Database Information:
$dbhost = 'localhost';
$dbuser = 'username';
$dbpass = 'password';
$db = 'database_name';
$mysqli_class = 'mysql'; // Use mysql or mysqli

/*  Limit zone lists to a specified expansion
 *  (i.e. setting $expansion_limit to 2 would cause only Classic and Kunark zones
 *  to appear in zone drop-down lists)
 *    1 = EQ Classic
 *    2 = Kunark
 *    3 = Velious
 *    4 = Luclin
 *    5 = Planes of Power
 *    6 = Legacy of Ykesha
 *    7 = Lost Dungeons of Norrath
 *    8 = Gates of Discord
 *    9 = Omens of War
 *    10 = Dragons of Norrath
 *    11 = Depths of Darkhollow
 *    12 = Prophecy of Ro
 *    13 = The Serpents Spine
 *    14 = The Buried Sea
 *    15 = Secrets of Faydwer
 *    16 = Seeds of Destruction
 *    17 = Underfoot
 *    18 = House of Thule 
 *    19 = Veil of Alaris
 *    20 = Rain of Fear
 *    99 = Other
 */
$expansion_limit = 20;

// How NPCs are listed. 1 = by NPCID (zoneidnumber*1000), 2 = By spawn2 entry
$npc_list = 2;

// Spawngroup list limit. Limits how many spawngroups are displayed as result of a Coord/NPC search. Specific NPC lists are not effected.
$spawngroup_limit = 150; 

// Dont want to have to type the username and password every time you start the editor?
// Set the two variables below to the values you want to be in the form when you start it up.v
// (default login: admin  pw: password)
$login = 'admin';
$password = 'password';

// Log SQL queries:  1 = on, 0 = off
$logging = 0;

// $log_file = path to the file your sql logs will be saved in.
// If you want a single log file, uncomment next line and comment the two monthly log options.
//$log_file = "logs/sql_log.sql";

// Automatically create new logs monthly.
$filetime = date("m-Y");
$log_file = "logs/sql_log_$filetime.sql";

// Log all MySQL queries (If disabled only write entries are logged - recommended.)
$log_all = 0;

// Log all MySQL queries that result in an error.
$log_error = 0;
 
// Enable or disable user logins.
$enable_user_login = 1;

// Enable or disable read only guest mode log in.
$enable_guest_mode = 0;

// Path for quests without trailing slash.
$quest_path = "/home/eqemu/quests";


/* EoC Added Code ~ Akkadius */

// Start the session for this page
session_start();

$IsAuthenticated = FALSE;
// If authenticated as an admin, allow alternate DB credentials/settings
if (isset($_SESSION['SESS_MEMBER_ID']) && (trim($_SESSION['SESS_MEMBER_ID']) != '')) {
    // Used to check if authenticated
    $IsAuthenticated=TRUE;

}

require_once('../../includes/config.php');

if($peq_local_auth){
    $IsAuthenticated = TRUE;
    $db = $dbname;
    # $dbhost = "localhost";
    # $db = "peq";
    # $dbuser = "root";
    # $dbpass = "eocdev";
}

/* Manual DB Login */
if ($_SESSION['dblogin']) {
    $dbhost = $_SESSION['dbip'];
    $db = $_SESSION['dbname'];
    $dbuser = $_SESSION['dbuser'];
    $dbpass = $_SESSION['dbpass'];
    // Used to check if authenticated
    $IsAuthenticated = TRUE;
}
else if ($_COOKIE['dblogin'] == 1) {
    $dbhost = $_COOKIE['dbip'];
    $db = $_COOKIE['dbname'];
    $dbuser = $_COOKIE['dbuser'];
    $dbpass = $_COOKIE['dbpass'];

    $_SESSION['dblogin'] = 1;
    $_SESSION['dbip'] = $_COOKIE['dbip'];
    $_SESSION['dbname'] = $_COOKIE['dbname'];
    $_SESSION['dbuser'] = $_COOKIE['dbuser'];
    $_SESSION['dbpass'] = $_COOKIE['dbpass'];
    // Used to check if authenticated
    $IsAuthenticated = TRUE;
}

$_SESSION['guest'] = 0;
error_reporting(0);  

/* EoC Added Code End ~ Akkadius */

?>