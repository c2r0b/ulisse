<?
include_once '../init.php';

header("Content-Type: application/json");

$retval = query("
  SELECT
    s.id,
    concat(s.surname,' ',s.name) name
  FROM
    student_class c
  JOIN
    student s
    ON
      c.student_id = s.id
  WHERE
    c.class_id = '".$_SESSION[conn_info][0]."'
  ORDER BY
    s.surname DESC, s.name DESC
");

// generate array of data to be shown as json
while($r = mysqli_fetch_array($retval)) {
  // marks
  $retval_marks = query("
    SELECT
      m.id,
      (
        CASE
          WHEN
            m.test_id IS NOT NULL
          THEN
            t.date
          ELSE
            m.date
          END
      ) date,
      m.mark,
      (
        CASE
          WHEN
            m.test_id IS NOT NULL
          THEN
            t.type
          ELSE
            m.type
        END
      ) type,
      (
        CASE
          WHEN
            m.test_id IS NOT NULL
          THEN
            t.title
          ELSE
            m.text
          END
      ) text,
      IF (m.test_id IS NOT NULL,1,0) fromTest
    FROM
      mark m
    LEFT OUTER JOIN
      test t
      ON
        t.id = m.test_id
    WHERE
      m.student_id = '".$r[id]."'
      AND m.subject_id = '".$_SESSION[conn_info][1]."'
    ORDER BY
      m.date DESC
  ");
  while($mark = mysqli_fetch_assoc($retval_marks)) $r['marks'][] = $mark;

  // save data into array to be parsed as json
  $rows[] = $r;
}
// print json data
print json_encode($rows);
