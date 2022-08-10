<?
include_once '../init.php';

$_DATA = get_post_data();

if (isset($_DATA[add])) {
	// get object
	$data = $_DATA[add];
	// update DB
	execs("
		INSERT INTO
			tax
			(
				amount,
				description
			)
		VALUES
			(
				'".mysqli_real_escape_string($conn,$data[amount])."',
				'".mysqli_real_escape_string($conn,$data[description])."'
			)
	");
	// data of the added absence
	$result = array(
		"id" => mysqli_insert_id($conn),
		"amount" => $data[amount],
		"description" => $data[description]
	);
}
elseif (isset($_DATA[edit])) {
	// get object
	$data = $_DATA[edit];

	execute("
		UPDATE
			tax
		SET
			amount = '".mysqli_real_escape_string($conn,$data[amount])."',
			description = '".mysqli_real_escape_string($conn,$data[description])."'
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
}
elseif (isset($_DATA[payments])) {
	// get object
	$data = $_DATA[payments];

	// remove old payments data
	execs("DELETE FROM payment WHERE tax_id = '".$_DATA[tax_id]."'");

	// add new payments data
	foreach($data as $student) {
		execs("
			INSERT INTO
				payment
				(
					tax_id,
					student_id
				)
			VALUES
				(
					'".$_DATA[tax_id]."',
					'".$student[id]."'
				)
		");
	}
}
elseif (isset($_DATA[remove])) {
	// get object
	$data = $_DATA[remove];

	execute("
		DELETE FROM
			tax
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
}
// return result in json if any
print json_encode($result);
