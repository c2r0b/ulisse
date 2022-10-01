<?
include_once '../init.php';

header("Content-Type: application/json");

// get teacher data
$rows['teacher'] = mysqli_fetch_array(query("
	SELECT
		name,
		surname,
		lang,
		connected_class
	FROM
		teacher t
	JOIN
		teacher_settings s
		ON
			t.id = s.teacher_id
	WHERE
		id = '".$_SESSION['id']."'
	LIMIT 1
"));

$connectedClass = $rows['teacher']['connected_class'];

// get class and subject data
$_SESSION['conn_info'] = explode("$//$", $connectedClass);

// get connected class info
if ($connectedClass != '') {
  $rows['class'] = mysqli_fetch_array(query("
		SELECT
			*
		FROM
			class
		WHERE
			id = '".$_SESSION['conn_info'][0]."'
		LIMIT 1
	"));
  $rows['subject'] = mysqli_fetch_array(query("
		SELECT
			*
		FROM
			subject
		WHERE
			id = '".$_SESSION['conn_info'][1]."'
		LIMIT 1
	"));
}
// print json data
print json_encode($rows);
