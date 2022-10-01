<?
include_once '../init.php';

// get list of teachers
print_json(query("
  SELECT
    t.id,
    concat(t.surname,' ',t.name) name
  FROM
    teacher t
  ORDER BY
    t.surname, t.name
"));
