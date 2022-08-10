<?
include_once '../init.php';

print_json(
  query("
    SELECT
      id,
      text,
      date
    FROM
      argument
    WHERE
      subject_id = '".$_SESSION[conn_info][1]."'
    ORDER BY
      date DESC
  ")
);
