<?
include_once '../init.php';

header("Content-Type: application/json");

// get list of classes
$retval = query("
  SELECT
    c.id,
    c.name,
    concat(t.surname,' ',t.name) coordinator
  FROM
    class c
  LEFT OUTER JOIN
    teacher t
    ON
      c.coordinator_id = t.id
  ORDER BY
    c.name
");
while($r = mysqli_fetch_assoc($retval)) $rows[classes][] = $r;

// get list of teachers
$retval = query("
  SELECT
    t.id,
    concat(t.surname,' ',t.name) name
  FROM
    teacher t
  ORDER BY
    t.surname, t.name
");

while($r = mysqli_fetch_assoc($retval)) $rows[teachers][] = $r;

print json_encode($rows);
