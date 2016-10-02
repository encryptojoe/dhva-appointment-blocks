<?php
include_once('tierion.php');
$result = array();

//hac values
$department = ''; $doctor=''; $date=''; $time=''; $staff_id='';

$id = ''; $name = ''; $family = ''; $pid = ''; $vid = ''; $ss = ''; $dob = ''; $department_id=''; $staff_type_id = ''; $patient_id = '';
$iniConfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/../conf/dhva.ini', true);
$db        = new mysqli($iniConfig['db_va']['db_server'], $iniConfig['db_va']['db_username'], $iniConfig['db_va']['db_password'], $iniConfig['db_va']['db_database']);

if($db->connect_errno > 0){
    header('Content-Type: application/json');
    echo json_encode(array('Error', 'Unable to connect to db'));
    exit;
}

foreach( $_POST AS $key => $value ){
    if($key != '') $$key = $value;
}
foreach( $_GET AS $key => $value ){
    if($key != '') $$key = $value;
}

function get_type ($table, &$db){
    $a = [];
    $sql = "SELECT * FROM $table";
    if($all = $db->query($sql)){
        while($row = $all->fetch_assoc()){
          $a[] = $row;                              
        }
        $all->free();    
    }
    return $a;
}

function get_patient ($id = '', $name = '', $family = '', $pid = '', $vid = '', $ss = '', $dob = '', &$db){
    $a = [];
    $w = '';
    
    if ($id!=='')
    {
        $w = "S.ID=$id";  
    }
    if ($pid!=='')
    {
        if ($w != '') $w .= ',';
        $w = "S.PID=$pid ;";    
    }
    if ($vid!=='')
    {
        if ($w != '') $w .= ',';
        $w = "S.VID=$vid ;";    
    }
    if ($ss!=='')
    {
        if ($w != '') $w .= ',';
        $w .= "S.SS=$ss";    
    }
    if ($name != '') 
    {
        if ($w != '') $w .= ',';
        if ($name != '') $w .= "NAME LIKE ('$name%')";
    }
    if ($family != '') 
    {
        if ($w != '') $w .= ',';
        $w = "FAMILY LIKE ('$family%')";
    }

    $sql = "SELECT S.ID,S.NAME,S.FAMILY, PID, VID, SS, DOB,
        (SELECT NAME FROM STAFF_TYPE WHERE ID=S.STAFF_TYPE_ID) AS STAFF_TYPE, 
        (SELECT CONCAT(FAMILY,', ', NAME) FROM STAFF WHERE ID=A.STAFF_ID) AS STAFF, 
        (SELECT NAME FROM DEPARTMENT WHERE ID=S.DEPARTMENT_ID) AS DEPARTMENT
        FROM STAFF S WHERE $w ;";  
        
        
    if($all = $db->query($sql)){
        while($row = $all->fetch_assoc()) $a[] = $row;
        $all->free();    
    }
    return $a;
}

function get_appointments ($date, &$db)
{
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
        while($row = $all->fetch_assoc()) $a[] = $row;                            
        
        $all->free();    
    }
    return $a;
}

function get_staff ($id = '', $name = '', $family = '', $department_id='', $staff_type_id = '', &$db)
{
    $a = [];
    $w = '';
    
    if ($id!=='')
    {
        $w = "S.ID=$id";  
    }
    if ($department_id!=='')
    {
        if ($w != '') $w .= ',';
        $w = "S.DEPARTMENT_ID=$department_id ";    
    }
    
    if ($staff_type_id!=='')
    {
        if ($w != '') $w .= ',';
        $w = "S.STAFF_TYPE_ID=$staff_type_id ";    
    }
    
    if ($name != '') 
    {
        if ($w != '') $w .= ',';
        if ($name != '') $w .= "NAME LIKE ('$name%')";
    }
    if ($family != '') 
    {
        if ($w != '') $w .= ',';
        $w = "FAMILY LIKE ('$family%')";
    }

    $sql = "SELECT S.ID, S.NAME, S.FAMILY, (SELECT NAME FROM STAFF_TYPE WHERE ID=S.STAFF_TYPE_ID) AS STAFF_TYPE, (SELECT DEPARTMENT FROM DEPARTMENT WHERE ID=S.DEPARTMENT_ID) AS DEPARTMENT FROM STAFF S WHERE $w;";    
    
    if($all = $db->query($sql)){
        while($row = $all->fetch_assoc()) $a[] = $row;                              
        
        $all->free();    
    }
    return $a;
}

function get_appointment ($id = '', $patient_id = '', $department_id = '', &$db)
{
    $a = [];
    $w = '';
    
    if ($id!=='')
    {
        $w = "S.ID=$id";  
    }
    if ($patiant_id!=='')
    {
        if ($w != '') $w .= ',';
        $w = "S.PATIENT_ID = $patient_id ";    
    }
    
    if ($department_id!=='')
    {
        if ($w != '') $w .= ',';
        $w = "S.DEPARTMENT_ID = $department_id ";    
    }
    
    $sql = "SELECT S.ID, S.PATIENT_ID, S.DATE_FROM,S.DATE_TO,S.APPOINTMENT_TYPE_ID, (SELECT NAME FROM APPOINTMENT_TYPE WHERE ID=S.APPOINTMENT_TYPE) AS APPOINTMENT_TYPE, STAFF_ID, (SELECT CONCAT(FAMILY,', ', NAME) FROM STAFF WHERE ID=S.STAFF_ID) AS STAFF, (SELECT NAME FROM STAFF_TYPE WHERE ID=S.STAFF_TYPE_ID) AS STAFF_TYPE, STAFF_TYPE_ID, PATIENT_LOCAL_ID, (SELECT NAME FROM APPOINTMENT_LOCAL WHERE ID=S.PATIENT_LOCAL_ID) AS PATIENT_LOCAL, PHYSICIAN_LOCAL_ID, (SELECT NAME FROM APPOINTMENT_LOCAL WHERE ID=S.PHYSICIAN_LOCAL_ID) AS PHYSICIAN_LOCAL,REQUEST_TYPE_ID, (SELECT NAME FROM REQUEST_TYPE WHERE ID=S.REQUEST_TYPE_ID) AS REQUEST_TYPE ,REMINDER_METHOD_ID, (SELECT NAME FROM REMINDER_METHOD WHERE ID=S.REMINDER_METHOD_ID) AS REMINDER_METHOD ,WAIT_LIST,COMPLETED,DEPARTMENT_ID, (SELECT DEPARTMENT FROM DEPARTMENT WHERE ID=S.DEPARTMENT_ID) AS DEPARTMENT FROM APPOINTMENT S WHERE $w;";    
    
    if($all = $db->query($sql)){
        while($row = $all->fetch_assoc()) $a[] = $row;                              
        
        $all->free();    
    }
    return $a;
}

//hacked functions
function insert_appointment($department_id='',$staff_id='',$date='',$time='',$patient_id='',$staff_id='',&$db)
{
    $date_from = "$date $time";
    
    $sql = "INSERT INTO APPOINTMENT (DEPARTMENT_ID,STAFF_ID,DATE_FROM,PATIENT_ID,STAFF_ID) VALUES($department_id,$staff_id,$date_from,$patient_id,$staff_id);";
    $db->query($sql);
    $id = $db->insert_id;
    
    $hash = getHash($patient_id . 'na'. $id . $staff_id);
    
    
    $sql = "UPDATE APPOINTMENT SET HASH='$hash' WHERE ID=$id;";
    $db->query($sql);
    
    
    $r = writeRecordToChainHack($staff_id, $id, $hash, $date_from);
    
    return $id;
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
    case 'get_staff':
        $result = get_staff($id, $name, $family, $staff_type_id, $department_id, $db);
        break;
    case 'get_patient':
        $result = get_patient($id, $name, $family, $pid, $vid, $ss, $dob, $db);
        break;
    case 'get_appointment':
        $result = get_apointment($id, $patient_id, $department_id, $db);
        break;
    case 'insert_appointment':
        $result = insert_appointment($department, $doctor, $date, $time, $staff_id, $db);
        break;
}
### sent the result as json
header('Content-Type: application/json');
echo json_encode($result);
$db->close();
