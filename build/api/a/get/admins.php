<?
include_once '../init.php';

print_json(query("
  SELECT
    a.id,
    a.surname,
    a.name
  FROM
    admin a
  ORDER BY
    a.surname, a.name
"));
