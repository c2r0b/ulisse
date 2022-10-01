<?
include_once '../init.php';

header("Content-Type: application/json");

// get teacher data
$rows['student'] = mysqli_fetch_array(query("
	SELECT
		s.id,
		concat(s.surname,' ',s.name) name,
		t.connected_class,
		t.lang,
		(
			SELECT
				count(*)
			FROM
				student_class
			WHERE
				student_id = '".$_SESSION['id']."'
		) classesCount
	FROM
		student s
	JOIN
		student_settings t
		ON
			s.id = t.student_id
	WHERE
		s.id = '".$_SESSION['id']."'
	LIMIT 1
"));

// get class and subject data
$_SESSION['conn_info'] = $rows['student']['connected_class'];

// get connected class info
if ($_SESSION['conn_info'] != '') {
  $rows['class'] = mysqli_fetch_array(query("
		SELECT
			*
		FROM
			class
		WHERE
			id = '".$_SESSION['conn_info']."'
		LIMIT 1
	"));
}
// print json data
print json_encode($rows);
