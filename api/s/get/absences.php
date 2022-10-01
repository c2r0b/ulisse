<?
include_once '../init.php';

header("Content-Type: application/json");

$retval = query("
  SELECT
    date,
    time,
    justified,
    type,
    justification
  FROM
    absence
  WHERE
    student_id = '".$_SESSION[id]."'
  ORDER BY
    date DESC
");
// make changes on retrived data
while($r = mysqli_fetch_assoc($retval)) {
  // remove seconds from time string
  $r[time] = substr($r[time],0,-3);
  // set data in output
  $rows[] = $r;
}
// print JSON output
print json_encode($rows);
