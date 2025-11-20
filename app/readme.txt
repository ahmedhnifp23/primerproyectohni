Adoptar la estructura de Laravel ahora te suavizará muchísimo la curva de aprendizaje cuando des el salto al framework. Laravel es famoso por su convención "opinada" (opiniated), es decir, tiene una forma muy clara de organizar las cosas.

Para alinear tu proyecto "Thalassa" con el "estándar Laravel", vamos a ajustar la estructura de carpetas de tu paso 2 (Backend y API).

Ajustes al estilo Laravel
En Laravel, la lógica no está suelta en app/controllers. Se organiza por capas HTTP.

Los Controladores: Se mueven a app/Http/Controllers.

La API: Se crea una subcarpeta específica app/Http/Controllers/Api para separar la lógica que devuelve JSON (para tu admin JS) de la que devuelve HTML (para la web pública).

Los Modelos: Se capitalizan: app/Models (tu script usó models, Laravel usa Models).

Las Rutas: Laravel no define rutas en el index.php. Usa una carpeta routes en la raíz.