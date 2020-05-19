<?php
$username = "mysql";
$password = "passwd";
$hostname = "localhost";
$database = "owncloud";

//connection to the database
$dbhandle = mysqli_connect($hostname, $username, $password)
 or die("Unable to connect to MySQL");
//select a database to work with
$selected = mysqli_select_db($dbhandle,$database)
  or die("Could not select owncloud");
//execute the SQL query and return records

$allusers = mysqli_query($dbhandle,"select distinct user_id from oc_accounts where last_login!='0';");
while ($row = mysqli_fetch_array($allusers)) {
	$allus = $row{'user_id'};
	$quota = mysqli_query($dbhandle,"select quota from oc_accounts where user_id = '$allus' ;");
	$actualquotausage = mysqli_query($dbhandle,"select  m.user_id, concat(convert(fc.size *1, integer)) as size from oc_mounts m, oc_filecache fc, oc_storages s where m.mount_point=concat('/', m.user_id, '/') and s.numeric_id=m.storage_id and fc.storage=m.storage_id and fc.path='files' and user_id='$allus';");

	$actquot = mysqli_fetch_array($quota);
	$actusage = mysqli_fetch_array($actualquotausage);


	//quota prolly in GB values, for nagios i want bytes
	if (strpos($actquot{'quota'},'GB') !== false) {
	    list($sizecur, $unit) = explode(" ", $actquot{'quota'});
	    $maxquot = $sizecur * 1024 * 1024 * 1024;
	}
	//user has no custom quota, use OUR system default of 50 GB, you might have to change this
	else {
        	$maxquot = 50 * 1024 * 1024 * 1024;
	}

//print username, current usage in bytes, and the max available space in bytes
echo $allus.",".$actusage{'size'}.",".$maxquot."
";
}

//close the connection
mysqli_close($dbhandle);

?>
