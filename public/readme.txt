public/ — Lo que ve el servidor (zona pública)

Es la raíz web (DocumentRoot).

Aquí van:

index.php → tu front controller:

inicia la app,

carga config/config.php,

decide qué controlador y acción ejecutar,

llama a los controladores de app/Http/Controllers.

css/, js/, img/ → recursos de la web pública (no del admin).

uploads/ → imágenes subidas (por ejemplo, fotos de platos).