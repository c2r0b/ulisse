<?
include_once '../init.php';

print_json(query("
  SELECT
    s.name subject,
    a.text,
    a.date
  FROM
    argument a
  JOIN
    subject s
    ON
      a.subject_id = s.id
  WHERE
    s.class_id = '".$_SESSION[conn_info]."'
  ORDER BY
    a.date DESC
"));
