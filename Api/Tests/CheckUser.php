<?php
include_once '../Models/UserManager.php';
if(isset($_REQUEST['username']))
{
	$username= $_REQUEST['username'];
}
else{
	$username="superuser";
}
$um = new UserManager();
$user = $um->getUserByName($username);
echo "<p>";
echo json_encode($user);
echo "<p>";
echo json_encode(reset($user->xownAuthenticationList)->hash);
echo "<p>";
echo json_encode($user);
echo "<p>";
echo json_encode($user->sharedRoleList);
echo "<p>";
echo json_encode($user);
foreach ($user->sharedRoleList as &$role){
	echo "<p>";
	echo json_encode($role->sharedResourceList);
}
echo "<p>";
echo json_encode($user);
?>