<?
include_once '../init.php';

$_DATA = get_post_data();

print_json(query("
  SELECT
    concat(s.surname,' ',s.name) name,
    c.name class
  FROM
    booking b
  JOIN
    student s
    ON
      s.id = b.student_id
  JOIN
    student_class sc
    ON
      s.id = sc.student_id
  JOIN
    class c
    ON
      c.id = sc.class_id
  WHERE
    b.meeting_id = '".$_DATA[id]."'
"));
