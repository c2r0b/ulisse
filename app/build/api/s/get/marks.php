<?
include_once '../init.php';

header("Content-Type: application/json");

$retval = query("
  SELECT
    id,
    name
  FROM
    subject
  WHERE
    class_id = '".$_SESSION[conn_info]."'
  ORDER BY
    name
");

// generate array of data to be shown as json
while($r = mysqli_fetch_assoc($retval)) {

  $abb = '';
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
      ) text
    FROM
      mark m
    LEFT OUTER JOIN
      test t
      ON
        t.id = m.test_id
    WHERE
      m.student_id = '".$_SESSION[id]."'
      AND m.subject_id = '".$r[id]."'
    ORDER BY
      m.date DESC
  ");

  $n_marks = mysqli_num_rows($retval_marks);

  while($mark = mysqli_fetch_assoc($retval_marks)) $abb[] = $mark;

  $r[marks] = $abb;

  // save data into array to be parsed as json
  $rows[] = $r;
}
// print json data
print json_encode($rows);
