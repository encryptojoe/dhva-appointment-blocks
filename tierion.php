<?php
$iniConfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/../conf/dhva.ini', true);

function doCurl($url, $data=NULL){
    global $cookie_file;
    global $iniConfig;

    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'X-Username: ' . $iniConfig['tierion']['user'],
        'X-Api-Key: ' . $iniConfig['tierion']['apikey'] . '=')
    );
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C)');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);        
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);        
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    if($data!=NULL){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    ob_start(); // prevent any output
    return curl_exec ($ch); // Execute the Curl Command
    ob_end_clean(); // stop preventing output
    curl_close($ch);
}


function getAllRecords()
{
    $url = 'https://api.tierion.com/v1/records?datastoreId=1006';
    return doCurl($url);
}


function getRecordsByUUID($id_uuid='86cd2b1ea22f455d834bb94e0b5f7c77', $ispatient=True)
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
    

        if (array_key_exists('version', $res) && $res['version'] == '1') {
            echo "<br />";
            if ($ispatient) {
                if ($res['patient'] == $id_uuid) {
                    array_push($out_arr, $res);
                }
            } else {
                if ($res['attending'] == $id_uuid) {
                    array_push($out_arr, $res);
                }
            }
        }

    }
    return $out_arr;
}

function getRecordsByApptId($id)
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
    

        if (array_key_exists('version', $res) && $res['version'] == '1') {
                if ($res['appointment'] == $id) {
			array_push($out_arr, $res);
                }
        }

    }
    return $out_arr;
}

function writeRecordToChain($attending_id, $appt_no, $tracking_no, $enc_type, $row_hash, $datestr)
{
    global $iniConfig;
    $dataArr = [ 
                'attending'     => $attending_id,
                'appointment'   => $appt_no,
		'tracking'	=> $tracking_no,
                'type'          => $enc_type,
                'timestamp'     => $datestr,
                'rowhash'       => $row_hash,
                'version'       => '1',
                'datastoreId'   => $iniConfig['tierion']['datastoreId']
                ];
   
    # For Tierion
    $url = 'https://api.tierion.com/v1/records';
    $response = doCurl($url, json_encode($dataArr));

    return $response;
}

function writeRowsToChain($userRow, $trackingRow) 
{
	return writeRecordToChain($trackingRow['STAFF_ID'], $trackingRow['APPOINTMENT_ID'], $trackingRow['ID'], $trackingRow['EVENT_ID'], $trackingRow['HASH'], $trackingRow['EVENT_TS']);


}

function getUserTrackingHash($userRow, $trackingRow) 
{
	$toHash = $userRow['ID'] . $trackingRow['ID'] . $trackingRow['APPOINTMENT_ID'] . $trackingRow['STAFF_ID'];
	return openssl_digest($toHash, 'sha256');
}

?>
