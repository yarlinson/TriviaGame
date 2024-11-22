# Proyecto de Trivia

## Introducción
Este proyecto es una plataforma de trivia desarrollada en **PHP** y **JavaScript**, con almacenamiento de datos en una base de datos **PostgreSQL**. Permite a los usuarios responder preguntas, registrar puntajes y administrar contenido.

---

## Requisitos del Sistema

### **Hardware**
- **Procesador**: 2 GHz mínimo.
- **RAM**: 2 GB mínimo.
- **Espacio en disco**: 500 MB para el sistema y base de datos.

### **Software**
- **Sistema Operativo**: Compatible con PHP (Windows, macOS, Linux).
- **Servidor web**: Apache o Nginx.
- **PHP**: Versión 7.4 o superior.
- **Extensiones de PHP**: `pdo_pgsql`.
- **Base de datos**: PostgreSQL versión 12 o superior.
- **Navegador web**: Cualquier navegador actualizado.

---

## Estructura del Proyecto

### **Archivos PHP**
1. `conexion.php`: Configura la conexión con la base de datos PostgreSQL.
2. `contestar_pregunta.php`: Recupera una pregunta aleatoria para que los usuarios respondan.
3. `crear_pregunta.php`: Permite a los usuarios agregar preguntas.
4. `dashboard.php`: Presenta un ranking de usuarios basado en puntajes.
5. `login.php`: Maneja la autenticación de usuarios.
6. `logout.php`: Termina la sesión del usuario.
7. `mostrar.php`: Muestra información almacenada en la base de datos.
8. `procesar_respuesta.php`: Procesa las respuestas enviadas por los usuarios.

### **Archivos HTML y Estilo**
1. `index.html`: Página principal del sistema, utiliza Bootstrap y CSS personalizado.
2. Carpeta `css`: Hojas de estilo personalizadas.
3. Carpeta `js`: Scripts en JavaScript para interacción dinámica.

---

## Base de Datos

La base de datos `DateTriviaJuegos` contiene las siguientes tablas principales:

- **Usuarios**: `id`, `username`, `password`, `puntaje`.
- **Preguntas**: `id`, `contenido`.
- **Respuestas**: `id`, `pregunta_id`, `contenido`, `es_correcta`.

---

## Instalación y Configuración

### **Paso 1: Configurar la Base de Datos**
Ejecutar los siguientes comandos en PostgreSQL:
```sql
CREATE DATABASE DateTriviaJuegos;

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    puntaje INT DEFAULT 0
);

CREATE TABLE preguntas (
    id SERIAL PRIMARY KEY,
    contenido TEXT NOT NULL
);

CREATE TABLE respuestas (
    id SERIAL PRIMARY KEY,
    pregunta_id INT REFERENCES preguntas(id),
    contenido TEXT NOT NULL,
    es_correcta BOOLEAN NOT NULL
);

INSERT INTO usuarios (username, password, puntaje) VALUES 
('juan_perez', '$2b$10$EjemploDeHashDeBcrypt1234567890123456789012345', 100), -- Puntaje: 100
('maria_ruiz', '$2y$10$sG3nB6XQg91CALPp4cwc3unHLOLoqmjgVx8lIGW2lPSxPn1e8OPJK', 150); -- Puntaje: 150

```
## Innstrucciones para Configurar Usuarios Iniciales
Para que el sistema funcione correctamente y los usuarios puedan iniciar sesión, es necesario insertar cuentas iniciales en la base de datos. Esto permitirá probar el sistema con credenciales predefinidas.

Usuarios Iniciales

Estos usuarios ya tienen contraseñas protegidas mediante hashing (bcrypt) y pueden ser utilizados para acceder al sistema:
Usuario: juan_perez
Contraseña: PasswordJuan123
Puntaje inicial: 100
Usuario: maria_ruiz
Contraseña: MariaSegura456
Puntaje inicial: 150

Paso 2: Configurar el Servidor
Instalar un servidor web (por ejemplo, XAMPP).
Configurar el archivo conexion.php con los datos correctos de la base de datos.
Paso 3: Configurar el Proyecto
Colocar los archivos del proyecto en el directorio raíz del servidor (htdocs en XAMPP).
Asegurarse de que las carpetas css y js sean accesibles desde el navegador.
Paso 4: Iniciar el Servidor
Iniciar Apache y PostgreSQL.
Acceder al proyecto desde http://localhost/trivia.

## Uso del Sistema
Inicio de Sesión:
Los usuarios pueden registrarse y autenticarse mediante el archivo login.php.
Participar en Trivia:
Acceder a las preguntas mediante contestar_pregunta.php.
Responder preguntas y ganar puntajes.
Gestión de Preguntas:
Crear nuevas preguntas y respuestas usando crear_pregunta.php.
Ver el Ranking:
Consultar el ranking de usuarios en el archivo dashboard.php.

## Seguridad
Uso de contraseñas hash para proteger datos sensibles.
Validación de datos en formularios para evitar inyección SQL.
Autenticación de usuarios mediante sesiones.

## Mejora Continua
Algunas ideas para ampliar el proyecto:
Añadir niveles de dificultad a las preguntas.
Implementar un sistema de categorías para las preguntas.
Crear un módulo de administración para gestionar usuarios.






