<?
include_once '../init.php';

print_json(
  query("
    SELECT
      t.id,
      t.title,
      t.arguments,
      t.type,
      t.date,
      IF(t.subject_id = '".$_SESSION[conn_info][1]."',1,0) authorIsMe,
      s.name subject
    FROM
      test t
    JOIN
      subject s
      ON
        t.subject_id = s.id
    WHERE
      s.class_id = '".$_SESSION[conn_info][0]."'
    ORDER BY
      t.date DESC
  ")
);
