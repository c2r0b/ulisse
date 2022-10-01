<?
include_once '../init.php';

$_DATA = get_post_data();

print_json(query("
  SELECT
    s.id,
    s.surname,
    s.name
  FROM
    student s
  JOIN
    student_class c
    ON
      s.id = c.student_id
  WHERE
    c.class_id = '".$_DATA[id]."'
  ORDER BY
    s.surname, s.name
"));
