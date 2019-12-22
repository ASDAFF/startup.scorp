<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
$old_password = $_REQUEST["old_password"];
$user_id = $_REQUEST["user_id"];

$userData = CUser::GetByID($user_id)->Fetch();
$salt = substr($userData['PASSWORD'], 0, (strlen($userData['PASSWORD']) - 32));
$realPassword = substr($userData['PASSWORD'], -32);
$password = md5($salt.$old_password);
//echo 12313;
if($password != $realPassword){
	echo "false";
}else{
	echo "true";
}

?>