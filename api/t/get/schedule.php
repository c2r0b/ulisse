<?
include_once '../init.php';

print_json(query("
  SELECT
    c.name class,
    s.day,
    s.hour,
    j.name subject,
    s.comment
  FROM
    schedule s
  JOIN
    class c
    ON
      s.class_id = c.id
  JOIN
    subject j
    ON
      s.subject_id = j.id
  WHERE
    s.teacher_id = '".$_SESSION[id]."'
  ORDER BY
    s.day, s.hour
  LIMIT 84
"));
