<?
include '../../basic.php';

// if your're not logged in
if (!isset($_SESSION[id])) {
	print 0;
	die;
}
/*
	0 = strudent
	1 = teacher
	2 = admin
*/
print $_SESSION[type];
