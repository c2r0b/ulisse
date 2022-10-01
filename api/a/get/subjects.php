<?
include_once '../init.php';

print_json(query("
  SELECT
    c.name class,
    s.name subject,
    concat(t.surname,' ',t.name) teacher
  FROM
    subject s
  JOIN
    teacher t
    ON
      s.teacher_id = t.id
  JOIN
    class c
    ON
      s.class_id = c.id
  ORDER BY
    c.name, s.name
"));
