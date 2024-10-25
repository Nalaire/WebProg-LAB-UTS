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
  <title>User Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
    <form action="register_process.php" method="post" class="was-validated">
      <div class="mb-3 ms-2">
        <div id="error_status">
        </div>
        <h1>User Registration</h1>
        <input type="text" class="form-control" name="username" placeholder="Username" required />
        <div class="invalid-feedback">
          Please choose a Username.
        </div><br />
        <input type="email" class="form-control" name="email" placeholder="Email Address" required />
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
      <p class="my-auto me-2">Already have an account?</p>
      <a href="login.php"><button class="btn btn-dark">Login</button></a>
    </div>
  </div>




  <!--alert-->
  <script>
    error_status = <?php
                    if ($_GET['error_status'] == 1) {
                      $status = 1;
                    } else {
                      $status = 0;
                    }
                    echo $status;
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