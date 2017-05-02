<?php

include_once '../Models/UserManager.php';

$um = new UserManager();


$index = $um->createResource(1,"","Inicio",false);

$hall = $um->createResource(11,"hall.html","Salon",true);
$credit = $um->createResource(12,"credit.html","Cuentas",true);
$cash = $um->createResource(13,"cash.html","Caja",true);
$products = $um->createResource(14,"products.html","Productos",true);
$reports = $um->createResource(15,"reports.html","Reportes",true);
$users = $um->createResource(16,"users.html","Usuarios",true);
$system = $um->createResource(17,"system.html","Sistema",true);


$profile = $um->createResource(21,"profile.html","Perfil",false);
$login = $um->createResource(22,"login.html","Iniciar Sesion",false);
$notAuthorized= $um->createResource(23,"notAuthorized.html","No Autorizado",false);
$underConstruction = $um->createResource(24,"underConstruction.html","En Construccion",false);

$administrador = $um->createRole("Administrador");
$encargado = $um->createRole("Encargado");
$usuario = $um->createRole("Usuario");


$um->createRoleResource($administrador, $hall);
$um->createRoleResource($administrador, $credit);
$um->createRoleResource($administrador, $cash);
$um->createRoleResource($administrador, $products);
$um->createRoleResource($administrador, $reports);
$um->createRoleResource($administrador, $users);
$um->createRoleResource($administrador, $system);

$um->createRoleResource($encargado, $hall);
$um->createRoleResource($encargado, $credit);
$um->createRoleResource($encargado, $cash);

$um->createRoleResource($usuario, $index);
$um->createRoleResource($usuario, $profile);

$superuser = $um->getUserByName("superuser");
$um->createUserRole($superuser->id, $usuario);
$um->deleteUserRole($superuser,$usuario);

?>