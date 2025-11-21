<?php
session_start();

// Incluir archivos necesarios
require_once __DIR__ . '/../Controller/UsuariosController.php';
require_once __DIR__ . '/../Model/Usuarios.php';
require_once __DIR__ . '/../DB/Conexion.php';

use dist\Controller\UsuariosController;

// Capturar mensajes de la sesión
$mensajeExito = $_SESSION['mensaje_exito'] ?? null;
$mensajeError = $_SESSION['mensaje_error'] ?? null;

// Limpiar mensajes de la sesión
unset($_SESSION['mensaje_exito']);
unset($_SESSION['mensaje_error']);

// Si hay un usuario_id en la URL, cargar sus datos
$usuario = null;
if (isset($_GET['id'])) {
    $controller = new UsuariosController();
    $usuario = $controller->ObtenerUsuarioPorID($_GET['id']);
}
?>
<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE | Editar Perfil</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE | Editar Perfil" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../../dist/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
          </ul>
          <!--end::Start Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="../../dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
              <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./index.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard v1</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./index2.php" class="nav-link active">
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
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-md-12">
                
                <?php if ($mensajeExito): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($mensajeExito) ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if ($mensajeError): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($mensajeError) ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- CARD EDITAR PERFIL -->
                <div class="card card-outline card-primary">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="bi bi-person-<?= $usuario ? 'gear' : 'plus-fill' ?> me-2"></i>
                      <?= $usuario ? 'Editar Perfil de Usuario' : 'Crear Nuevo Usuario' ?>
                    </h3>
                  </div>

                  <form action="procesar_perfil.php" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario()">
                    <?php if ($usuario): ?>
                      <input type="hidden" name="accion" value="actualizar">
                      <input type="hidden" name="usuario_id" value="<?= $usuario->UsuarioID ?>">
                    <?php else: ?>
                      <input type="hidden" name="accion" value="crear">
                    <?php endif; ?>
                    
                    <div class="card-body">
                      <!-- Sección: Información Personal -->
                      <div class="row">
                        <div class="col-md-3 text-center">
                          <div class="form-group mb-3">
                            <label class="fw-bold"><i class="bi bi-image me-1"></i> Avatar</label>
                            <div class="mb-2">
                              <img id="preview" 
                                   src="<?= $usuario && $usuario->Avatar !== 'Sin Avatar' ? $usuario->Avatar : '../../dist/assets/img/user2-160x160.jpg' ?>" 
                                   class="img-circle elevation-2"
                                   style="width:120px; height:120px; object-fit:cover;">
                            </div>
                            <div class="mt-2">
                              <label for="avatar" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-upload me-1"></i> Elegir imagen
                              </label>
                              <input type="file" class="d-none" id="avatar" name="avatar" accept="image/*">
                            </div>
                            <small class="form-text text-muted d-block mt-2">JPG, PNG, GIF, WEBP<br>Máx: 5MB</small>
                          </div>
                        </div>
                        
                        <div class="col-md-9">
                          <!-- Fecha de nacimiento -->
                          <div class="form-group mb-3">
                            <label><i class="bi bi-calendar-event me-1"></i> Fecha de nacimiento <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control" 
                                   name="fecha_nacimiento" 
                                   value="<?= $usuario ? $usuario->FechaNacimiento : '' ?>" 
                                   required>
                          </div>

                          <!-- Correo -->
                          <div class="form-group mb-3">
                            <label><i class="bi bi-envelope me-1"></i> Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control" 
                                   name="correo" 
                                   placeholder="correo@example.com" 
                                   value="<?= $usuario ? htmlspecialchars($usuario->Correo) : '' ?>" 
                                   required>
                            <small class="form-text text-muted">Este correo será usado para iniciar sesión</small>
                          </div>

                          <!-- Teléfono -->
                          <div class="form-group mb-3">
                            <label><i class="bi bi-telephone me-1"></i> Teléfono (opcional)</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="telefono"
                                   placeholder="555-1234"
                                   value="<?= $usuario ? htmlspecialchars($usuario->Telefono) : '' ?>">
                          </div>
                        </div>
                      </div>

                      <hr class="my-4">

                      <!-- Sección: Seguridad -->
                      <h5 class="mb-3"><i class="bi bi-shield-lock me-2"></i> Seguridad</h5>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group mb-3">
                            <label>
                              <i class="bi bi-key me-1"></i>
                              <?= $usuario ? 'Nueva Contraseña (dejar en blanco para no cambiar)' : 'Contraseña' ?>
                              <?= !$usuario ? '<span class="text-danger">*</span>' : '' ?>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="pass" 
                                   name="password"
                                   placeholder="<?= $usuario ? 'Dejar en blanco para mantener' : 'Ingrese su contraseña' ?>"
                                   <?= !$usuario ? 'required' : '' ?>>
                          </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group mb-3">
                            <label>
                              <i class="bi bi-key-fill me-1"></i>
                              Confirmación de contraseña
                              <?= !$usuario ? '<span class="text-danger">*</span>' : '' ?>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="pass2" 
                                   name="password_confirm"
                                   placeholder="Confirme su contraseña"
                                   <?= !$usuario ? 'required' : '' ?>>
                            <div id="password-error" class="text-danger mt-1" style="display:none;">
                              <i class="bi bi-exclamation-circle me-1"></i>Las contraseñas no coinciden
                            </div>
                          </div>
                        </div>
                      </div>

                      <hr class="my-4">

                      <!-- Sección: Información Adicional -->
                      <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i> Información Adicional</h5>
                      <div class="form-group mb-3">
                        <label><i class="bi bi-chat-left-text me-1"></i> Biografía (opcional)</label>
                        <textarea class="form-control" 
                                  name="biografia" 
                                  rows="4"
                                  placeholder="Cuéntanos sobre ti..."><?= $usuario ? htmlspecialchars($usuario->Biografia) : '' ?></textarea>
                        <small class="form-text text-muted">Máximo 500 caracteres</small>
                      </div>

                    </div>

                    <div class="card-footer bg-light">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i><?= $usuario ? 'Actualizar Usuario' : 'Guardar Usuario' ?>
                      </button>
                      <a href="lista_usuarios.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver a la Lista
                      </a>
                    </div>
                  </form>
                </div>
                <!-- /.card -->
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      
      <!--begin::Footer-->
      <footer class="app-footer">
        <strong>
          Copyright &copy; 2014-2025
          <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="../../dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    
    <!--begin::Form Validation Scripts-->
    <script>
      // Preview de avatar
      document.getElementById("avatar").addEventListener("change", function(){
        if (this.files && this.files[0]) {
          const reader = new FileReader();
          reader.onload = function(e){
            document.getElementById("preview").src = e.target.result;
          };
          reader.readAsDataURL(this.files[0]);
        }
      });

      // Validación de contraseñas
      function validarPasswords() {
        const p1 = document.getElementById("pass").value;
        const p2 = document.getElementById("pass2").value;
        const errorDiv = document.getElementById("password-error");

        if (p1 !== "" && p2 !== "" && p1 !== p2) {
          document.getElementById("pass2").setCustomValidity("Las contraseñas no coinciden");
          errorDiv.style.display = "block";
          return false;
        } else {
          document.getElementById("pass2").setCustomValidity("");
          errorDiv.style.display = "none";
          return true;
        }
      }
      
      function validarFormulario() {
        return validarPasswords();
      }
      
      document.getElementById("pass").addEventListener("input", validarPasswords);
      document.getElementById("pass2").addEventListener("input", validarPasswords);
      document.getElementById("pass2").addEventListener("blur", validarPasswords);
    </script>
    <!--end::Form Validation Scripts-->
  </body>

