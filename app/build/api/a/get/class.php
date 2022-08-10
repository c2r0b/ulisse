<?
include_once '../init.php';

$_DATA = get_post_data();

header("Content-Type: application/json");

$rows = mysqli_fetch_assoc(query("
  SELECT
    id,
    name,
    coordinator_id
  FROM
    class
  WHERE
    id = '".$_DATA[id]."'
  LIMIT 1
"));

// generate array of students
$retval_students = query("
  SELECT
    s.id,
    concat(s.surname,' ',s.name) name
  FROM
    student s
  JOIN
    student_class c
    ON
      s.id = c.student_id
  WHERE
    c.class_id = '".$_DATA[id]."'
");
while($r = mysqli_fetch_assoc($retval_students)) $rows[students][] = $r;

// generate array of teachers and subjects
$retval_teachers = query("
  SELECT
    t.id,
    concat(t.surname,' ',t.name) name,
    s.name 'option'
  FROM
    subject s
  JOIN
    teacher t
    ON
      t.id = s.teacher_id
  WHERE
    s.class_id = '".$_DATA[id]."'
");
while($r = mysqli_fetch_assoc($retval_teachers)) $rows[teachers][] = $r;

// print json data
print json_encode($rows);
