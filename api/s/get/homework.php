<?
include_once '../init.php';

print_json(query("
  SELECT
    s.name subject,
    h.text,
    h.date
  FROM
    homework h
  JOIN
    subject s
    ON
      h.subject_id = s.id
  WHERE
    s.class_id = '".$_SESSION[conn_info]."'
  ORDER BY
    h.date DESC
"));
