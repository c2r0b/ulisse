<?
include_once '../init.php';

print_json(query("
  SELECT
    s.id,
    CONCAT(s.surname,' ',s.name) name,
    i.sex,
    i.birthday,
    (
  		SELECT count(*) FROM absence a
      WHERE
  			a.student_id = s.id
  			AND a.justified = 0
  			AND (a.date < '".date("Y-m-d")."' || (a.type IN (1,2)))
  	) absencesCount
  FROM
    student_class c
  JOIN
    student s
    ON
      c.student_id = s.id
  JOIN
    student_info i
    ON
      c.student_id = i.student_id
  WHERE
    c.class_id = '".$_SESSION[conn_info][0]."'
  ORDER BY
    s.surname DESC, s.name DESC
"));
