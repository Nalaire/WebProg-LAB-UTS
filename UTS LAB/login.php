<?php
//page init
require_once('db.php');
session_start();
//check session
if (isset($_SESSION['user_id'])) {
  header('location: home.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Log in</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
    <form action="login_process.php" method="post" class="was-validated">
      <div class="mb-3 ms-2">
        <div id="error_status"></div>
        <h1>User Log in</h1>
        <input type="text" class="form-control" name="email" placeholder="Email Address" required />
        <div class="invalid-feedback">
          Please provide an Email Address.
        </div><br />
        <input type="password" class="form-control" name="password" placeholder="Password" required />
        <div class="invalid-feedback">
          Please provide a Password.
        </div><br />
        <button type="submit" name="submitform" class="btn btn-dark" data-mdb-ripple-init>Submit</button>
      </div>
    </form>
    <div class="d-flex">
      <p class="my-auto ms-4 me-2">Don't have an account?</p>
      <a href="register.php"><button class="btn btn-dark">Register</button></a>
    </div><br />
    <div class="d-flex">
      <p class="my-auto ms-5 me-2">Forgot Password?</p>
      <a href="forgot_password.php"><button class="btn btn-dark">Change Password</button></a>
    </div>
  </div>


  <!--alert box-->
  <script>
    error_status = <?php
                    //parse the $_GET variable
                    $error_status = $_GET['error'];
                    if ($error_status == 1) {
                      echo '1';
                    } else if ($error_status == 2) {
                      echo '2';
                    } else {
                      echo '0';
                    }
                    ?>;

    if (error_status == 1) {
      // Create a new div element
      const alertDiv = document.createElement("div");
      alertDiv.className = "alert alert-danger";
      alertDiv.role = "alert";
      alertDiv.textContent = "User  Not Found";

      // Clear previous messages and append the new alert
      const errorStatusElement = document.getElementById("error_status");
      errorStatusElement.innerHTML = "";
      errorStatusElement.appendChild(alertDiv);
    } else if (error_status == 2) {
      // Create a new div element
      const alertDiv = document.createElement("div");
      alertDiv.className = "alert alert-danger";
      alertDiv.role = "alert";
      alertDiv.textContent = "Password Does Not Match";

      // Clear previous messages and append the new alert
      const errorStatusElement = document.getElementById("error_status");
      errorStatusElement.innerHTML = "";
      errorStatusElement.appendChild(alertDiv);
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.0/mdb.min.js"></script>
</body>

</html>