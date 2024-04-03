![Discord](https://img.shields.io/discord/1150516717617938543?style=flat-square&label=Chat%20de%20Discord&color=%237289da)
![GitHub commit activity (branch)](https://img.shields.io/github/commit-activity/m/ScriptParaPHPost/PHPost/main?style=flat-square&label=Actividad%20commit)
![GitHub top language](https://img.shields.io/github/languages/top/ScriptParaPHPost/PHPost?style=flat-square)
![GitHub Downloads (all assets, latest release)](https://img.shields.io/github/downloads/ScriptParaPHPost/PHPost/latest/total?style=flat-square)
![GitHub repo size](https://img.shields.io/github/repo-size/ScriptParaPHPost/PHPost?style=flat-square&label=Peso%20total)


# PHPost 2024
PHPost Risus es un sistema de compartimiento de enlaces que permite crear un sitio web similar a Taringa!

### Actualizaciones
 * PHP 7.x/8.x
 * Smarty 3.5
 * jQuery 3.7 
 * Plugins de jQuery

Se han eliminado códigos completamente innecesarios, que no tenían uso alguno para el script, se modificaron funciones para mejorarlo.
Se añadió un pequeño plugin para smarty que la función que tiene es inspeccionar si existe un archivo ya sea .css o .js de la página en la que este el usuario, en caso de que exista la añade al html.

### INSTALL
Se hizo modificaciones en el instalador, tiene un nuevo paso extra en el que informará que si tiene las extensiones necesarias habilitadas o no, por ejemplo la extensión GD, si no esta habilitada no podrás subir imagenes al sitio. Si la extensión cURL no esta habilitada no podrá acceder a las url y poder obtener información de las misma.
Se agrego un archivo para realizar un mayor control sobre las acciones de insertar y/o actualizar los datos en la base de datos.

### ¿Que es .env.example?
Este archivo hay que renombrarlo a `.env` y allí colocan su [token de github](https://github.com/settings/tokens?type=beta) y así poder acceder a los commits de una mejor manera, ya que sin el token las consultas a los token es limitada.

 * [Generan un nuevo token](https://github.com/settings/personal-access-tokens/new)
 * Asignan un nombre que deseen
 * **Expiration** eligen una fecha
 * y dan a generar token

No deben compartir el archivo .env, ni su token