
<section class="mt-5">
    <div class="container mt-5">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title">Registratie</h3>
            <div id="registrationMessage"></div>
            <form id="registrationForm" method="post">
              <div class="mb-3">
                <label for="email" class="form-label">E-mailadres</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Wachtwoord</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <button type="submit" class="btn btn-primary">Registreren</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title">Inloggen</h3>
              <div id="loginMessage"></div>
            <form id="loginForm" method="post">
              <div class="mb-3">
                <label for="loginEmail" class="form-label">E-mailadres</label>
                <input type="email" class="form-control" id="loginEmail" name="loginEmail" required>
              </div>
              <div class="mb-3">
                <label for="loginPassword" class="form-label">Wachtwoord</label>
                <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
              </div>
              <button type="submit" class="btn btn-primary">Inloggen</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="/js/login.js"></script>
<script src="/js/registration.js"></script>
