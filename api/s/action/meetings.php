<?
include_once '../init.php';

$_DATA = get_post_data();
// get object
$data = $_DATA[edit];
// check parameters
check_not_empty($data[id]);
// get meeting info
$retval = mysqli_fetch_assoc(query('
  SELECT
    people
  FROM
    meeting
  WHERE
		id = "'.$data[id].'"
  LIMIT 1
'));
// get booked people
$retval_booked = mysqli_num_rows(query('
  SELECT
    *
  FROM
    booking
  WHERE
		meeting = "'.$data['id'].'"
  LIMIT '.$retval[people].'
'));
if ($retval_booked < $retval[people]) {
	// update DB
	execs('
		INSERT INTO
			booking
		VALUES
			(
				"'.$_SESSION[id].'",
				"'.$data[id].'"
			)
	');
	// data of the added booking
	$result = array(
		"id" => $data[id],
		"teacher" => $data[teacher],
		"date" => $data[date],
		"time" => $data[time],
		"people" => $data[people],
		"alreadyBooked" => 1,
		"booked" => $data[booked] + 1
	);
}
else {
	$result = 'error';
}
// return result in json if any
print json_encode($result);
