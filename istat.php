<?php

session_start();

$data = $_GET['data'];

switch ( $data ) {
	case 'cpu_hour' :
	
/* 		$db = new PDO ( 'sqlite:/Library/Application\ Support/iStat\ Server/databases/local.db' ); */
		$db = sqlite_open("/Library/Application\ Support/iStat\ Server/databases/local.db", 0666, $error);
		
		$query = "SELECT user, system, time FROM hour_cpuhistory ORDER BY time DESC LIMIT 20";
		$result = sqlite_query($db, $query);

		if (!$db) die($error);

		$finalArray = array (
			'graph' => array (
				'title' => 'CPU History' ,
				'type' => 'bar' ,
				'datasequences' => '' ,
			)
		);
		
		
		foreach ( $result as $row ) {
			$cpu_user[] = $row['user'];
			$cpu_system[] = $row['system'];
		};
		
		
		$finalArray['graph']['datasequences'] = array (
			array (
				'title' => 'User' ,
				'color' => 'red' ,
				'datapoints' => $cpu_user ,
			) ,
			array (
				'title' => 'System' ,
				'color' => 'green' ,
				'datapoints' => $cpu_system ,
			)
		);
		
	break;
}
if (!$result) die("Cannot exectute query");

header ( 'content-type: application/json' );

echo json_encode($finalArray);

?>
