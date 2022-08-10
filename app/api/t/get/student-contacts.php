<?
include_once '../init.php';

$_DATA = get_post_data();

header("Content-Type: application/json");

$r = mysqli_fetch_array(query("
  SELECT
    i.email,
    i.telephone,
    i.mobile
  FROM
    student_info i
  JOIN
    student_class c
    ON i.student_id = c.student_id
  WHERE
    i.student_id = '".$_DATA[id]."'
    AND c.class_id = '".$_SESSION[conn_info][0]."'
  LIMIT 1
"));

// generate array of data to be shown as json
$rows = array(
  'email'               =>   decrypt($r[email]),
  'telephone'           =>   decrypt($r[telephone]),
  'mobile'              =>   decrypt($r[mobile])
);

// print json data
print json_encode($rows);
