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



function getAllFullRecs() 
{
    $recs = getAllRecords();
    $recsjson = json_decode($recs, True);
    $recsjson = $recsjson['records'];

    $out_arr = []; 
    foreach ($recsjson as $rec) {
        $url = 'https://api.tierion.com/v1/records/' . $rec['id'];
        $recordresponse = doCurl($url);

        $res = json_decode($recordresponse, True);
        $res = $res['data'];
        $out_arr[] = $res;
    }
    return $out_arr;
}

function getBCApptInfo($id, $allrecs)
{
    $out_arr = null; 
    foreach ($allrecs as $res) {
        if (array_key_exists('version', $res) && $res['version'] == '0.1') {
                if ($res['appointment'] == $id) {
			$out_arr = $res;
			break;
                }
        }
    }

    return $out_arr;
}

$allrecs = getAllFullRecs();

$apptInfos = getApptInfo($db);
foreach ($apptInfos as &$apptInfo) {
    if ($apptInfo['ID'] == 1) {
        continue;
    }
	$pat = getPatient($db, $apptInfo['PATIENT_ID']);
	$apptInfo['BC'] = getBCApptInfo($apptInfo['ID'], $allrecs);

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
echo "<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<title>VA Audit</title>
</head>
<body>
    <div class='container-fluid'>
    <div class='row'>
        <div class='col-sm-12'>
            <h2>Audit Results</h2>";
foreach ($apptInfos as $appt) {
    if ($appt['ID'] == 1) {
        continue;
    }
    echo '<h3>Appointment for: USER' . $appt['PATIENT_ID'] . ' on ' . $appt['DATE_FROM'] . '</h3>';
    echo "<table class='table table-striped table-condensed'>";

    echo '<th>Value</th><th>Local DB</th><th>Blockchain</th>';
        
    writeTableRow('Timestamp', $appt['DATE_FROM'], $appt['BC']['timestamp']);
    writeTableRow('Attending ID', $appt['STAFF_ID'],  $appt['BC']['attending']);
    writeTableRow('Row Hash', $appt['HASH'],  $appt['BC']['rowhash']);
    echo '<tr><td>Hash Matches Data?</td>';
    if ($appt['HashMatch']) {
        echo "<td>YES</td><td></td></tr>";
    } else {
        echo "<td class='bg-danger'>NO, data changed</td><td></td></tr>";
    }
    echo '</table>';
    echo '<br /></div></div></div><!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"></body></html>';
}

?>

