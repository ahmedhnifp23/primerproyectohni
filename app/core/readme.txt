Aquí va la “maquinaria interna” que usan todos:

Controller.php → clase base de la que heredan todos los controladores.

Database.php → crea y devuelve la conexión PDO.

Session.php → helpers para trabajar con la sesión.

Auth.php → helpers de login/logout/usuario actual.

Router.php (si lo usas) → para traducir URLs a controladores/acciones.