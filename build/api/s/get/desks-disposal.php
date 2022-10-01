<?
include_once '../init.php';

print_json(query("
  SELECT
    d.student_id,
    CONCAT(s.surname,' ',s.name) student_name,
    d.x,
    d.y
  FROM
    desks_disposal d
    JOIN
      student s
      ON
        d.student_id = s.id
  WHERE
    d.class_id = '".$_SESSION[conn_info]."'
"));
