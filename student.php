<?php
$metodo = $_SERVER["REQUEST_METHOD"];
include('./class/Student.php');

switch($metodo) {
  $studente = new Student();

  /*---------------------------------------------------------------------------------------------------*/ 
  case 'GET':
    $id = $_GET['id'];
    if (isset($id)){
      $studente = $studente->find($id);
      $json_encoded = json_encode(array('state'=>TRUE, 'student'=>$studente),true);
    } else {
      $students = $studente->all();
      $json_encoded = json_encode(array('state'=>TRUE, 'students'=>$students),true);
    }
    header("Content-Type: application/json");
    echo($json_encoded);
    break;

  /*---------------------------------------------------------------------------------------------------*/
  case 'POST':
    $body = file_get_contents("php://input");
    $js_decoded = json_decode($body, true);
    
    if(isset($js_decoded["_id"])) {
      $bool = $studente->find($js_decoded["_id"]);
      if(!$bool) {
        $bool = $studente->addStudent($js_decoded["_id"], $js_decoded["_name"], $js_decoded["_surname"], $js_decoded["_sidiCode"], $js_decoded["_taxCode"]);
        $esitoInserimento = $bool ? "Studente inserito con successo" :  "Errore nell'inserimento!";
        echo $esitoInserimento;
      } else {
        echo "ID già in utilizzo";
      }
    } else {
      $id =  $studente->getId();
      $bool = $studente->find($js_decoded["_id"]);
      if(!$bool) {
        $$bool = $studente->addStudent($id, $js_decoded["_name"], $js_decoded["_surname"], $js_decoded["_sidiCode"], $js_decoded["_taxCode"]);
        $esitoInserimento = $bool ? "Studente inserito con successo" :  "Errore nell'inserimento!";
      }else{
        echo "ID già in utilizzo";
      }
    }
    break;

  /*---------------------------------------------------------------------------------------------------*/
  case 'DELETE':
    $body = file_get_contents("php://input");
    $js_decoded = json_decode($body, true);
    if(isset($js_decoded["_id"])){
      $bool = $studente->find($js_decoded["_id"]);
      if($bool) {
        //RIMOZIONE COLLEGAMENTI
        $studente->removeColl($js_decoded["_id"]);
        if($bool) {
          $studente->removeStudent($js_decoded["_id"]);
          $esitoRimozione = $bool ? "Studente rimosso con successo" :  "Errore nella rimozione!";
          echo $esitoRimozione;
        } else {
          echo "Errore nella rimozione dei collegamenti";
        }  
      } else {
        echo "ID non trovato";
      }
    }
    else {
      echo "ID mancante, impossibile eseguire l'eliminazione";
    }
    break;

  /*---------------------------------------------------------------------------------------------------*/
  case 'PUT':
    $body = file_get_contents("php://input");
    $js_decoded = json_decode($body, true);
    if(isset($js_decoded["_name"])) {
      $name = $js_decoded["_name"];
    } else {
      $name = "";
    }
    if(isset($js_decoded["_surname"])) {
      $surname = $js_decoded["_surname"];
    } else {
      $surname = "";
    }
    if(isset($js_decoded["_sidiCode"])) {
      $sidiCode = $js_decoded["_sidiCode"];
    } else {
      $sidiCode = "";
    }
    if(isset($js_decoded["_taxCode"])) {
      $taxCode = $js_decoded["_taxCode"];
    } else {
      $taxCode = "";
    }
    if(isset($js_decoded["_id"])) {
      $bool = $studente->find($js_decoded["_id"]);
      if($bool){
        $studente->changeStudent($js_decoded["_id"],$name,$surname,$sidiCode,$taxCode);
        $esitoModifica = $bool ? "Studente modificato con successo" :  "Errore nella modifica!";
        echo $esitoModifica;
      } else {
        echo "ID non trovato";
      }
    } else {
      echo "ID mancante, impossibile eseguire la modifica";
    }
    break;

  /*---------------------------------------------------------------------------------------------------*/
  default:
    break;
}
?>
