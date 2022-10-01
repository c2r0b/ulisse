<?
include_once '../init.php';

header("Content-Type: application/json");

// get meetings
$retval = query("
  SELECT
    m.id,
    m.date,
    m.time,
    m.people,
    concat(t.surname,' ',t.name) teacher,
    m.teacher_id,
    (
      SELECT
        count(*)
      FROM
        booking b
      WHERE
        b.meeting_id = m.id
    ) bookedCount
  FROM
    meeting m
    JOIN
      teacher t
      ON
        m.teacher_id = t.id
  ORDER BY
    m.date DESC
");
while($r = mysqli_fetch_assoc($retval)) {
  $r[time] = explode(':',$r[time]);
  $rows[meetings][] = $r;
}

// get teachers list
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

// print json data
print json_encode($rows);
