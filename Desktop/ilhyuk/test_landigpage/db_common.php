<?php

function sendRelayServer($data) {
  $buff = json_encode($data);
  $fp = fsockopen('unix:///tmp/coplemsg-relay.sock', -1);

  fwrite($fp, $buff);
  fclose($fp);
}

// ?? 이것에 대한 설명
// ?? 더 간단하게 불러낼 수는 없는가?
function fetchAssocStatement($stmt)
{
  if($stmt->num_rows>0)
  {
    $result = array();
    $md = $stmt->result_metadata();
    $params = array();
    while($field = $md->fetch_field()) {
      $params[] = &$result[$field->name];
    }
    call_user_func_array(array($stmt, 'bind_result'), $params);
    if($stmt->fetch())
    
      return $result;
  }
  return null;
}

function querySelect($query) {
  global $cm_data_mysqli;

  $sql = $cm_data_mysqli->prepare(
    $query
  );
  if (!$sql) exit($cm_data_mysqli->error);
  $sql->execute();
  $sql->store_result();
  $result = array();

  while($row = fetchAssocStatement($sql)) {
    $result[] = $row;
  }
  $sql->close();

  if ($cm_data_mysqli->error) exit($cm_data_mysqli->error);

  return $result;
}

function queryUpdate($query) {
  global $cm_data_mysqli;

  $sql = $cm_data_mysqli->prepare(
    $query
  );
  if (!$sql) exit($cm_data_mysqli->error);
  $sql->execute();
  $sql->store_result();
  $result = array();

  while($row = fetchAssocStatement($sql)) {
    $result[] = $row;
  }
  $sql->close();

  if ($cm_data_mysqli->error) exit($cm_data_mysqli->error);

  return $result;
}


function queryInsert($query) {
  global $cm_data_mysqli;

  $sql = $cm_data_mysqli->prepare(
    $query
  );
  if (!$sql) exit($cm_data_mysqli->error);
  $sql->execute();
  $insert_id = $sql->insert_id;
  $sql->store_result();
  $result = array();

  while($row = fetchAssocStatement($sql)) {
    $result[] = $row;
  }
  $sql->close();

  if ($cm_data_mysqli->error) exit($cm_data_mysqli->error);

  return $insert_id;
}


function reloadParent() {
  echo '<script>parent.location.reload();</script>';
}

session_start();

header('Content-Type: text/html; charset=utf-8'); 

$host = 'localhost';
$user = 'root';
$pw = 'dkakwhs12';
$dbName = 'landing-list';
$cm_data_mysqli = new mysqli($host, $user, $pw, $dbName);
$cm_data_mysqli->set_charset("utf8");


