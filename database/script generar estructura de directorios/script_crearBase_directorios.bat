@echo off
setlocal ENABLEDELAYEDEXPANSION

REM ===========================================================
REM  CONFIGURACIÓN: CAMBIA ESTA RUTA A DONDE QUIERAS EL PROYECTO
REM ===========================================================
set "PROJECT_ROOT=C:\xampp\htdocs\primerProyectoHni"

echo Creando estructura de proyecto en: %PROJECT_ROOT%
echo.

REM =======================================
REM  DIRECTORIOS PRINCIPALES
REM =======================================
mkdir "%PROJECT_ROOT%" 2>nul

REM public (raíz web)
mkdir "%PROJECT_ROOT%\public" 2>nul
mkdir "%PROJECT_ROOT%\public\css" 2>nul
mkdir "%PROJECT_ROOT%\public\js" 2>nul
mkdir "%PROJECT_ROOT%\public\img" 2>nul
mkdir "%PROJECT_ROOT%\public\uploads" 2>nul

REM app (MVC)
mkdir "%PROJECT_ROOT%\app" 2>nul
mkdir "%PROJECT_ROOT%\app\core" 2>nul
mkdir "%PROJECT_ROOT%\app\models" 2>nul
mkdir "%PROJECT_ROOT%\app\controllers" 2>nul
mkdir "%PROJECT_ROOT%\app\views" 2>nul

REM subcarpetas de views
mkdir "%PROJECT_ROOT%\app\views\layout" 2>nul
mkdir "%PROJECT_ROOT%\app\views\home" 2>nul
mkdir "%PROJECT_ROOT%\app\views\product" 2>nul
mkdir "%PROJECT_ROOT%\app\views\cart" 2>nul
mkdir "%PROJECT_ROOT%\app\views\user" 2>nul
mkdir "%PROJECT_ROOT%\app\views\order" 2>nul
mkdir "%PROJECT_ROOT%\app\views\auth" 2>nul
mkdir "%PROJECT_ROOT%\app\views\errors" 2>nul

REM panel admin (HTML + CSS + JS)
mkdir "%PROJECT_ROOT%\admin" 2>nul
mkdir "%PROJECT_ROOT%\admin\css" 2>nul
mkdir "%PROJECT_ROOT%\admin\js" 2>nul
mkdir "%PROJECT_ROOT%\admin\img" 2>nul

REM otros directorios útiles
mkdir "%PROJECT_ROOT%\config" 2>nul
mkdir "%PROJECT_ROOT%\database" 2>nul
mkdir "%PROJECT_ROOT%\docs" 2>nul
mkdir "%PROJECT_ROOT%\logs" 2>nul
mkdir "%PROJECT_ROOT%\docker" 2>nul

echo Directorios creados.
echo.

REM =======================================
REM  ARCHIVOS BÁSICOS EN PUBLIC
REM =======================================

echo Creando public\index.php ...
(
  echo ^<?php
  echo // Front controller de Thalassa
  echo // TODO: cargar config, autoload y router
  echo
  echo echo "Thalassa - Front controller listo";
) > "%PROJECT_ROOT%\public\index.php"

REM =======================================
REM  CORE BÁSICO (opcional, pero útil)
REM =======================================

echo Creando clases base en app\core ...

REM Controller.php
(
  echo ^<?php
  echo
  echo class Controller {
  echo     protected function render(string $viewPath, array $data = []) {
  echo         extract($data);
  echo         require __DIR__ . '/../views/' . $viewPath . '.php';
  echo     }
  echo }
) > "%PROJECT_ROOT%\app\core\Controller.php"

REM Model.php
(
  echo ^<?php
  echo
  echo abstract class Model {
  echo     // Clase base para modelos si necesitas lógica común
  echo }
) > "%PROJECT_ROOT%\app\core\Model.php"

REM Database.php
(
  echo ^<?php
  echo
  echo class Database {
  echo     private static ?\PDO $conn = null;
  echo
  echo     public static function getConnection(): \PDO {
  echo         if (self::$conn === null) {
  echo             $host = 'localhost';   // TODO: usar config.php
  echo             $db   = 'thalassa_db';
  echo             $user = 'root';
  echo             $pass = '';
  echo             $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
  echo
  echo             self::$conn = new \PDO($dsn, $user, $pass, [
  echo                 \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
  echo             ]);
  echo         }
  echo         return self::$conn;
  echo     }
  echo }
) > "%PROJECT_ROOT%\app\core\Database.php"

echo Core creado.
echo.

REM =======================================
REM  MODELOS + DAOs
REM  (según tu diagrama: Users, Dishes, Orders, etc.)
REM =======================================

set "MODELS_DIR=%PROJECT_ROOT%\app\models"

call :create_model User
call :create_model Dish
call :create_model Order
call :create_model OrderLine
call :create_model Ingredient
call :create_model Allergen
call :create_model Discount
call :create_model DishIngredient
call :create_model OrderLineIngredient
call :create_model IngredientAllergen
call :create_model Log

echo Modelos y DAOs creados.
echo.

REM =======================================
REM  CONTROLADORES
REM =======================================

set "CTRL_DIR=%PROJECT_ROOT%\app\controllers"

call :create_controller HomeController
call :create_controller ProductController
call :create_controller CartController
call :create_controller OrderController
call :create_controller UserController
call :create_controller AuthController
call :create_controller ApiAdminController

echo Controladores creados.
echo.

REM =======================================
REM  VISTAS BÁSICAS (vacías o con comentario)
REM =======================================

set "VIEWS_DIR=%PROJECT_ROOT%\app\views"

REM layout
(
  echo ^<?php
  echo // Layout principal
  echo // TODO: HTML ^<head^>, ^<body^>, header, footer, etc.
) > "%VIEWS_DIR%\layout\main.php"

REM home/index.php
(
  echo ^<?php
  echo // Vista: Home
  echo // TODO: contenido de la página principal
) > "%VIEWS_DIR%\home\index.php"

REM product/list.php
(
  echo ^<?php
  echo // Vista: listado de platos/productos
) > "%VIEWS_DIR%\product\list.php"

REM cart/index.php
(
  echo ^<?php
  echo // Vista: carrito
) > "%VIEWS_DIR%\cart\index.php"

REM cart/checkout.php
(
  echo ^<?php
  echo // Vista: checkout / finalizar pedido
) > "%VIEWS_DIR%\cart\checkout.php"

REM user/profile.php
(
  echo ^<?php
  echo // Vista: perfil de usuario
) > "%VIEWS_DIR%\user\profile.php"

REM user/orders.php
(
  echo ^<?php
  echo // Vista: historial de pedidos del usuario
) > "%VIEWS_DIR%\user\orders.php"

REM order/show.php
(
  echo ^<?php
  echo // Vista: detalle de un pedido
) > "%VIEWS_DIR%\order\show.php"

REM auth/login.php
(
  echo ^<?php
  echo // Vista: login
) > "%VIEWS_DIR%\auth\login.php"

REM auth/register.php
(
  echo ^<?php
  echo // Vista: registro
) > "%VIEWS_DIR%\auth\register.php"

REM errors/404.php
(
  echo ^<?php
  echo // Vista: página no encontrada
) > "%VIEWS_DIR%\errors\404.php"

echo Vistas creadas.
echo.

REM =======================================
REM  PANEL ADMIN: index.html vacío
REM =======================================

(
  echo ^<!DOCTYPE html^>
  echo ^<html lang="es"^>
  echo ^<head^>
  echo     ^<meta charset="UTF-8" /^>
  echo     ^<title^>Panel Admin Thalassa^</title^>
  echo     ^<link rel="stylesheet" href="css/admin.css" /^>
  echo ^</head^>
  echo ^<body^>
  echo     ^<h1^>Panel de Administración Thalassa^</h1^>
  echo     ^<!-- TODO: estructura del panel, menús y secciones -->^
  echo     ^<script src="js/api.js"^>^</script^>
  echo ^</body^>
  echo ^</html^>
) > "%PROJECT_ROOT%\admin\index.html"

echo Panel admin: index.html creado.
echo.

echo ESTRUCTURA COMPLETA GENERADA CON ÉXITO.
goto :EOF


REM =======================================
REM  SUBRUTINA: CREAR MODELO + DAO
REM =======================================
:create_model
set "NAME=%~1"
set "MODEL_FILE=%MODELS_DIR%\%NAME%.php"
set "DAO_FILE=%MODELS_DIR%\%NAME%DAO.php"

echo Creando modelo %NAME% y %NAME%DAO ...

(
  echo ^<?php
  echo
  echo class %NAME% {
  echo     // TODO: propiedades del modelo %NAME%
  echo     // Ejemplo:
  echo     // private int $id;
  echo }
) > "%MODEL_FILE%"

(
  echo ^<?php
  echo
  echo class %NAME%DAO {
  echo     // TODO: métodos de acceso a datos para %NAME%
  echo     // Usa Database::getConnection() para obtener la conexión PDO
  echo }
) > "%DAO_FILE%"

goto :EOF


REM =======================================
REM  SUBRUTINA: CREAR CONTROLADOR
REM =======================================
:create_controller
set "CNAME=%~1"
set "CFILE=%CTRL_DIR%\%CNAME%.php"

echo Creando controlador %CNAME% ...

(
  echo ^<?php
  echo
  echo class %CNAME% extends Controller {
  echo
  echo     public function index(): void {
  echo         // TODO: lógica principal de %CNAME%
  echo         // $this->render('ruta/vista', []);
  echo     }
  echo }
) > "%CFILE%"

goto :EOF
