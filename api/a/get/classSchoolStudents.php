<?
include_once '../init.php';

$_DATA = get_post_data();

header("Content-Type: application/json");

$retval = query("
  SELECT
    s.id,
    s.surname,
    s.name
  FROM
    student s
  ORDER BY
    s.surname, s.name
");

while($r = mysqli_fetch_assoc($retval)) {
  if (mysqli_num_rows(query("
    SELECT
      *
    FROM
      student_class
    WHERE
      class_id = '".$_DATA[id]."'
      AND student_id = '".$r[id]."'
    LIMIT 1
  ")) == 0)
    $rows[] = $r;
}

print json_encode($rows);
