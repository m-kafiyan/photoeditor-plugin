<?php
  $json = json_decode(file_get_contents("php://input"), true);
  // Access your $json['this']
  // then when you are done
  header("Content-type: application/json");
  print json_encode(array(
    "passed" => "back",
    "json" => $json
  ));
?>