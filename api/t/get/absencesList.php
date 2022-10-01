<?
include_once '../init.php';

$_DATA = get_post_data();

// absences
print_json(query("
  SELECT
    date,
    time,
    type
  FROM
    absence
  WHERE
    student_id = '".$_DATA[id]."'
    AND justified = 0
    AND (date < '".date("Y-m-d")."' || (type IN (1,2)))
  ORDER BY
    date DESC, type DESC
"));
