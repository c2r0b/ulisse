<?
include_once '../init.php';

print_json(query("
  SELECT
    s.day,
    s.hour,
    j.name subject,
    s.comment
  FROM
    schedule s
  JOIN
    subject j
    ON
      s.subject_id = j.id
  WHERE
    s.class_id = '".$_SESSION[conn_info]."'
  ORDER BY
    day, hour
  LIMIT 84
"));
