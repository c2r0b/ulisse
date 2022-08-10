<?
include_once '../init.php';

print_json(query("
  SELECT
    r.text,
    concat(t.surname,' ',t.name) teacher,
    r.date
  FROM
    reminder r
  JOIN
    teacher t
    ON
      r.teacher_id = t.id
  WHERE
    r.class_id = '".$_SESSION[conn_info]."'
  ORDER BY
    r.date DESC
"));
