<?
include_once '../init.php';

// get list of students in class
print_json(query("
  SELECT
    s.id,
    concat(s.surname,' ',s.name) name
  FROM
    student s
    JOIN
      student_class c
      ON
        s.id = c.student_id
  WHERE
    c.class_id = '".$_SESSION[conn_info][0]."'
  ORDER BY
    s.surname, s.name
"));
