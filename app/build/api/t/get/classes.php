<?
include_once '../init.php';

print_json(query("
  SELECT
    s.class_id,
    c.name class,
    s.name subject,
    s.id subject_id
  FROM
    subject s
    JOIN
      class c
      ON
        s.class_id = c.id
  WHERE
    s.teacher_id = '".$_SESSION['id']."'
  ORDER BY
    c.name, s.name
"));
