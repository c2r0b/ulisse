<?
include_once '../init.php';

header("Content-Type: application/json");

$r = mysqli_fetch_assoc(query("
  SELECT
    i.admin_id id,
    i.email,
    i.birthday,
    i.birthplace,
    i.tax_code,
    i.cap,
    i.address,
    i.city_of_residence,
    i.country,
    i.telephone,
    i.mobile,
    i.state_of_birth,
    i.sex,
    s.adminsManagement,
    s.teachersManagement,
    s.studentsManagement,
    s.classesManagement,
    s.taxesManagement,
    s.meetingsManagement,
    s.newsManagement,
    s.scheduleManagement,
    s.ballotsManagement
  FROM
    admin_info i
  JOIN
    admin_settings s
    ON
      i.admin_id = s.admin_id
  WHERE
    i.admin_id = '".$_SESSION[id]."'
  LIMIT 1
"));
// generate array of data to be shown as json
$r['email']               =    decrypt($r[email]);
$r['birthday']            =    $r[birthday];
$r['birthplace']          =    decrypt($r[birthplace]);
$r['tax_code']            =    decrypt($r[tax_code]);
$r['cap']                 =    decrypt($r[cap]);
$r['address']             =    decrypt($r[address]);
$r['city_of_residence']   =    decrypt($r[city_of_residence]);
$r['country']             =    decrypt($r[country]);
$r['telephone']           =    decrypt($r[telephone]);
$r['mobile']              =    decrypt($r[mobile]);
$r['state_of_birth']      =    decrypt($r[state_of_birth]);
$r['sex']                 =    $r[sex];

// print json data
print json_encode($r);
