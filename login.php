<?php
session_start();
include __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // TODO: implementare password_hash/password_verify
    $_SESSION['email'] = $email;
    // Ruolo semplice: email che contiene 'it@' => IT, altrimenti USER
    $_SESSION['role'] = (strpos(strtolower($email), 'it@') !== false) ? 'IT' : 'USER';
    if ($_SESSION['role'] === 'IT') {
        header('Location: pages/dashboard.php');
    } else {
        header('Location: pages/catalogo.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang='it'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Login</title>
<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='bg-light'>
<div class='container mt-5'>
  <div class='row justify-content-center'>
    <div class='col-md-4'>
      <div class='card shadow'>
        <div class='card-header bg-primary text-white text-center'>Login</div>
        <div class='card-body'>
          <form method='post'>
            <div class='mb-3'>
              <label class='form-label'>Email</label>
              <input type='email' name='email' class='form-control' required>
            </div>
            <div class='mb-3'>
              <label class='form-label'>Password</label>
              <input type='password' name='password' class='form-control' required>
            </div>
            <button type='submit' class='btn btn-primary w-100'>Accedi</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>