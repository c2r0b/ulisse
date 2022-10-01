<?
include_once '../init.php';

$_DATA = get_post_data();

print_json(query("
  SELECT
    s.id,
    concat(s.surname,' ',s.name) name
  FROM
    payment p
  JOIN
    student s
    ON
      p.student_id = s.id
  WHERE
    p.tax_id = '".$_DATA[id]."'
  LIMIT 2
"));
