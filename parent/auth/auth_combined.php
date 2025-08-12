<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Connexion / Inscription - Betuole Académie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* Reset and base */
    * {
      box-sizing: border-box;
    }
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      color: #f0f0f0;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    /* Navigation bar container */
    .nav-bar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 40px;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      display: flex;
      align-items: center;
      padding: 0 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
      z-index: 1000;
    }
    .nav-bar a {
      color: #f0f0f0;
      font-weight: 600;
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 6px;
      transition: background-color 0.3s ease, color 0.3s ease;
      user-select: none;
    }
    .nav-bar a:hover,
    .nav-bar a:focus {
      background: #f0f0f0;
      color: #1e3c72;
      outline: none;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    /* Container */
    .auth-wrapper {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.4);
      width: 420px;
      max-width: 90vw;
      padding: 40px 30px 50px;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      overflow: hidden;
      margin-top: 60px; /* increased margin to separate from nav bar */
    }

    /* Toggle buttons */
    .toggle-buttons {
      display: flex;
      width: 100%;
      margin-bottom: 30px;
      border-radius: 50px;
      background: rgba(255, 255, 255, 0.15);
      box-shadow: inset 0 0 10px rgba(255,255,255,0.1);
      overflow: hidden;
    }
    .toggle-buttons button {
      flex: 1;
      padding: 12px 0;
      font-size: 1.2rem;
      font-weight: 600;
      color: #ddd;
      background: transparent;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      z-index: 2;
      user-select: none;
    }
    .toggle-buttons button.active {
      color: #1e3c72;
      background: #f0f0f0;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      border-radius: 50px;
      z-index: 3;
    }
    .toggle-buttons button:focus {
      outline: none;
    }

    /* Forms container */
    .form-container {
      width: 100%;
      position: relative;
      min-height: 420px;
    }

    /* Form styles */
    form {
      background: transparent;
      color: #222;
      display: flex;
      flex-direction: column;
      gap: 18px;
      position: absolute;
      width: 100%;
      top: 0;
      left: 0;
      transition: opacity 0.4s ease, transform 0.4s ease;
      border-radius: 15px;
    }
    form.hidden {
      opacity: 0;
      pointer-events: none;
      transform: translateX(50px);
    }
    form.visible {
      opacity: 1;
      pointer-events: auto;
      transform: translateX(0);
    }

    /* Form heading */
    form h2 {
      text-align: center;
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 20px;
      color: #f0f0f0;
      text-shadow: 0 0 8px rgba(0,0,0,0.4);
    }

    /* Input groups */
    .form-floating {
      position: relative;
      width: 100%;
    }
    .form-floating input,
    .form-floating select {
      width: 100%;
      padding: 14px 18px;
      border-radius: 12px;
      border: none;
      background: rgba(255,255,255,0.9);
      color: #222;
      font-size: 1rem;
      font-weight: 500;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: box-shadow 0.3s ease;
    }
    .form-floating input:focus,
    .form-floating select:focus {
      outline: none;
      box-shadow: 0 0 10px #66b2ff;
      background: #fff;
    }
    .form-floating label {
      position: absolute;
      top: 50%;
      left: 18px;
      transform: translateY(-50%);
      color: #666;
      font-weight: 600;
      pointer-events: none;
      transition: all 0.3s ease;
      font-size: 1rem;
    }
    .form-floating input:focus + label,
    .form-floating input:not(:placeholder-shown) + label,
    .form-floating select:focus + label,
    .form-floating select:not([value=""]) + label {
      top: 8px;
      font-size: 0.75rem;
      color: #007bff;
      background: transparent;
      padding-left: 4px;
    }

    /* Icons inside inputs */
    .form-floating i {
      position: absolute;
      top: 50%;
      right: 18px;
      transform: translateY(-50%);
      color: #999;
      font-size: 1.2rem;
      pointer-events: none;
    }

    /* Submit buttons */
    .btn-submit {
      background: #007bff;
      color: white;
      font-weight: 700;
      font-size: 1.1rem;
      padding: 14px 0;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      box-shadow: 0 6px 15px rgba(0,123,255,0.5);
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
      user-select: none;
    }
    .btn-submit:hover {
      background: #0056b3;
      box-shadow: 0 8px 20px rgba(0,86,179,0.7);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .auth-wrapper {
        width: 90vw;
        padding: 30px 20px 40px;
        min-height: 630px; /* 420px + half of 420px */
      }
      .toggle-buttons button {
        font-size: 1rem;
        padding: 10px 0;
      }
      .form-container {
        min-height: 480px;
      }
    }
  </style>
</head>
<body>
    <div class="auth-wrapper" role="main" aria-label="Formulaire d'authentification">
      <div class="toggle-buttons" role="tablist" aria-label="Basculer entre connexion et inscription">
        <button id="btn-login" role="tab" aria-selected="true" aria-controls="login-form" tabindex="0" class="active">Connexion</button>
        <button id="btn-register" role="tab" aria-selected="false" aria-controls="register-form" tabindex="-1">Inscription</button>
      </div>

      <div class="nav-bar" role="navigation" aria-label="Navigation principale">
        <a href="index.php" tabindex="0" style="text-decoration: underline; cursor: pointer;">Accueil</a>
      </div>

      <div class="form-container">
      <form id="login-form" class="visible" method="POST" action="login_process.php" aria-labelledby="btn-login" novalidate>
        <h2>Connexion à votre espace</h2>
        <?php if (!empty($_SESSION['error'])): ?>
          <script>
            Swal.fire({ icon: 'error', title: 'Erreur', text: '<?= $_SESSION["error"] ?>' });
          </script>
          <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
          <script>
            Swal.fire({ icon: 'success', title: 'Succès', text: '<?= $_SESSION["success"] ?>' });
          </script>
          <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="form-floating position-relative">
              <input type="text" id="matricule" name="matricule" placeholder="" required>
          <label for="matricule">Matricule</label>
          <i class="bi bi-person-badge-fill"></i>
        </div>

        <div class="form-floating position-relative">
          <input type="email" id="email" name="email" placeholder="" required>
          <label for="email">Adresse email</label>
          <i class="bi bi-envelope-fill"></i>
        </div>

        <button type="submit" class="btn-submit">Se connecter</button>
      </form>

      <form id="register-form" class="hidden" method="POST" action="register_process.php" aria-labelledby="btn-register" novalidate>
        <h2>Créer un compte</h2>
        <div class="row g-3">
            <div class="col-md-6">
              <div class="form-floating position-relative mb-3">
                <input type="text" id="nom" name="nom" placeholder="" required>
                <label for="nom">Nom</label>
                <i class="bi bi-person-fill"></i>
              </div>
              <div class="form-floating position-relative mb-3">
                <input type="text" id="prenom" name="prenom" placeholder="" required>
                <label for="prenom">Prénom</label>
                <i class="bi bi-person-lines-fill"></i>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sexe" id="sexeM" value="M" required>
                <label class="form-check-label text-white" for="sexeM">Masculin</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sexe" id="sexeF" value="F" required>
                <label class="form-check-label text-white" for="sexeF">Féminin</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating position-relative mb-3">
                <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                <label for="date_naissance" class="text-white">Date de naissance</label>
                <i class="bi bi-calendar-date"></i>
              </div>
              <div class="form-floating position-relative mb-3">
                <input type="text" id="cni" name="cni" placeholder="" required>
                <label for="cni">Numéro de CNI</label>
                <i class="bi bi-credit-card-2-front"></i>
              </div>
              <div class="form-floating position-relative mb-3">
                <input type="tel" id="telephone" name="telephone"  required>
                <label for="telephone">Téléphone</label>
                <i class="bi bi-telephone-fill"></i>
              </div>
            </div>
            <div class="col-12">
              <div class="form-floating position-relative mb-3">
                <input type="email" id="email" name="email" placeholder="" required>
                <label for="email">Email</label>
                <i class="bi bi-envelope-fill"></i>
              </div>
            </div>
        </div>

        <button type="submit" class="btn-submit">S'inscrire</button>
      </form>
    </div>
  </div>

  <script>
    const btnLogin = document.getElementById('btn-login');
    const btnRegister = document.getElementById('btn-register');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    function showLogin() {
      btnLogin.classList.add('active');
      btnLogin.setAttribute('aria-selected', 'true');
      btnLogin.setAttribute('tabindex', '0');
      btnRegister.classList.remove('active');
      btnRegister.setAttribute('aria-selected', 'false');
      btnRegister.setAttribute('tabindex', '-1');
      loginForm.classList.remove('hidden');
      loginForm.classList.add('visible');
      registerForm.classList.remove('visible');
      registerForm.classList.add('hidden');
    }

    function showRegister() {
      btnRegister.classList.add('active');
      btnRegister.setAttribute('aria-selected', 'true');
      btnRegister.setAttribute('tabindex', '0');
      btnLogin.classList.remove('active');
      btnLogin.setAttribute('aria-selected', 'false');
      btnLogin.setAttribute('tabindex', '-1');
      registerForm.classList.remove('hidden');
      registerForm.classList.add('visible');
      loginForm.classList.remove('visible');
      loginForm.classList.add('hidden');
    }

    btnLogin.addEventListener('click', showLogin);
    btnRegister.addEventListener('click', showRegister);

    // Accessibility: allow keyboard navigation
    btnLogin.addEventListener('keydown', e => {
      if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
        e.preventDefault();
        showRegister();
        btnRegister.focus();
      }
    });
    btnRegister.addEventListener('keydown', e => {
      if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
        e.preventDefault();
        showLogin();
        btnLogin.focus();
      }
    });
  </script>
</body>
</html>
