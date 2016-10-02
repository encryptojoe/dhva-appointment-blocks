<?php

if (!array_key_exists('run', $_POST)) {
?>
<h2>Run Audit</h2>
<form method='POST'>
	<input type="hidden" name="run" value="1">
	<input type="submit" value="Run Audit">
</form>
<?php 
	exit();
}
#####################################


include "tierion.php";
$iniConfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/../conf/dhva.ini', true);
$db        = new mysqli($iniConfig['db_va']['db_server'], $iniConfig['db_va']['db_username'], $iniConfig['db_va']['db_password'], $iniConfig['db_va']['db_database']);


if ($db->connect_errno > 0) {
    header('Content-Type: application/json');
    echo json_encode(array('Error', 'Unable to connect to db'));
    exit;
}

function getApptInfo($db) {
	$a = [];
	$sql = 'SELECT * FROM APPOINTMENT ORDER BY ID';
	if ($all = $db->query($sql)) {
		while ($row = $all->fetch_assoc()) {
			$a[] = $row;
		}
		$all->free();
	}
	return $a;
}

function getPatient($db, $patId) {
	$a = null;
	$sql = "SELECT * FROM PATIENT WHERE ID=" . $patId . " ORDER BY FAMILY";
	if ($all = $db->query($sql)) {
		while ($row = $all->fetch_assoc()) {
			$a = $row;
		}
		$all->free();
	}
	return $a;
}


function getBCApptInfo($id)
{
    $recs = getAllRecords();
    $recsjson = json_decode($recs, True);
    $recsjson = $recsjson['records'];

    $out_arr = null; 
    foreach ($recsjson as $rec) {
        $url = 'https://api.tierion.com/v1/records/' . $rec['id'];
        $recordresponse = doCurl($url);

        $res = json_decode($recordresponse, True);
        $res = $res['data'];
    

        if (array_key_exists('version', $res) && $res['version'] == '0.1') {
                if ($res['appointment'] == $id) {
			$out_arr = $res;
			break;
                }
        }
    }
    return $out_arr;
}

$apptInfos = getApptInfo($db);
foreach ($apptInfos as $apptInfo) {
	$pat = getPatient($db, $apptInfo['PATIENT_ID']);
	$apptInfo['NAME'] = $pat['FAMILY'] . ' ' . $pat['NAME'];
	$apptInfo['BC'] = getBCApptInfo($apptInfo);

	$toHash = $apptInfo['PATIENT_ID'] . 'na'. $apptInfo['ID'] . $apptInfo['STAFF_ID'];
	$apptInfo['CalcHash'] = getHash($toHash);
	$apptInfo['HashMatch'] = True;
	if ($apptInfo['CalcHash'] != $apptInfo['HASH']) {
		$apptInfo['HashMatch'] = False;
	}
}


function writeTableRow($rowName, $mySQLVal, $bcVal) 
{
	if ($mySQLVal != $bcVal) {
		echo '<tr bgcolor="pink">';
	} else {
		echo '<tr>';
	}
	echo '<td>' . $rowName . '</td>';
	echo '<td>' . $mySQLVal. '</td>';
	echo '<td>' . $bcVal . '</td></tr>';
}

#############################
echo "<h2>Audit Results</h2>";
foreach ($apptInfos as $apptInfo) {
	echo '<h3>Appointment for: ' . $apptInfo['NAME'] . ' on ' . $apptInfo['L_TS'] . '</h3>';
	echo "<table style='width:50%'>";

	echo '<th>Value</th><th>Local DB</th><th>Blockchain</th>';
		
	writeTableRow('Timestamp', $apptInfo['L_TS'], $apptInfo['BC']['timestamp']);
	writeTableRow('Attending ID', $apptInfo['STAFF_ID'],  $apptInfo['BC']['attending']);
	writeTableRow('Row Hash', $apptInfo['HASH'],  $apptInfo['BC']['rowhash']);
	echo '<tr><td>Hash Matches Data?</td>';
	if ($apptInfo['HashMatch']) {
		echo "<td>YES</td><td></td></tr>";
	} else {
		echo "<td bgcolor='pink'>NO, data changed</td><td></td></tr>";
	}
	echo '</table>';
	echo '<br />';
}

?>

