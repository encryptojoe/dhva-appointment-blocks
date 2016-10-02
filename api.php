<?php
$result    = array();
$iniConfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/../conf/dhva.ini', true);
$db        = new mysqli($iniConfig['db_va']['db_server'], $iniConfig['db_va']['db_username'], $iniConfig['db_va']['db_password'], $iniConfig['db_va']['db_database']);

if($db->connect_errno > 0){
    header('Content-Type: application/json');
    echo json_encode(array('Error', 'Unable to connect to db'));
    exit;
}

$valid_keys = array(
    'get_staff_type'       => 1,
    'get_event_type'       => 1,
    'get_appointment_type' => 1,
    'get_department_type'  => 1,
    'get_appointments'     => 1,
    'get_department_staff' => 1,
);

foreach( $_POST AS $key => $value ){
    if($key != '' && !is_numeric($key)  || !isset($valid_keys)){
        if(is_numeric($value)){ 
            $$key = $value * 1;        
        } else{
            $$key = $value;
        } 
    }
}

foreach( $_GET AS $key => $value ){
    if($key != '' && !is_numeric($key) && (strstr($valid_keys,$key) || !isset($valid_keys))){
        if(is_numeric($value)) $$key = $value * 1;
        else $$key = $value;
    }
}

function get_type ($table, $db) {
    $a   = [];
    $sql = "SELECT * FROM $table";
    if($all = $db->query($sql)){
        while($row = $all->fetch_assoc()){
          $a[] = $row;
        }
        $all->free();
    }
    return $a;
}

function get_appointments ($date, $db){
    $a = [];
    $sql = "SELECT A.ID,A.DATE_FROM,A.DATE_TO,
    (SELECT NAME FROM APPOINTMENT_TYPE WHERE ID=A.APPOINTMENT_TYPE_ID) AS APPOINTMENT_TYPE,
    (SELECT CONCAT(FAMILY,', ', NAME) FROM STAFF WHERE ID=A.STAFF_ID) AS STAFF,
    (SELECT NAME FROM APPOINTMENT_LOCAL WHERE ID=A.PATIENT_LOCAL_ID) AS PATIENT_LOCAL,
    (SELECT NAME FROM APPOINTMENT_LOCAL WHERE ID=A.PHYSICIAN_LOCAL_ID) AS PHYSICIAN_LOCAL,
    (SELECT NAME FROM REQUEST_TYPE WHERE ID=A.REQUEST_TYPE_ID) AS REQUEST_TYPE,
    (SELECT NAME FROM REMINDER_METHOD WHERE ID=A.REMINDER_METHOD_ID) AS REMINDER_METHOD, WAIT_LIST, COMPLETED
    FROM APPOINTMENT A WHERE DATE(A.DATE_FROM)=DATE($date) ;";

    if($all = $db->query($sql)){
        while($row = $all->fetch_assoc()){
          $a[] = $row;
        }
        $all->free();
    }
    return $a;
}

function get_department_staff ($department, $db){
    $a = [];

    $sql = "SELECT S.NAME, S.FAMILY, (SELECT NAME FROM STAFF_TYPE WHERE ID=S.STAFF_TYPE_ID) AS STAFF_TYPE, (SELECT DEPARTMENT FROM DEPARTMENT WHERE ID=S.DEPARTMENT_ID) AS DEPARTMENT FROM STAFF S WHERE S.DEPARTMENT_ID=$department;";
    if($all = $db->query($sql)){
        while($row = $all->fetch_assoc()){
          $a[] = $row;
        }
        $all->free();
    }
    return $a;
}

switch($func){
    case 'get_staff_type':
        $result = get_type('STAFF_TYPE',$db);
        break;
    case 'get_event_type':
        $result = get_type('EVENT_TYPE',$db);
        break;
    case 'get_appointment_type':
        $result = get_type('APPOINTMENT_TYPE',$db);
        break;
    case 'get_department_type':
        $result = get_type('DEPARTMENT',$db);
        break;
    case 'get_appointments':
        $result = get_appointments($date,$db);
        break;
    case 'get_department_staff':
        $result = get_department_staff($department_id,$db);
        break;
}

### send the result as json
header('Content-Type: application/json');
echo json_encode($result);
$db->close();
exit;
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Empty</title></head><body></body></html>
