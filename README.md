![GitHub repo size](https://img.shields.io/github/repo-size/PHPostApp/PHPost)

# PHPost 2023-2024
PHPost Risus es un sistema de compartimiento de enlaces que permite crear un sitio web similar a Taringa!

# Por ahora
Este repositorio fue actualizado, no esta completamente todo el script, pero si lo necesario para que este pueda funcionar. Todo lo que es el diseño no se ha tocado solo un poco en los botones y el modal, luego todo sigue igual.

# NOTA 02.03.24
PHPost:
 - Mucho más estructurado.
 - Mucho más limpio.
 - Actualización completa del plugins function.jsdelivr.php 
 - Actualización completa del plugins function.phpost.php es parecido a function.hook.php, pero mejor (lo termine hoy 02.03.24)
 - Optimizador de imágenes mejorado y reestructurado desde cero (Aún no estará disponible para su uso, pero funciona) 

### Actualizaciones
 * PHP 8.x
 * Smarty 3.4.x
 * jQuery 3.7.x 
 * Plugins de jQuery

Se han eliminado códigos completamente innecesarios, que no tenían uso alguno para el script, se modificaron funciones para mejorarlo.
Se añadió un pequeño plugin para smarty que la función que tiene es inspeccionar si existe un archivo ya sea .css o .js de la página en la que este el usuario, en caso de que exista la añade al html.

### INSTALL
Se han implementado mejoras significativas en el instalador del sistema. Se ha añadido un paso adicional que verifica la habilitación de extensiones esenciales, como GD para la carga de imágenes y cURL para el acceso a URL y la obtención de información pertinente. En caso de que estas extensiones no estén habilitadas, se informará al usuario sobre la necesidad de activarlas para garantizar un funcionamiento óptimo del sistema.

Adicionalmente, se ha incorporado un archivo adicional destinado a fortalecer el control sobre las operaciones de inserción y/o actualización de datos en la base de datos. Esta medida proporciona una capa adicional de seguridad y supervisión para asegurar la integridad de la información almacenada. Estas mejoras reflejan nuestro compromiso continuo con la eficiencia y la seguridad en el despliegue y gestión del sistema.

#### DATOS.PHP
Se han eliminado ciertas opciones relacionadas con la descripción personal debido a su escasa utilización y al riesgo potencial que conlleva para la seguridad de los individuos. Al completar dichas secciones, los usuarios podrían exponerse de manera más pronunciada a situaciones peligrosas. En aras de salvaguardar la integridad y privacidad de nuestros usuarios, se ha tomado la decisión de restringir dicha información. Agradecemos su comprensión y colaboración en mantener un entorno seguro para todos los usuarios.