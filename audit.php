<?php
if (array_key_exists('patid', $_POST)) {
	header('Location: doAudit.php?id=' . $_POST['patid']);
	die();
}


##################


include "tierion.php";

$iniConfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/../conf/dhva.ini', true);
$db        = new mysqli($iniConfig['db_va']['db_server'], $iniConfig['db_va']['db_username'], $iniConfig['db_va']['db_password'], $iniConfig['db_va']['db_database']);


if ($db->connect_errno > 0) {
    header('Content-Type: application/json');
    echo json_encode(array('Error', 'Unable to connect to db'));
    exit;
}


function getPatients($db, $lname) {
	$a = [];
	$sql = "SELECT * FROM PATIENT WHERE UPPER(FAMILY) LIKE '%" . strtoupper($lname) . "%' ORDER BY FAMILY";
	if ($all = $db->query($sql)) {
		while ($row = $all->fetch_assoc()) {
			$a[] = $row;
		}
		$all->free();
	}
	return $a;
}

?>
<form method='POST'>
	<input type="text" name="last">

<?php 
if (array_key_exists('last', $_POST)) {
	echo "<br />";
	echo "<select name='patid'>";
	$pats = getPatients($db, $_POST['last']);
	foreach ($pats as $pat) {
		echo '<option value="' . $pat['ID'] . '">' . $pat['FAMILY'] . ', ' . $pat['NAME'] . '</option>';
	}
	echo "</select>";
	echo "<br />";
}
?>

	<input type="submit" value="Submit">
</form>

