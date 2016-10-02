<?php
include "tierion.php";

$iniConfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/../conf/dhva.ini', true);
$db        = new mysqli($iniConfig['db_va']['db_server'], $iniConfig['db_va']['db_username'], $iniConfig['db_va']['db_password'], $iniConfig['db_va']['db_database']);


if ($db->connect_errno > 0) {
    header('Content-Type: application/json');
    echo json_encode(array('Error', 'Unable to connect to db'));
    exit;
}


function getPatient($db, $getData) {
	$a = null;
	$sql = "SELECT * FROM PATIENT WHERE ID=" . $getData['id'];
	if ($all = $db->query($sql)) {
		while ($row = $all->fetch_assoc()) {
			$a = $row;
		}
		$all->free();
	}
	return $a;
}

function getApptInfo($db, $patId) {
	$a = [];
	$sql = 'SELECT * FROM APPOINTMENT WHERE PATIENT_LOCAL_ID=' . $patId . ' ORDER BY ID';
	if ($all = $db->query($sql)) {
		while ($row = $all->fetch_assoc()) {
			$a[] = $row;
		}
		$all->free();
	}
	return $a;
}

function getApptDetailedInfo($db, $apptInfos) {
	$a = [];
	foreach ($apptInfos as $apptInfo) {
		$b = [];
		$sql = "SELECT * FROM APPOINTMENT_TRACKING WHERE APPOINTMENT_ID=" . $apptInfo['ID'] . ' ORDER BY ID';
		if ($all = $db->query($sql)) {
			while ($row = $all->fetch_assoc()) {
				$b[] = $row;
			}
			$all->free();
		}
		$a[] = $b;
	}
	return $a;
}


function cmpBCInfos($a, $b) {
	if ($a == $b) {
		return 0;
	}
	return ($a['tracking'] < $b['tracking']) ? -1 : 1;
}

function getBlockchainDetailedInfo($apptInfos) {
	$a = [];
	foreach ($apptInfos as $apptInfo) {
		$bcInfos = getRecordsByApptId($apptInfo['ID']);
		uasort($bcInfos, 'cmpBCInfos');
		$a[] = $bcInfos;
	}
	return $a;
}

$patInfo = getPatient($db, $_GET);
$apptInfo = getApptInfo($db, $patInfo['ID']);
echo '<br />';
echo '<br />';
var_dump($apptInfo);
$apptDetailedInfo = getApptDetailedInfo($db, $apptInfo);
echo '<br />';
echo '<br />';
var_dump($apptDetailedInfo);

//$blockchainDetailedInfo = getBlockchainDetailedInfo($apptInfo);


function writeTableRow($rowName, $mySQLVal, $bcVal) 
{
	if ($mySQLVal != $bcVal) {
		echo '<tr bgcolor="red">';
	} else {
		echo '<tr>';
	}
	echo '<td>' . $rowName . '</td>';
	echo '<td>' . $mySQLVal. '</td>';
	echo '<td>' . $bcVal . '</td></tr>';
}

############### Page ####################
echo '<h1>Detailed info for ' . $patInfo['NAME'] . ' ' . $patInfo['FAMILY'] . '</h1>';


for ($x = 0; $x < count($apptInfo); $x++) {
	echo '<table>';
	echo '<th>Appointment: ' . $apptInfo[$x]['ID'] . '</th>';
	for ($y = 0; $y < count($apptDetailedInfo); $y++) {
		$detInfo = $apptDetailedInfo[$x][$y];
		$bcInfo = $blockchainDetailedInfo[$x][$y];
		
		writeTableRow('Timestamp', $detInfo['EVENT_TS'], $bcInfo['timestamp']);
		writeTableRow('Attending ID', $detInfo['STAFF_ID'],  $bcInfo['attending']);
		writeTableRow('Type', $detInfo['EVENT_ID'], $bcInfo['type']);
		writeTableRow('Row Hash', $detInfo['HASH'],  $bcInfo['rowhash']);
		echo '<tr><td>Hash Matches current?</td>';
		if (getUserTrackingHash($patInfo, $apptDetailedInfo[$x][$y]) != $detInfo['HASH']) {
			echo '<td bgcolor="red">NO</td><td></td></tr>';
		} else {
			echo '<td>NO</td><td></td></tr>';
		}
	}
	echo '</table>';
	echo '<br />';
}
?>

