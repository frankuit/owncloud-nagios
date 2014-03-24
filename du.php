<?php
$username = "mysql";
$password = "passwd";
$hostname = "localhost"; 
$database = "owncloud";
$location = "/mnt/owncloud/owncloud/"; //Change this to the location where userdirs are located on your owncloud linux server

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password)
 or die("Unable to connect to MySQL");
//select a database to work with
$selected = mysql_select_db($database, $dbhandle)
  or die("Could not select owncloud");
//execute the SQL query and return records
$allusers = mysql_query("select distinct userid from oc_preferences where configkey = 'cache_version' ;");
while ($row = mysql_fetch_array($allusers)) {
	$allus = $row{'userid'};

//check on disk to see directory size
    $dirname = $location.$allus;
    $io = popen ( '/usr/bin/du -sb ' . $dirname, 'r' );
    $size = fgets ( $io, 4096);
    $sizecurb = substr ( $size, 0, strpos ( $size, "\t" ) );
    pclose ( $io );

// check if user has higher then default quota
$quota = mysql_query("select configvalue from oc_preferences where configkey = 'quota' and userid = '$allus' ;");
$actquot = mysql_fetch_array($quota);
//quota prolly in GB values, for nagios i want bytes
if (strpos($actquot{'configvalue'},'GB') !== false) {
    list($sizecur, $unit) = explode(" ", $actquot{'configvalue'});
	$maxquot = $sizecur * 1024 * 1024 * 1024;
}
//user has no custom quota, use the system default of 10 GB
else {
	$maxquot = 10 * 1024 * 1024 * 1024;
}

//print username, current usage in bytes, and the max available space in bytes
echo $allus.",".$sizecurb.",".$maxquot."
";

}

//close the connection
mysql_close($dbhandle);





?>
