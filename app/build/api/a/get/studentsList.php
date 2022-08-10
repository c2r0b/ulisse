<?
include_once '../init.php';

// get list of students
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
  ORDER BY
    s.surname, s.name
"));
