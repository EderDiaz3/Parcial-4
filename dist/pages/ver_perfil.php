<?php
session_start();

// Incluir archivos necesarios
require_once __DIR__ . '/../Controller/UsuariosController.php';
require_once __DIR__ . '/../Model/Usuarios.php';
require_once __DIR__ . '/../DB/Conexion.php';

use dist\Controller\UsuariosController;

// Obtener el usuario
$controller = new UsuariosController();
$usuario = null;
$usuarioID = $_GET['id'] ?? $_SESSION['usuario_id'] ?? null;

if ($usuarioID) {
    $usuario = $controller->ObtenerUsuarioPorID($usuarioID);
}

// Si no hay usuario, redirigir a la lista con un mensaje
if (!$usuario) {
    $_SESSION['mensaje_error'] = 'Selecciona un usuario desde la lista para ver su perfil';
    header('Location: lista_usuarios.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Perfil de Usuario | AdminLTE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="../../dist/css/adminlte.css" />
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Header -->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="index.html" class="nav-link">Home</a></li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="./index.html" class="brand-link">
                    <img src="../../dist/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
                    <span class="brand-text fw-light">AdminLTE 4</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard<i class="nav-arrow bi bi-chevron-right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./index.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Dashboard v1</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./index2.php" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Crear Usuario</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./lista_usuarios.php" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Lista de Usuarios</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Perfil de Usuario</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="lista_usuarios.php">Usuarios</a></li>
                                <li class="breadcrumb-item active">Perfil</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Columna Izquierda: Avatar e Info Básica -->
                        <div class="col-md-4">
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid rounded-circle" 
                                             src="<?= $usuario->Avatar !== 'Sin Avatar' ? $usuario->Avatar : '../../dist/assets/img/user2-160x160.jpg' ?>" 
                                             alt="Avatar del usuario"
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                    </div>

                                    <h3 class="profile-username text-center mt-3">
                                        <?= htmlspecialchars($usuario->Correo) ?>
                                    </h3>

                                    <p class="text-muted text-center">Usuario ID: <?= $usuario->UsuarioID ?></p>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b><i class="bi bi-cake2 me-2"></i>Fecha de Nacimiento</b>
                                            <span class="float-end"><?= $usuario->FechaNacimiento ?></span>
                                        </li>
                                        <li class="list-group-item">
                                            <b><i class="bi bi-telephone me-2"></i>Teléfono</b>
                                            <span class="float-end"><?= htmlspecialchars($usuario->Telefono) ?></span>
                                        </li>
                                        <li class="list-group-item">
                                            <b><i class="bi bi-envelope me-2"></i>Correo</b>
                                            <span class="float-end"><?= htmlspecialchars($usuario->Correo) ?></span>
                                        </li>
                                    </ul>

                                    <div class="d-grid gap-2">
                                        <a href="index2.php?id=<?= $usuario->UsuarioID ?>" class="btn btn-primary">
                                            <i class="bi bi-pencil me-2"></i>Editar Perfil
                                        </a>
                                        <a href="lista_usuarios.php" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left me-2"></i>Volver a la lista
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha: Biografía y Detalles -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="bi bi-info-circle me-2"></i>Información Completa
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h5><i class="bi bi-journal-text me-2"></i>Biografía</h5>
                                            <div class="alert alert-light">
                                                <?php if ($usuario->Biografia && $usuario->Biografia !== 'Sin biografía'): ?>
                                                    <?= nl2br(htmlspecialchars($usuario->Biografia)) ?>
                                                <?php else: ?>
                                                    <em class="text-muted">El usuario no ha agregado una biografía.</em>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="text-primary">
                                                        <i class="bi bi-person-badge me-2"></i>ID de Usuario
                                                    </h6>
                                                    <p class="mb-0"><?= $usuario->UsuarioID ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="text-primary">
                                                        <i class="bi bi-envelope-at me-2"></i>Correo Electrónico
                                                    </h6>
                                                    <p class="mb-0"><?= htmlspecialchars($usuario->Correo) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="text-primary">
                                                        <i class="bi bi-calendar-event me-2"></i>Fecha de Nacimiento
                                                    </h6>
                                                    <p class="mb-0"><?= $usuario->FechaNacimiento ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="text-primary">
                                                        <i class="bi bi-phone me-2"></i>Teléfono
                                                    </h6>
                                                    <p class="mb-0"><?= htmlspecialchars($usuario->Telefono) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="text-primary">
                                                        <i class="bi bi-image me-2"></i>Avatar
                                                    </h6>
                                                    <p class="mb-0">
                                                        <?= $usuario->Avatar !== 'Sin Avatar' ? 
                                                            '<span class="badge bg-success">Avatar personalizado</span>' : 
                                                            '<span class="badge bg-secondary">Avatar por defecto</span>' 
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <strong>Copyright &copy; 2014-2025 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="../../dist/js/adminlte.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarWrapper = document.querySelector('.sidebar-wrapper');
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: { theme: 'os-theme-light', autoHide: 'leave' }
                });
            }
        });
    </script>
</body>
</html>
