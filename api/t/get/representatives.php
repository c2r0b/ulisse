<?
include_once '../init.php';

// get parents data
print_json(query("
  (
    SELECT
      CONCAT(p.surname,' ',p.name) name,
      0 type
    FROM
      student_class c
    JOIN
      parent p
      ON
        c.student_id = p.student_id
    WHERE
      c.class_id = '".$_SESSION[conn_info][0]."'
      AND p.representative = 1
    ORDER BY
      p.surname, p.name
  )
  UNION
  (
    SELECT
      CONCAT(s.surname,' ',s.name) name,
      1 type
    FROM
      student_class c
    JOIN
      student s
      ON
        c.student_id = s.id
    WHERE
      c.class_id = '".$_SESSION[conn_info][0]."'
      AND s.representative = 1
    ORDER BY
      s.surname, s.name
  )
"));
