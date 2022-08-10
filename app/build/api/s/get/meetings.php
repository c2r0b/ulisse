<?
include_once '../init.php';

header("Content-Type: application/json");

// get meetings data
$retval = query("
  SELECT
    m.id,
    concat(t.surname,' ',t.name) teacher,
    m.date,
    m.time,
    m.people,
    (
      SELECT
        count(*)
      FROM
        booking
      WHERE
        meeting_id = m.id
    ) booked,
    IF (
      (
        SELECT
          count(*)
        FROM
          booking
        WHERE
          meeting_id = m.id
          AND student_id = '".$_SESSION[id]."'
      ) > 0,
      1,
      0
    ) alreadyBooked
  FROM
    meeting m
    JOIN
      teacher t
      ON
        m.teacher_id = t.id
  ORDER BY
    m.date DESC, m.time DESC
");

// generate array of data to be shown as json
while($r = mysqli_fetch_assoc($retval)) {

  if ($r[booked] < $r[people]) {
    $rows[] = $r;
  }
}
// print json data
print json_encode($rows);
