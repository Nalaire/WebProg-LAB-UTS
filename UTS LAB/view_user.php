<!--Page Init-->
<?php
require_once('db.php');
session_start();
//check session
if (!isset($_SESSION['user_id'])) {
  header('location: login.php');
  exit;
}

if (isset($_SESSION['message'])) {
  echo "<script>alert('" . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . "');</script>";
  unset($_SESSION['message']);
}
$sql = "SELECT user_name, email FROM users";

$hasil = $db->query($sql);

$sql = "SELECT user_name, email FROM users WHERE user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
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
    <div class="mb-3 ms-2">
      <h1>User Profile</h1>
      <?php if ($user): ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr>
              <th>Username</th>
              <th>Email</th>
            </tr>
            <tr>
              <td><?= htmlspecialchars($user['user_name']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
            </tr>
          </table>
        <?php else: ?>
          <p>User data not found.</p>
        <?php endif; ?>
        </table>
        </div>
        <a class="btn btn-dark" data-mdb-ripple-init href="edit_user.php">Edit Profile</a><br /><br />
        <a class="btn btn-dark" data-mdb-ripple-init href="change_password.php">Change Password</a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.0/mdb.min.js"></script>
</body>

</html>