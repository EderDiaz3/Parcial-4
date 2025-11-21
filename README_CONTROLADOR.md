# 📚 Documentación - Sistema de Gestión de Usuarios

## 🏗️ Estructura del Proyecto

```
dist/
├── Controller/
│   └── UsuariosController.php  (Controlador con toda la lógica de negocio)
├── Model/
│   └── Usuarios.php            (Modelo de datos)
├── DB/
│   ├── Conexion.php            (Clase de conexión a BD)
│   └── usuarios.sql            (Script SQL para crear la tabla)
├── pages/
│   ├── index.html              (Dashboard principal)
│   ├── index2.php              (Formulario de edición de perfil)
│   └── procesar_perfil.php     (Procesador del formulario)
└── uploads/
    └── avatars/                (Carpeta para almacenar avatares)
```

---

## 🎯 Funcionalidades del Controlador

### 1️⃣ **ObtenerUsuarioPorID($UsuarioID)**
Obtiene un usuario específico por su ID.

```php
$controller = new UsuariosController();
$usuario = $controller->ObtenerUsuarioPorID(1);

if ($usuario) {
    echo $usuario->Correo;
    echo $usuario->FechaNacimiento;
}
```

### 2️⃣ **CrearUsuario($datos)**
Crea un nuevo usuario con validaciones.

```php
$datos = [
    'correo' => 'usuario@ejemplo.com',
    'password' => 'miPassword123',
    'fecha_nacimiento' => '1995-05-15',
    'telefono' => '555-1234',
    'biografia' => 'Mi biografía'
];

$response = $controller->CrearUsuario($datos);

if ($response['success']) {
    echo "Usuario creado con ID: " . $response['usuarioID'];
} else {
    echo "Error: " . $response['message'];
}
```

**Características:**
- ✅ Valida datos obligatorios (correo y contraseña)
- ✅ Verifica que el correo no exista
- ✅ Hashea la contraseña automáticamente
- ✅ Procesa y guarda el avatar
- ✅ Devuelve el ID del nuevo usuario

### 3️⃣ **ActualizarUsuario($usuarioID, $datos)**
Actualiza los datos de un usuario existente.

```php
$datos = [
    'correo' => 'nuevo@correo.com',
    'password' => 'nuevaPassword',  // Opcional
    'fecha_nacimiento' => '1990-01-01',
    'telefono' => '555-9876',
    'biografia' => 'Nueva biografía'
];

$response = $controller->ActualizarUsuario(1, $datos);

if ($response['success']) {
    echo "Usuario actualizado correctamente";
}
```

**Características:**
- ✅ Solo actualiza los campos proporcionados
- ✅ Si no se proporciona password, mantiene el anterior
- ✅ Valida que el correo no esté en uso por otro usuario
- ✅ Reemplaza el avatar antiguo si se sube uno nuevo

### 4️⃣ **EliminarUsuario($usuarioID)**
Elimina un usuario y su avatar.

```php
$response = $controller->EliminarUsuario(1);

if ($response['success']) {
    echo "Usuario eliminado";
}
```

### 5️⃣ **ObtenerTodosLosUsuarios()**
Obtiene todos los usuarios de la base de datos.

```php
$usuarios = $controller->ObtenerTodosLosUsuarios();

foreach ($usuarios as $usuario) {
    echo $usuario->Correo . "<br>";
    echo $usuario->FechaNacimiento . "<br>";
}
```

### 6️⃣ **ValidarCredenciales($correo, $password)**
Valida las credenciales de login.

```php
$resultado = $controller->ValidarCredenciales('usuario@ejemplo.com', 'password123');

if ($resultado) {
    $_SESSION['usuario_id'] = $resultado['UsuarioID'];
    $_SESSION['correo'] = $resultado['Correo'];
    echo "Login exitoso";
} else {
    echo "Credenciales inválidas";
}
```

---

## 🔒 Seguridad Implementada

### ✅ **Prepared Statements**
Todos los métodos usan prepared statements para prevenir SQL Injection:

```php
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $var1, $var2, ...);
```

### ✅ **Password Hashing**
Las contraseñas se hashean con `password_hash()` y se verifican con `password_verify()`:

```php
// Al crear/actualizar
$passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

// Al validar login
if (password_verify($password, $hashAlmacenado)) {
    // Login exitoso
}
```

### ✅ **Validación de Archivos**
El método `ProcesarAvatar()` valida:
- Tipos de archivo permitidos (JPG, PNG, GIF, WEBP)
- Tamaño máximo (5MB)
- Genera nombres únicos para evitar colisiones

### ✅ **Validación de Correos Únicos**
El método `ExisteCorreo()` verifica que no haya duplicados:

```php
// Al crear
if ($this->ExisteCorreo($correo)) {
    return ['success' => false, 'message' => 'El correo ya existe'];
}

// Al actualizar (excluye el usuario actual)
if ($this->ExisteCorreo($correo, $usuarioID)) {
    return ['success' => false, 'message' => 'El correo ya está en uso'];
}
```

---

## 📝 Uso del Formulario

### **Crear nuevo usuario:**
```html
<form action="procesar_perfil.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="crear">
    <!-- Campos del formulario -->
</form>
```

### **Editar usuario existente:**
```html
<form action="procesar_perfil.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="actualizar">
    <input type="hidden" name="usuario_id" value="1">
    <!-- Campos del formulario -->
</form>
```

### **Cargar usuario para editar:**
Usa el parámetro GET `id`:
```
index2.php?id=1
```

El formulario detectará automáticamente el usuario y cargará sus datos.

---

## 🗄️ Base de Datos

### Ejecutar el script SQL:
```sql
-- Desde MySQL o phpMyAdmin
source dist/DB/usuarios.sql
```

### Estructura de la tabla:
```sql
CREATE TABLE `usuarios` (
  `UsuarioID` int(11) NOT NULL AUTO_INCREMENT,
  `Avatar` varchar(255) DEFAULT 'Sin Avatar',
  `FechaNacimiento` date DEFAULT '0000-00-00',
  `Correo` varchar(100) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Telefono` varchar(20) DEFAULT 'Sin especificar',
  `Biografia` text DEFAULT 'Sin biografía',
  PRIMARY KEY (`UsuarioID`),
  UNIQUE KEY `Correo` (`Correo`)
);
```

---

## 🚀 Respuestas del Controlador

Todos los métodos de crear, actualizar y eliminar devuelven un array con esta estructura:

```php
[
    'success' => true,  // o false
    'message' => 'Mensaje descriptivo',
    'usuarioID' => 123  // Solo en CrearUsuario cuando success=true
]
```

### Ejemplo de manejo:
```php
$response = $controller->ActualizarUsuario($id, $datos);

if ($response['success']) {
    $_SESSION['mensaje_exito'] = $response['message'];
} else {
    $_SESSION['mensaje_error'] = $response['message'];
}
```

---

## 📋 Mensajes en Sesión

El sistema usa sesiones para mostrar mensajes al usuario:

```php
// Después de procesar
$_SESSION['mensaje_exito'] = 'Usuario creado correctamente';
// o
$_SESSION['mensaje_error'] = 'Error al crear usuario';

// En index2.php se capturan y muestran
$mensajeExito = $_SESSION['mensaje_exito'] ?? null;
$mensajeError = $_SESSION['mensaje_error'] ?? null;

// Y se limpian
unset($_SESSION['mensaje_exito']);
unset($_SESSION['mensaje_error']);
```

---

## ✨ Características Adicionales

### 📸 **Preview de Avatar en Tiempo Real**
JavaScript muestra el avatar antes de subirlo:
```javascript
document.getElementById("avatar").addEventListener("change", function(){
    const reader = new FileReader();
    reader.onload = function(e){
        document.getElementById("preview").src = e.target.result;
    };
    reader.readAsDataURL(this.files[0]);
});
```

### 🔐 **Validación de Contraseñas**
JavaScript valida que las contraseñas coincidan antes de enviar:
```javascript
function validarPasswords() {
    const p1 = document.getElementById("pass").value;
    const p2 = document.getElementById("pass2").value;
    
    if (p1 !== p2) {
        // Mostrar error
        return false;
    }
    return true;
}
```

---

## 🎨 Flujo Completo del Sistema

```
Usuario accede → index.html
    ↓
Clic en "Editar perfil" → index2.php
    ↓
Llena el formulario → Submit
    ↓
procesar_perfil.php
    ↓
UsuariosController.php
    ↓
Conexion.php → MySQL
    ↓
Respuesta → Sesión
    ↓
Redirect → index2.php (muestra mensaje)
```

---

## 🔧 Configuración Necesaria

1. **Crear la base de datos:**
   ```bash
   mysql -u AlumnosPV -p < dist/DB/usuarios.sql
   ```

2. **Verificar credenciales en Conexion.php:**
   ```php
   $this->host = "localhost";
   $this->user = "AlumnosPV";
   $this->password = "Prog.V2025";
   $this->database = "parcial4";
   ```

3. **Crear carpeta de uploads:**
   ```
   dist/uploads/avatars/
   ```
   O dejar que el sistema la cree automáticamente.

4. **Actualizar links en index.html:**
   ```html
   <a href="./index2.php" class="nav-link">
   ```

---

¡Tu sistema está listo para funcionar! 🎉
