<?php
$result    = array();
$iniConfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/../conf/dhva.ini', true);
$db        = new mysqli($iniConfig['db_va']['db_server'], $iniConfig['db_va']['db_username'], $iniConfig['db_va']['db_password'], $iniConfig['db_va']['db_database']);

if($db->connect_errno > 0){
    header('Content-Type: application/json');
    echo json_encode(array('Error', 'Unable to connect to db'));
    exit;
}

if(isset($_POST['func'])){
    switch($_POST['func']){
        ### what action do we want to perform
        case 'pull_departments':
        $sql = "SELECT * FROM `department` WHERE 1";
        if($all = $db->query($sql)){
            while($row = $all->fetch_assoc()){
              ### Add results to array
              $result[] = $row;                              
            }
            $all->free();    
        }
        break;
    }

    ### sent the result as json
    header('Content-Type: application/json');
    echo json_encode($result);
    $db->close();
    exit;
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Empty</title></head><body></body></html>
