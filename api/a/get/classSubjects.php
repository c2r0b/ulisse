<?
include_once '../init.php';

$_DATA = get_post_data();

print_json(query("
  SELECT
    s.id,
    concat(t.surname,' ',t.name) teacher,
    t.id teacher_id,
    s.name
  FROM
    teacher t
  JOIN
    subject s
    ON
      t.id = s.teacher_id
  WHERE
    s.class_id = '".$_DATA[id]."'
  ORDER BY
    t.surname, t.name, s.name
"));
