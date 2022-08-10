<?
include_once '../init.php';

print_json(query("
  SELECT
    c.id,
    c.name
  FROM
    student_class s
    JOIN
      class c
      ON
        s.class_id = c.id
  WHERE
    s.student_id = '".$_SESSION['id']."'
  ORDER BY
    c.name
"));
