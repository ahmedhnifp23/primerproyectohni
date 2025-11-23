<?php
// Front controller de Thalassa
// Todo cargar config, autoload y router
//ECHO esta desactivado.

//Imports there
require_once __DIR__ . "/../app/controllers/DishController.php";

//Variable with the instance of the dish controller.
$controller = new DishController();

//Variables with the controller and action obtained with method GET.
$controller = $_GET['controller'] ?? 'dish';//CAMBIAR LUEGO POR HOME
$action = $_GET['action'] ?? 'index';

