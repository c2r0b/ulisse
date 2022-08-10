<?
include_once '../init.php';

print_json(query("
  SELECT
    s.id,
    s.surname,
    s.name
  FROM
    student s
  ORDER BY
    s.surname, s.name
"));
