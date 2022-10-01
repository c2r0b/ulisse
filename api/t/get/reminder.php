<?
include_once '../init.php';

print_json(query("
  SELECT
    r.id,
    t.id teacher_id,
    concat(t.surname,' ',t.name) author,
    r.text,
    r.date,
    IF(t.id = '".$_SESSION[id]."',1,0) authorIsMe
  FROM
    reminder r
  JOIN
    teacher t
    ON
      r.teacher_id = t.id
  WHERE
    r.class_id = '".$_SESSION[conn_info][0]."'
  ORDER BY
    r.date DESC
"));
