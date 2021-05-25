

## Interactive tools for "Diseño y Administración de Sistemas Operativos" course

This app is intended to be used to follow along the course "Diseño y administración de sistemas operativos" from UNED.
The system is based on several Docker images and runs a content management app for courses, tasks, and users with differentiated roles and permissions.
Code tasks show a live terminal connected to an independent Docker container that can be used to assess the student's progress.

## Installation
A pre-requisite to install this tool is to have Docker installed on your machine.
Then clone this repository.
Inside the root directory of the project, copy the .env.example file to .env
 ```
 cp .env.example .env
 ```
Change the APP_URL constant if needed and then build the image:
```
docker-compose build app
```
Start the docker environment in detached mode:
```
docker-compose up -d
```
From now on, all commands will run in the app service. So, to install dependencies through composer, we must:
```
docker-compose exec app composer install
```
Create the app key too (you can check in the .env file the new key)
```
docker-compose exec app php artisan key:generate
```
And to create the databases with the initial seed:
```
docker-compose exec app php artisan migrate --seed
```

The tool is now ready to visit in `http://192.168.1.88:8000/`

## Usage
### Rol profesor
#### Manual para introducir nuevos contenidos
Una vez realizado el acceso con un usuario y contraseña que nos acredite como profesor para insertar nuevos contenidos debemos hacer click en el botón de editar del curso en cuestión.
Una vez ahí podemos editar una tarea existente o crear una nueva en el botón "Añadir nueva tarea"
Podemos incluir esta tarea como subtarea de otra, como parte de un capítulo o sección nueva o de las que ya estén dadas de alta.
También debemos cumplimentar con los datos básicos para cada tarea. Una vez elegido el tipo de contenido se mostrarán los siguientes campos para incluir la información necesaria.
También podemos decidir si esta tarea va a depender de otra, esto es, que hasta que la tarea de la que depende no aparezca como completada, el alumno no podrá acceder a esta.
Una vez creada cada tarea puede ser dispuesta en el orden que deseemos. Esto se consigue arrastrando la tarea hasta el lugar adecuado y salvando esta disposición, que será la que se muestre en el curso.

**El editor de markdown**
Las tareas de tipo chapter, code y document presentan un editor en el que podemos insertar y editar el texto que luego se mostrará en el curso. La interfaz es intuitiva y está basado en el conocido markdown.

**Subir archivos de código**
Las tareas de tipo code tienen un campo en el formulario para subir archivos de código que luego pueden ser manejados en la preparación previa de la tarea o en el test posterior.
Estos archivos se guardarán en el sistema y pueden ser accedidos desde "../ejemplos/"

**Cuestionarios**
Los cuestionarios pueden admitir una o múltiples respuestas, que se pueden seleccionar desde el formulario.

#### Manual para meter nuevos alumnos
Podemos añadir alumnos en bloque desde un archivo en formato csv separado por comas donde los campos son ```nombre, apellido, email, otros``` Podemos también añadir al curso a aquellos alumnos que ya estén dados de alta en el sistema si seleccionamos su email en el formulario. O podemos uno a uno añadir el email del alumno, al que se asignará una contraseña aleatoria.
#### Manual para añadir nuevos cursos
El proceso es similar al de añadir una tarea. En la pantalla principal, después de haber hecho el acceso con un rol de profesor, seleccionamos el botón "crear curso" que nos llevará al formulario correspondiente.
Tras esto podremos añadir las tareas al curso.
### Rol estudiante
#### entrada en el sitio, mi cuenta
Después de haber entrado en el sitio llegamos a la página principal donde disponemos de dos opciones, visitar nuestra cuenta para comprobar que nuestros datos son correctos. Aunque se ha deshabilitado la opción de edición de estos contenidos y solo será posible ponerse en contacto con el tutor en el caso de que algo deba ser modificado.
O visitar el/los cursos en los que estemos dados de alta.

#### visualización del curso, puntos, progreso
Una vez dentro del curso podemos ver nuestra puntuación, nuestro progreso dentro de la asignatura y los contenidos de esta.
En cada curso podemos ver el itinerario con las tareas ordenadas en el margen izquierdo. Si las tareas están anidadas por capítulos debemos hacer para hacer click y expandir o podemos ir tarea tras tarea avanzando gracias a los botones de progreso que aparecen debajo de cada tarea. Al hacerlo así se irán sumando los puntos y el progreso conseguido.
Algunos contenidos pueden requerir que hayamos realizado otras tareas anteriormente y pueden aparecer como bloqueados.
Ciertas tareas son ejercicios que no nos dejarán avanzar hasta que no los hayamos realizado y comprobado el resultado.
Cada tarea dispone de un enlace a la página de la Uned para consultar dudas o avisar de incidencias.
Pero si no estamos dados de alta en un curso no nos va a permitir ver su contenido.

## License
Copyright 2021 Carmen Maymó @mmaymo

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

