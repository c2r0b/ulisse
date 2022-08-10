<?
include_once '../init.php';

$_DATA = get_post_data();

if (isset($_DATA[add])) {
	// get object
	$data = $_DATA[add];
	// check both parameters
	check_param($data[content], $data[title]);
	// convert date to standard format
	$data[date] = get_date($data[date]);
	// update DB
	execs("
		INSERT INTO
			report
			(
				title,
				content,
				date
			)
		VALUES
			(
				'".mysqli_real_escape_string($conn,$data[title])."',
				'".mysqli_real_escape_string($conn,$data[content])."',
				'".$data[date]."'
			)
	");
	// data of the added absence
	$result = array(
		"id" => mysqli_insert_id($conn),
		"date" => $data[date],
		"title" => $data[title],
		"content" => $data[content]
	);
}
elseif (isset($_DATA[edit])) {
	// get object
	$data = $_DATA[edit];

	check_param($data[content], $data[title]);

	// convert date to standard format
	$data[date] = get_date($data[date]);

	execute("
		UPDATE
			report
		SET
			title = '".mysqli_real_escape_string($conn,$data[title])."',
			content = '".mysqli_real_escape_string($conn,$data[content])."',
			date = '".$data[date]."'
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
}
elseif (isset($_DATA[remove])) {
	// get object
	$data = $_DATA[remove];

	execute("
		DELETE FROM
			report
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
}
// return result in json if any
print json_encode($result);
