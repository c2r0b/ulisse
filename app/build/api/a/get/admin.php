<?
include_once '../init.php';

$_DATA = get_post_data();

header("Content-Type: application/json");

$r = mysqli_fetch_array(query("
  SELECT
    *
  FROM
    admin a
  JOIN
    admin_info i
    ON
      a.id = i.admin_id
  WHERE
    a.id = '".$_DATA[id]."'
  LIMIT 1
"));

// generate array of data to be shown as json
$rows = array(
  'id'                  =>    $_DATA[id],
  'email'               =>    decrypt($r[email]),
  'birthday'            =>    $r[birthday],
  'birthplace'          =>    decrypt($r[birthplace]),
  'tax_code'            =>    decrypt($r[tax_code]),
  'cap'                 =>    decrypt($r[cap]),
  'address'             =>    decrypt($r[address]),
  'city_of_residence'   =>    decrypt($r[city_of_residence]),
  'country'             =>    decrypt($r[country]),
  'telephone'           =>    decrypt($r[telephone]),
  'mobile'              =>    decrypt($r[mobile]),
  'state_of_birth'      =>    decrypt($r[state_of_birth]),
  'sex'                 =>    $r[sex]
);

// print json data
print json_encode($rows);
