<!--Page Init-->
<?php
require_once('db.php');

if (isset($_SESSION['error'])) {
  echo "<script>alert('" . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') . "');</script>";
  unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change User Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="home.php">To-Do-List</a>
      <button
        class="navbar-toggler"
        type="button"
        data-mdb-toggle="collapse"
        data-mdb-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link mx-2" href="insert.php"><i class="fas fa-plus-circle pe-2"></i>Add List</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center"
              href="#"
              id="navbarDropdownMenuLink"
              role="button"
              data-mdb-toggle="dropdown"
              aria-expanded="false">
              <img
                src="user.png"
                class="rounded-circle"
                height="30"
                alt=""
                loading="lazy" />
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="view_user.php">My profile</a></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <form method="post" class="was-validated">
      <div class="mb-3 ms-2">
        <div id="error_status"></div>
        <h1>Change User Password</h1>
        <input class="form-control" type="email" name="email" placeholder="Email Address" required />
        <div class="invalid-feedback">
          Please provide your Email Address.
        </div><br />
        <input class="form-control" type="password" name="change_password" placeholder="New Password" required />
        <div class="invalid-feedback">
          Please provide a New Password.
        </div><br />
        <button type="submit" name="submit_change_password" class="btn btn-dark" data-mdb-ripple-init>Submit</button>
      </div>
    </form>
  </div>
  <?php
  if (isset($_POST['submit_change_password'])) {
    $email = $_POST['email'];

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the user exists
    $checkRecords = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $db->prepare($checkRecords);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {

      if (!empty($_POST['change_password'])) {
        $password = $_POST['change_password'];
        $encrypted_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':password', $encrypted_password, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
      }

      $_SESSION['message'] = "Password changed successfully!";
      header('location: login.php');
      exit;
    } else {
      $_SESSION['error'] = "Email not found";
      header('location: forgot_password.php');
    }

    $db = null;
  }
  ?>
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