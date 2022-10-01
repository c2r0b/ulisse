<?
include_once '../init.php';

print_json(query("
  SELECT
    date,
    time
  FROM
    signature
  WHERE
    subject_id = '".$_SESSION[conn_info][1]."'
    AND teacher_id = '".$_SESSION[id]."'
  ORDER BY
    date DESC, time
"));
