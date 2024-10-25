<!--Page Init-->
<?php
require_once('db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

if (isset($_SESSION['message'])) {
  echo "<script>alert('" . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . "');</script>";
  unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>To Do List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    /* Custom CSS for alert box */
    .alertCover {
      position: fixed;
      padding: 0;
      margin: 0;

      top: 0;
      left: 0;

      width: 100%;
      height: 100%;
    }

    .alertBox {
      width: 75%;
      max-width: 500px;
      height: auto;
      max-height: 300px;
    }
  </style>
</head>

<body>
  <!--Navbar-->
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
            <a
              class="nav-link dropdown-toggle d-flex align-items-center"
              href="#"
              id="navbarDropdownMenuLink"
              role="button"
              data-mdb-toggle="dropdown"
              data-mdb-auto-close="true"
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
  <!--End of Navbar-->

  <!--Dummy for Navbar Empty Starting Space-->
  <br />
  <br />

  <!--Alert box placement-->
  <div id="alert-box">
  </div>

  <!--Content Page-->
  <div id="content-page" class="w-100 d-flex justify-content-center">

    <!--Content of the page-->
    <div id="content" class="w-75 my-4 p-2 bg-secondary bg-opacity-10">

      <!--Getting the list-->
      <?php
      //query the data
      $stmt = $db->prepare("SELECT * FROM to_do_lists WHERE user_id = ? GROUP BY list_id ORDER BY list_id ASC");
      $user_id = $_SESSION['user_id'];
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $stmt->execute([$user_id]);

      //fetching and printing the data
      $data = $stmt->fetchAll();
      if (!$data) {
        echo "<strong>You don't have a list yet : (</strong>";
      }
      foreach ($data as $d) {
      ?>
        <div class="m-4 bg-white d-flex flex-row justify-content-between">
          <!--to do list: name-->
          <div class="m-2 d-flex flex-column">
            <strong class="m-1"><?= $d['list_name'] ?></strong>
            <a href="individual.php?list_id=<?= $d['list_id'] ?>">
              <button class="btn btn-primary m-1">More Details</button>
            </a>
          </div>

          <!--to do list: config-->
          <div class="m-2 d-flex flex-column">
            <a href="list_edit.php?list_id=<?= $d['list_id'] ?>" class="m-1">
              <button class="btn btn-warning w-100">Edit List</button>
            </a>
            <div class="m-1">
              <button onclick="alert_box_warning('<?= $d['list_id'] ?>', '<?= $d['list_name'] ?>')" class="btn btn-danger w-100">Delete List</button>
            </div>
          </div>
        </div>
      <?php
      }
      ?>

    </div>
  </div>


  <script>
    var alert_element = document.getElementById('alert-box'); //alert box element

    //close alert box
    function close_alert_box() {
      alert_element.innerHTML = '';
    };

    //Delete warning
    function alert_box_warning(list_id, list_name) {
      var list_id_str = list_id.toString();
      var list_name_str = list_name.toString();

      alert_element.innerHTML = '<div class="bg-success alertCover">asdf</div>';

      alert_element.innerHTML = '<div class="alertCover bg-dark bg-opacity-50">' +
        '<div class="bg-white rounded position-fixed top-50 start-50 translate-middle alertBox">' +
        '<div class="w-100 h-100 p-2 d-flex flex-column">' +
        '<div class="d-flex flex-row justify-content-between m-1">' +
        "<h4>Delete " + list_name_str + "?</h4>" +
        '<button onclick="close_alert_box()" class="btn btn-sm btn-secondary">&times</button>' +
        '</div>' +
        '<div class="d-flex flex-row justify-content-between m-1">' +
        "<form action='list_delete_process.php' method='post'>" +
        '<input type="hidden" readonly name="list_id" value=' + list_id_str + ' />' +
        '<button type="submit" class="btn btn-danger">Yes, delete this list</button>' +
        '</form>' +
        "<button onclick='close_alert_box()' class='btn btn-warning'>No, don't delete this list</button>" +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.0/mdb.min.js"></script>
</body>

</html>