<?
include_once '../init.php';

foreach(($_DATA = get_post_data()) as $desk) {
	execs("
		UPDATE
			desks_disposal
		SET
			x = '".$desk[x]."',
			y = '".$desk[y]."'
		WHERE
			student_id = '".$desk[student_id]."'
			AND class_id = '".$_SESSION[conn_info][0]."'
		LIMIT 1
	");
}
print json_encode($result);
