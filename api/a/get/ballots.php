<?
include_once '../init.php';

// get list of classes
print_json(query("
  SELECT
    *
  FROM
    ballot
  ORDER BY
    name
"));
