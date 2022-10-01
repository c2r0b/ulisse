<?
include_once '../init.php';

header("Content-Type: application/json");

$rows[info] = mysqli_fetch_assoc(query("
  SELECT
    s.delays,
    i.religion
  FROM
    student_info i
  JOIN
    student s
    ON
      i.student_id = s.id
  WHERE
    i.student_id = '".$_SESSION[id]."'
  LIMIT 1
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
    concat(t.surname,' ',t.name) teacher
  FROM
    booking b
  JOIN
    meeting m
    ON
      b.meeting_id = m.id
  JOIN
    teacher t
    ON
      m.teacher_id = t.id
  WHERE
    b.student_id = '".$_SESSION[id]."'
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
      m.class_id = c.id
  WHERE
    m.student_id = '".$_SESSION[id]."'
  ORDER BY
    m.date DESC
  LIMIT 1
"));

// absences info _______________________________________________________________
$rows[absences] = mysqli_fetch_assoc(query("
  SELECT
    SUM(justified = 1) non_justified,
    SUM(type = 0) days,
    SUM(type = 1) entrances,
    SUM(type = 2) leavings,
    (
      SELECT
        date
      FROM
        absence
      WHERE
        student_id = '".$_SESSION[id]."'
      ORDER BY
        date DESC
      LIMIT 1
    ) last
  FROM
    absence
  WHERE
    student_id = '".$_SESSION[id]."'
"));

// more info ___________________________________________________________________
$booked = mysqli_fetch_row(query("
  SELECT
    count(*)
  FROM
    booking
  WHERE
    student_id = '".$_SESSION[id]."'
"));
$paid = mysqli_fetch_row(query("
  SELECT
    count(*)
  FROM
    payment
  WHERE
    student_id = '".$_SESSION[id]."'
"));
$rows[more] = array(
  'booked'    =>   $booked,
  'paid'      =>   $paid
);

// print json data
print json_encode($rows);
