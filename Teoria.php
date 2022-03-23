¿Porque piensa que se decidió implementar algunas de las necesidades como Servicios WEB? ¿Cuál es su ventaja? 
Porque se pueden ofrecer servicios integrados mas facilmente.
Porque Cada servicio puede actualizarse independientemente mientras respete su interfaz
Porque pueden programarse en diferentes lenguajes
Porque facilita la reutilización
Porque desacopla partes no relacionadas del sistema
Porque se pueden usar servicios existentes


¿Qué otros servicios web pensaría que pueden ser útiles en este sistema? 
De al menos dos ejemplos, defina los endpoints de cada uno y explique brevemente cual es su función y porque pueden ser útiles

Eliminar una Publicacion (utilizaria el metodo DELETE) para que cuando la publicacion no me sirva mas la pueda borrar

API/Servicios/Publicacion/:ID

Agregar una publicacion (utilizaria el metodo POST) para que la puedan ver los demas

API/Servicios/Publicacion

3. Explique brevemente las ventajas de usar:

CSR (Client Side Rendering)

Las ventajas son buena UX, renderizacion rápida después de la carga inicial y tambien que es excelente para aplicaciones web.

PDO

Las ventajas son:
capa de abstracción
sintaxis orientada a objetos
soporte para declaraciones preparadas (ayuda a protegerse de la inyección de SQL)
mejor manejo de excepciones
API seguras y reutilizables
soporte para todas las bases de datos populares



4.  Explique el patrón de diseño MVC. Describa cada una de sus partes y las responsabilidades de las mismas. 
Ventajas y desventajas del uso de este patrón. ¿En qué tipo de sistema no sería útil usar este patrón?

MVC separa responsabilidades para lograr que los sistemas sean mantenibles y modificables en el transcurso del tiempo.
Es un patrón de arquitectura de software utilizado ampliamente en la industria.  
MVC divide la lógica del programa en tres elementos inter-relacionados. Cada uno cumple una función determinada.

MODELO
Se encarga de la comunicación con los datos de nuestro sistema (base de datos). 
Proteger y persistir los datos del usuario.
Asegurar la integridad y consistencia de datos.
Proveer métodos para consultar, insertar, modificar y borrar datos.

VISTA 
Se encarga de generar la interfaz gráfica del usuario e interactuar con ellos.
Presentar la información al usuario (front-end).
Permitir al usuario interactuar con la aplicación.

CONTROLADOR 
Coordina la comunicación entre el modelo y la vista. 
Acepta las peticiones del usuario y coordina el flujo de la aplicación.
Valida la entrada de datos del usuario.
En un ruteo de una aplicación MVC. Cada entrada de la tabla de ruteo invoca a un método de un controlador.


VENTAJAS MVC
Crea un sistema desacoplado
Reduce la complejidad de cada parte del sistema
Permite trabajar en paralelo de forma colaborativa (Desarrolladores FrontEnd – BackEnd)
Facilita escalabilidad y mantenimiento.

DESVENTAJAS MVC
Agrega complejidad a la solución
La estructura predefinida puede no ser lo que estábamos buscando
