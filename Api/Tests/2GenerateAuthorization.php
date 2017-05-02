<?php

include_once '../Models/UserManager.php';

$um = new UserManager();


$index = $um->createResource(0,"index.html","Inicio",true);
$hall = $um->createResource(1,"hall.html","Salon",true);
$credit = $um->createResource(2,"credit.html","Cuentas",true);
$cash = $um->createResource(3,"cash.html","Caja",true);
$products = $um->createResource(4,"products.html","Productos",true);
$reports = $um->createResource(5,"reports.html","Reportes",true);
$users = $um->createResource(6,"users.html","Usuarios",true);
$system = $um->createResource(7,"system.html","Sistema",true);
$profile = $um->createResource(10,"profile.html","Perfil",false);
$login = $um->createResource(20,"login.html","Iniciar Sesion",false);
$notAuthorized= $um->createResource(21,"notAuthorized.html","No Autorizado",false);
$underConstruction = $um->createResource(100,"underConstruction.html","En Construccion",false);

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

$um->createRoleResource($usuario, $profile);

$superuser = $um->getUserByName("superuser");
$um->createUserRole($superuser->id, $usuario);
$um->deleteUserRole($superuser,$usuario);

?>