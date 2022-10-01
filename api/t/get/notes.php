<?
include_once '../init.php';

header("Content-Type: application/json");

$retval = query("
  SELECT
    n.id,
    n.date,
    n.type,
    n.teacher_id,
    concat(t.surname,' ',t.name) author,
    n.text,
    IF(t.id = '".$_SESSION[id]."',1,0) authorIsMe
  FROM
    note n
  JOIN
    teacher t
    ON
    n.teacher_id = t.id
  WHERE
    n.class_id = '".$_SESSION[conn_info][0]."'
  ORDER BY
    n.date DESC
");

// generate array of data to be shown as json
while($r = mysqli_fetch_assoc($retval)) {

  // start building students data
  if ($r[type] != 2) {
    // init student names string
    $retval_students = query("
      SELECT
        s.id,
        concat(s.surname,' ',s.name) name
      FROM
        note_student n
      JOIN
        student s
        ON
        n.student_id = s.id
      WHERE
        n.note_id = '".$r[id]."'
      ORDER BY
        s.surname, s.name
    ");
    while($data = mysqli_fetch_assoc($retval_students)) {
      $r[students][] = $data;
    }
  }
  else
    $r[students] = array();

  $rows[] = $r;
}

// print json data
print json_encode($rows);
