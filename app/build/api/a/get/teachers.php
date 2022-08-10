<?
include_once '../init.php';

print_json(query("
  SELECT
    s.id,
    s.surname,
    s.name
  FROM
    teacher s
  ORDER BY
    s.surname, s.name
"));
