# PHPost 2023
PHPost Risus es un sistema de compartimiento de enlaces que permite crear un sitio web similar a Taringa!

# Por ahora
Este repositorio fue actualizado, no esta completamente todo el script, pero si lo necesario para que este pueda funcionar. Todo lo que es el diseño no se ha tocado solo un poco en los botones y el modal, luego todo sigue igual.

### Actualizaciones
 * PHP 8
 * Smarty 3.4.x
 * jQuery 3.7.x 
 * Plugins de jQuery

Se han eliminado códigos completamente innecesarios, que no tenían uso alguno para el script, se modificaron funciones para mejorarlo.
Se añadió un pequeño plugin para smarty que la función que tiene es inspeccionar si existe un archivo ya sea .css o .js de la página en la que este el usuario, en caso de que exista la añade al html.

### INSTALL
Se hizo modificaciones en el instalador, tiene un nuevo paso extra en el que informará que si tiene las extensiones necesarias habilitadas o no, por ejemplo la extensión GD, si no esta habilitada no podrás subir imagenes al sitio. Si la extensión cURL no esta habilitada no podrá acceder a las url y poder obtener información de las misma.
Se agrego un archivo para realizar un mayor control sobre las acciones de insertar y/o actualizar los datos en la base de datos.