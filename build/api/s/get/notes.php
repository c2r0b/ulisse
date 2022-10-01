<?
include_once '../init.php';

print_json(query("
  (
    SELECT
      n.date,
      n.type,
      concat(t.surname,' ',t.name) teacher,
      n.text
    FROM
      note n
    JOIN
      teacher t
      ON
        n.teacher_id = t.id
    WHERE
      n.class_id = '".$_SESSION[conn_info]."'
      AND n.type = 2
    ORDER BY
      n.date DESC
  ) UNION
  (
    SELECT
      n.date,
      n.type,
      concat(t.surname,' ',t.name) author,
      n.text
    FROM
      note n
    JOIN
      note_student s
      ON
        n.id = s.note_id
    JOIN
      teacher t
      ON
        n.teacher_id = t.id
    WHERE
      n.class_id = '".$_SESSION[conn_info]."'
      AND s.student_id = '".$_SESSION[id]."'
    ORDER BY
      n.date DESC
  )
"));
