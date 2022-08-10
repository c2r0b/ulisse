<?
include_once '../init.php';

header("Content-Type: application/json");

$retval = query("
  SELECT
    s.id,
    CONCAT(s.surname,' ',s.name) name,
    s.delays,
    i.sex,
    i.birthday,
    (
  		SELECT count(*) FROM absence a
      WHERE
  			a.student_id = s.id
  			AND a.justified = 0
  			AND (a.date < '".date("Y-m-d")."' || (a.type IN (1,2)))
  	) absencesCount
  FROM
    student_class c
  JOIN
    student s
    ON
      c.student_id = s.id
  JOIN
    student_info i
    ON
      c.student_id = i.student_id
  WHERE
    c.class_id = '".$_SESSION[conn_info][0]."'
  ORDER BY
    s.surname DESC, s.name DESC
");

// generate array of data to be shown as json
while($r = mysqli_fetch_array($retval)) {

  // general student data
  $r['isBday'] = (date("m-d", strtotime(decrypt($r[birthday]))) == date("m-d"));

  $r[isAbsent] = (mysqli_num_rows(query("
    SELECT
      *
    FROM
      absence
    WHERE
      student_id = '".$r[id]."'
      AND date = '".date("Y-m-d")."'
      AND (type != 1)
    LIMIT 1
  ")) == 0) ? false : true;

  // save data into array to be parsed as json
  $rows[] = $r;
}
// print json data
print json_encode($rows);
