<?php
/**
 * GAMESERVERWATCHER
 * 	coded by Ben Weidenhofer
 * Published under the open-source GNU GPLv3 licence.
 *
 * GitHub repo: https://github.com/KiloooNL/GameServerWatcher
 *
 * cstrike.php
 *
 * This PHP file is a script for scraping Source Engine (OrangeBox+) server information in real time.
 *
 */

// Server IP / Port
$serverIP = "127.0.0.1";
$serverPort = 27015;

require_once("../../config/config.php");

function sourceQuery($serverIP, $serverPort)
{
    /** TODO: Change variables in this file to reflect the same variables in goldSrcQuery.php
     *        this will make moving between PHP files easier, and make code base neater and more understandable.
     * // Initialize variables
     * var $_arr = array();
     * var $_ip = "";
     * var $_port = 0;
     * var $_isconnected = 0;
     * var $_players = array();
     * var $_rules = array();
     * var $_errorcode = ERROR_NOERROR;
     * var $_seed = "Server status for server (%s:%d)";
     * var $_socket;
     */

    $info = array(
        "name"        => "",
        "map"         => "",
        "dir"         => "",
        "description" => "",
        "version"     => "",);

    $result = "";
    $svStats = "";
    $svCommand = "\377\377\377\377TSource Engine Query\0";
    $svSocket = fsockopen("udp://" . $serverIP, $serverPort, $errno, $errstr, 3) or die(error("Unable to connect to " . $serverIP . ":" . $serverPort . "\n", -1));
    fwrite($svSocket, $svCommand);

    // $junkHead = fread($svSocket, 4);
    $checkStatus = socket_get_status($svSocket);

    if($checkStatus["unread_bytes"] == 0) {
        return 0;
    }

    $loop = true;
    while($loop) {
        $str = fread($svSocket, 1);
        $svStats .= $str;
        $status = socket_get_status(@$svSocket);
        if ($status["unread_bytes"] == 0) {
            $loop = false;
        }
    }
    fclose($svSocket);

    $x = 0;
    while($x <= strlen($svStats)) {
        $x++;
        $result .= substr($svStats, $x, 1);
    }

    // ord(string $string);
    $result = str_split($result);
    $info['network'] = ord($result[0]);
    $i = 1;

    // TODO: Make this into array and to a foreach loop
    while(ord($result[$i]) != "%00") {
        $info['name'] .= $result[$i];
        $i++;
    } $i++;

    while(ord($result[$i]) != "%00") {
        $info['map'] .= $result[$i];
        $i++;
    } $i++;

    while(ord($result[$i]) != "%00") {
        $info['dir'] .= $result[$i];
        $i++;
    } $i++;

    while(ord($result[$i]) != "%00") {
        $info['description'] .= $result[$i];
        $i++;
    } $i++;

    $info['appid']      = ord($result[$i] . $result[($i + 1)]); $i += 2;
    $info['players']    = ord($result[$i]); $i++;
    $info['max']        = ord($result[$i]); $i++;
    $info['bots']       = ord($result[$i]); $i++;
    $info['dedicated']  = ord($result[$i]); $i++;
    $info['os']         = chr(ord($result[$i])); $i++;
    $info['password']   = ord($result[$i]); $i++;
    $info['secure']     = ord($result[$i]); $i++;

    while(ord($result[$i]) != "%00") {
        $info['version'] .= $result[$i];
        $i++;
    }

    return $info;
}

$query = sourceQuery($serverIP, $serverPort);

/** Don't need to display this info.
 * This is for debugging purposes.
 *
    echo "network: "        .$query['network']      ."<br/>";
    echo "name: "           .$query['name']         ."<br/>";
    echo "map: "            .$query['map']          ."<br/>";
    echo "dir: "            .$query['dir']          ."<br/>";
    echo "description: "    .$query['description']  ."<br/>";
    echo "id: "             .$query['appid']        ."<br/>";
    echo "players: "        .$query['players']      ."<br/>";
    echo "max: "            .$query['max']          ."<br/>";
    echo "bots: "           .$query['bots']         ."<br/>";
    echo "dedicated: "      .$query['dedicated']    ."<br/>";
    echo "os: "             .$query['os']           ."<br/>";
    echo "password: "       .$query['password']     ."<br/>";
    echo "secure: "         .$query['secure']       ."<br/>";
    echo "version: "        .$query['version']      ."<br/>";
 *
 */
$svStatus = $query['network'];

if(!$svStatus) {
    $svStatus = "Offline";
} else {
    $svStatus = "Online";
}

$svRank = "1st"; // TODO: Scrape this information from gametracker.rs in the future for a true rank.
?>

<img src="csImg.php?svName="<?php echo $query['name']; ?>"&svAddress="<?php echo $serverIP; ?>"&svPort="<?php echo $serverPort; ?>"&svStatus="<?php echo $svStatus; ?>"&svPlayers="<?php echo $query['players']; ?>"&svMax="<?php echo $query['max']; ?>"&svRank="<?php echo $svRank; ?>"&svMap="<?php echo $query['map']; ?>" class="border" width="560" height="95" align="middle" />