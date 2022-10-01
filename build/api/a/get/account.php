<?
include_once '../init.php';

header("Content-Type: application/json");

// get teacher data
$rows['admin'] = mysqli_fetch_array(query("
	SELECT
		a.id,
		concat(a.surname,' ',a.name) name,
		s.adminsManagement,
		s.teachersManagement,
		s.studentsManagement,
		s.studentsManagement,
		s.taxesManagement,
		s.meetingsManagement,
		s.newsManagement,
		s.scheduleManagement,
		s.lang
	FROM
		admin a
	JOIN
		admin_settings s
		ON
			a.id = s.admin_id
	WHERE
		a.id = '".$_SESSION['id']."'
	LIMIT 1
"));

// print json data
print json_encode($rows);
