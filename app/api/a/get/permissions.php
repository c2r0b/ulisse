<?
include_once '../init.php';

$_DATA = get_post_data();

print_json(query("
  SELECT
    admin_id id,
    adminsManagement,
    teachersManagement,
    studentsManagement,
    classesManagement,
    taxesManagement,
    meetingsManagement,
    newsManagement,
    scheduleManagement,
    ballotsManagement
  FROM
    admin_settings
  WHERE
    admin_id = '".$_DATA[id]."'
  LIMIT 1
"));
