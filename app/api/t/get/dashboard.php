<?
include_once '../init.php';

header("Content-Type: application/json");

// classes info
$rows[classes] = mysqli_fetch_array(query("
  SELECT
    count(*) count
  FROM
    subject
  WHERE
    teacher_id = '".$_SESSION[id]."'
"));

// last info ___________________________________________________________________
$rows[news] = mysqli_fetch_assoc(query("
  SELECT
    date,
    title,
    content
  FROM
    report
  ORDER BY
    date DESC
  LIMIT 1
"));

$rows[meeting] = mysqli_fetch_assoc(query("
  SELECT
    m.date,
    m.time,
    (
      SELECT
        count(*)
      FROM
        booking b
      WHERE
        b.meeting_id = m.id
    ) booked
  FROM
    meeting m
  WHERE
    m.teacher_id = '".$_SESSION[id]."'
    AND m.date >= '".date('Y-m-d')."'
  ORDER BY
    m.date DESC
  LIMIT 1
"));

$rows[mark] = mysqli_fetch_assoc(query("
  SELECT
    m.date,
    c.name class,
    s.name subject,
    m.mark
  FROM
    mark m
  JOIN
    subject s
    ON
      m.subject_id = s.id
  JOIN
    class c
    ON
      s.class_id = c.id
  WHERE
    s.teacher_id = '".$_SESSION[id]."'
  ORDER BY
    m.date DESC
  LIMIT 1
"));

// print json data
print json_encode($rows);
