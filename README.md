<h1>PROCESO DE INSTALACIÓN:<h1><br>
- Clonar el repositorio<br>
- ingresar a la carpeta del proyecto -> cd test<br>
- En una consola ejecutar el comando php artisan serve - Éste ejecutará el servidor backend y por tanto permitirá el ingreso<br>
- En otra consola simultáneamente ejecutar el comando npm run dev - Éste ejecutará el frontend y por tanto permitirá la visualización del contenido<br>
Ingresaremos al proyecto a través de http://127.0.0.1:8000<br>

BASE DE DATOS:<br>
Encontraremos la base de datos del proyecto en la carpeta Test-database
![image](https://github.com/user-attachments/assets/4410c3ff-0724-4713-92cc-b278a9efa247)

CÓMO INGRESAR:<br>
Tal como se especificó en el documento de la prueba, dejamos el login predeterminado, por lo tanto el proceso de inicio de sesión será darle click a Register
![image](https://github.com/user-attachments/assets/0688758f-9f2d-4bea-90b9-8f9a7bec07f8)<br>
Creamos nuestro usuarios y procedemos a ir al Login, logrando autenticarnos e ingresar al proyecto

A TENER EN CUENTA:<br>
El API https://www.thecocktaildb.com/api.php genera problemas de CORS, específicamente "has been blocked by CORS policy: Request header field x-requested-with is not allowed by Access-Control-Allow-Headers in preflight response", por tanto
como solución pertinente instalaremos la extensión "CORS Unblock"
![image](https://github.com/user-attachments/assets/5a2d15d6-ecf1-4717-8cb7-c79c2fddecad)<br>
Debe verse de esta manera para que el API pueda cargar los datos

Una vez ingresado al proyecto, es posible que no cargue ningún dato, si esto ocurre, daremos click en el vínculo "Cocktails", esto recargará los headers, permitiendo cargar los datos
![image](https://github.com/user-attachments/assets/0dadb97e-f417-49aa-ae48-545ad82ae210)<br>

Estas serían las recomendaciones principales para lograr ejecutar con éxito el proyecto.
