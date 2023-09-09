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

### ¿Como usar el plugin de PHPost?
Así será la estructura del plugin
```html
{phpost favicon="xx.xx" css=["xx1.xx", "xx2.xx"] js=["xx1.xx", "xx2.xx"] deny=["xx1.xx", "xx2.xx"]}
```

**favicon="xx.xx"** = _string_ : Con este puedes agregar el icono/favicon a tu sitio, y debe tener su extensión. Las extensiones permitidas para el icono son: _ico_, _png_, _jpg_, _jpeg_, _gif_, _svg_ y _webp_

**css=["xx1.xx", "xx2.xx"]** / **css="xx1.xx"** = _string|array_ : Solo deben poner los estilos que deseen agregar a su sitio con la extensión _xx.css_

**js=["xx1.xx", "xx2.xx"]** / **js="xx1.xx"** = _string|array_ : Solo deben poner los scripts que deseen agregar a su sitio con la extensión _xx.js_

**deny=["xx1.xx", "xx2.xx"]** = _array_ : Va en conjunto con **js=[]** ya que se puede llegar a repetir el archivo como en el caso de "**moderacion.js**" o "**cuenta.js**", ya que no estarán en el `<head>`

Y así se puede usar en los themes evitando todo el texto HTML, de esta forma esta aplicado en el default, donde se ve "**$tsPage.css**" y "**$tsPage.js**" esto hará que de forma automatica busque el archivo que tenga el mismo nombre que la página actual en la que esten navegando.
```html
{phpost 
	favicon="favicon.ico" 
	css=["estilo.css", "phpost.css", "extras.css", "$tsPage.css", "wysibb.css"] 
	js=["acciones.js", "wysibb.js", "$tsPage.js"] 
	deny=["moderacion.js", "cuenta.js"]
}
```
¿Se puede mejorar sin poner "**$tsPage.xx**"? 
Si tranquilamente, pero por el momento es así de básico.

¿Porqué no está _jquery.min.js_, _jquery.plugins.js_?
Ya que estos archivos siempre estarán en todos los themes, se agregará de forma automática sin necesidad de tener que estar mencionandolos.