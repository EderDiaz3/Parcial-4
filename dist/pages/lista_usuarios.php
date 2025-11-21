<?php
session_start();

// Incluir archivos necesarios
require_once __DIR__ . '/../Controller/UsuariosController.php';
require_once __DIR__ . '/../Model/Usuarios.php';
require_once __DIR__ . '/../DB/Conexion.php';

use dist\Controller\UsuariosController;

// Obtener todos los usuarios
$controller = new UsuariosController();
$usuarios = $controller->ObtenerTodosLosUsuarios();

// Capturar mensajes
$mensajeExito = $_SESSION['mensaje_exito'] ?? null;
$mensajeError = $_SESSION['mensaje_error'] ?? null;
unset($_SESSION['mensaje_exito']);
unset($_SESSION['mensaje_error']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lista de Usuarios | AdminLTE</title>
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
                                    <a href="./lista_usuarios.php" class="nav-link active">
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
                            <h3 class="mb-0">Lista de Usuarios</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active">Usuarios</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            
                            <?php if ($mensajeExito): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($mensajeExito) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <?php if ($mensajeError): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($mensajeError) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Todos los Usuarios</h3>
                                    <div class="card-tools">
                                        <a href="index2.php" class="btn btn-success btn-sm">
                                            <i class="bi bi-plus-circle me-1"></i>Nuevo Usuario
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <?php if (count($usuarios) > 0): ?>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 60px">Avatar</th>
                                                <th>ID</th>
                                                <th>Correo</th>
                                                <th>Fecha Nacimiento</th>
                                                <th>Teléfono</th>
                                                <th style="width: 200px">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($usuarios as $usuario): ?>
                                            <tr>
                                                <td>
                                                    <img src="<?= $usuario->Avatar !== 'Sin Avatar' ? $usuario->Avatar : '../../dist/assets/img/user2-160x160.jpg' ?>" 
                                                         alt="Avatar" 
                                                         class="rounded-circle" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                </td>
                                                <td><?= $usuario->UsuarioID ?></td>
                                                <td><?= htmlspecialchars($usuario->Correo) ?></td>
                                                <td><?= $usuario->FechaNacimiento ?></td>
                                                <td><?= htmlspecialchars($usuario->Telefono) ?></td>
                                                <td>
                                                    <a href="ver_perfil.php?id=<?= $usuario->UsuarioID ?>" 
                                                       class="btn btn-info btn-sm" 
                                                       title="Ver perfil">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="index2.php?id=<?= $usuario->UsuarioID ?>" 
                                                       class="btn btn-primary btn-sm" 
                                                       title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="eliminar_usuario.php?id=<?= $usuario->UsuarioID ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       onclick="return confirm('¿Estás seguro de eliminar este usuario?')"
                                                       title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                    <div class="p-4 text-center">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-2">No hay usuarios registrados</p>
                                        <a href="index2.php" class="btn btn-primary">Crear primer usuario</a>
                                    </div>
                                    <?php endif; ?>
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
