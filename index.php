<?php
include('./class/Student.php');
$body = file_get_contents("php://input");
$js_decoded = json_decode($body, true);

$studente = new Student();

$studente->_name = $js_decoded["_name"];
$studente->_surname = $js_decoded["_surname"];
$studente->_sidiCode = $js_decoded["_sidiCode"];
$studente->_taxCode = $js_decoded["_taxCode"];

$json_encoded = json_encode(array('state'=>TRUE, 'student'=>$studente),true);
header("Content-Type: application/json");
echo $json_encoded;
?>
