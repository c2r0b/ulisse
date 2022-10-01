<?
include_once '../init.php';

$_DATA = get_post_data();

print_json(
  query("
    SELECT
      s.id,
      concat(s.surname,' ',s.name) name,
      (
    		SELECT
          m.mark
    		FROM
    			mark m
    		WHERE
    			m.student_id = s.id
          AND m.subject_id = '".$_SESSION[conn_info][1]."'
          AND m.test_id = '".$_DATA[test]."'
        LIMIT 1
  	  ) mark
    FROM
      student s
    JOIN
      student_class c
      ON
        s.id = c.student_id
    JOIN
      test t
      ON
        t.id = '".$_DATA[test]."'
        AND t.subject_id = '".$_SESSION[conn_info][1]."'
    WHERE
      c.class_id = '".$_SESSION[conn_info][0]."'
    ORDER BY
      s.surname, s.name
  ")
);
