<?php
$result = array();
$db     = new mysqli('host', 'user', 'pass', 'dbName');

if($db->connect_errno > 0){
    header('Content-Type: application/json');
    echo json_encode(array('Error', 'Unable to connect to db'));
    exit;
}


if(isset($_POST['func'])){
    switch($_POST['func']){
        ### what action do we want to perform
        case 'get_docs':
        $sql = "SELECT docs FROM `docs` WHERE 1";
        if($all = $db->query($sql)){
            while($row = $all->fetch_assoc()){
              ### Add results to array
              $result[] = $row;                              
            }
            $all->free();    
        }
        break;

        case 'new_appt':
        $sql = "INSERT INTO `table` (columns) VALUES ('" . $db->escape_string($_POST['vals']) . "');";
        $result[] = array($db->query($sql), array($db->insert_id, $_POST['vals']));

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