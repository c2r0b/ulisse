<?
include_once '../init.php';

$_DATA = get_post_data();

header("Content-Type: application/json");

$retval = (query("
  SELECT
    *
  FROM
    parent p
  JOIN
    parent_info i
    ON
      p.id = i.parent_id
  WHERE
    p.student_id = '".$_DATA[id]."'
  LIMIT 2
"));

// generate array of data to be shown as json
while($r = mysqli_fetch_array($retval)) {
  $rows[] = array(
    'id'                  =>    $r[id],
    'name'                =>    $r[name],
    'surname'             =>    $r[surname],
    'email'               =>    decrypt($r[email]),
    'birthday'            =>    $r[birthday],
    'tax_code'            =>    decrypt($r[tax_code]),
    'telephone'           =>    decrypt($r[telephone]),
    'mobile'              =>    decrypt($r[mobile]),
    'sex'                 =>    $r[sex],
  );
}

// print json data
print json_encode($rows);
