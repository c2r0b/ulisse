<?
include_once '../init.php';

// get meetings data
print_json(query("
  SELECT
    m.id,
    m.date,
    m.time,
    m.people,
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
  WHERE
    m.teacher_id = '".$_SESSION[id]."'
  ORDER BY
    m.date DESC, m.time DESC
"));
