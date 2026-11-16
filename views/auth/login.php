<!DOCTYPE html>
<html lang="es"><head>
<meta charset="UTF-8"><title>Login - Sistema Boxeo</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?=BASE_URL?>public/css/style.css" rel="stylesheet">
<style>
body{background: linear-gradient(135deg,#1a1a1a 0%,#c8102e 100%); min-height:100vh; display:flex; align-items:center;}
.login-card{background:#fff; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,.4); padding:2.5rem; max-width:420px; margin:auto;}
.login-card h2{color:#c8102e; font-weight:800;}
.login-icon{font-size:4rem; color:#c8102e;}
</style></head>
<body>
<div class="container">
  <div class="login-card text-center">
    <i class="bi bi-trophy-fill login-icon"></i>
    <h2>BOXEO</h2>
    <p class="text-muted">Sistema de Gestión Deportiva</p>
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="text-start">
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-person"></i> Usuario</label>
        <input type="text" name="username" class="form-control form-control-lg" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-lock"></i> Contraseña</label>
        <input type="password" name="password" class="form-control form-control-lg" required minlength="6">
      </div>
      <button class="btn btn-primary btn-lg w-100"><i class="bi bi-box-arrow-in-right"></i> Ingresar</button>
    </form>
    <small class="text-muted d-block mt-3">Usuario demo: <strong>admin</strong> / <strong>admin123</strong></small>
  </div>
</div>
</body></html>
