<?
include_once '../init.php';

print_json(query("
    SELECT
      id,
      amount,
      description
    FROM
      tax
"));
