<?php
require_once '../Database/rb.php';
class UserManager 
{	
	function __construct(){
		require_once '../Database/dbsetup.php';
	}
	
	function getUserByName($username){
		$user = R::findOne( 'user', 'username=?', [ $username] );
		return $user;		
	}

	function isAuthenticated($username, $password){
		$user = R::findOne( 'user', 'username=?', [ $username] );
		return password_verify($password, reset($user->xownAuthenticationList)->hash);
	}		
	
	function getUser($id){				
		$user = R::load('user', $id);;
		return $user;
	}
	function getAllUsers(){
		$users = R::find( 'user', 'username!="superuser" ORDER BY id');
		return $users;
	}
	
	function createUser($username, $password){		
		  $user = R::dispense( 'user' );
		  $user->username = $username;
		  $user->reset = true;
		  $user->sharedRoleList=array();
		  $id = R::store( $user );

		  $auth = R::dispense( 'authentication' );
		  $auth->user=$user;
		  $auth->hash=password_hash($password , PASSWORD_DEFAULT);
		  R::store( $auth );
		  
		  return $id;
	}
	
	function changePassword($username, $password, $mustReset){
		$user = self::getUserByName($username);
		$user->reset = $mustReset;
		$user->xownAuthenticationList=array();
		$id = R::store( $user );
	
		$auth = R::dispense( 'authentication' );
		$auth->user=$user;
		$auth->hash=password_hash($password , PASSWORD_DEFAULT);
		R::store( $auth );
	
		return $id;
	}
	
	function deleteUserByName($username){
		$user = self::getUserByName($username);
		R::trash( $user );
	}
	
	//Authorization
	function getAllRoles(){
		return  R::findAll( 'role');
	}
	function getRole($id){
		return R::load('role', $id);
	}
	function createRole($description){
		$role = R::dispense( 'role' );
		$role->description = $description;
		$role->sharedResourceList=array();
		$id = R::store( $role );
		return $id;
	}
	function deleteRole($roleId){
		$role = R::load('role', $roleId);
		R::trash($role);
	}
	function createUserRole($userId, $roleId){
		$user = R::load('user', $userId);
		$user->sharedRoleList[]=R::load('role', $roleId);		
		$id = R::store( $user );
		return $id;
	}
	function deleteUserRole($userId, $roleId){
		$user = R::load('user', $userId);
		unset($user->sharedRoleList[$roleId]);
		$id = R::store( $user );
		return $id;
	}
	function getAllResources(){
		return  R::findAll( 'resource');
	}
	function createResource($order, $uri, $description, $visible){
		$resource = R::dispense( 'resource' );
		$resource->order = $order;
		$resource->uri = $uri;
		$resource->description = $description;
		$resource->visible = $visible;
		$id = R::store( $resource );
		return $id;
	}
	function createRoleResource($roleId, $resourceId){
		$role = R::load('role', $roleId);
		$role->sharedResourceList[]=R::load('resource', $resourceId);
		$id = R::store( $role );
		return $id;
	}
	function deleteRoleResource($roleId,$resourceId){
		$role = R::load('role', $roleId);
		unset($role->sharedResourceList[$resourceId]);
		$id = R::store( $role );
		return $id;
	}
	function getUserAuthorizations($username){
		$sql = 'SELECT DISTINCT resource.description as resource, resource.uri, resource.visible, resource.order FROM user 
				INNER JOIN role_user ON user.id=role_user.user_id 
				INNER JOIN role ON role_user.role_id=role.id 
				INNER JOIN resource_role ON role.id=resource_role.role_id 
				INNER JOIN resource ON resource_role.resource_id=resource.id 
				WHERE user.username = ? 
				ORDER BY resource.order';
		$userAuthorizations = R::getAll($sql, [$username]);
		return $userAuthorizations;
	}
	function getAllAuthorizations(){
		$sql = 'SELECT DISTINCT resource.description as resource, resource.uri, resource.visible, resource.order FROM user
				INNER JOIN role_user ON user.id=role_user.user_id
				INNER JOIN role ON role_user.role_id=role.id
				INNER JOIN resource_role ON role.id=resource_role.role_id
				INNER JOIN resource ON resource_role.resource_id=resource.id
				ORDER BY resource.order';
		return R::getAll($sql);
	}
}

?>
