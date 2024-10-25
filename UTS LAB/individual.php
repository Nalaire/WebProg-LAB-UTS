<!--Page Init-->
<?php
require_once('db.php');
session_start();
//check session
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do-List</title>
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
    <br />
    <br />

    <!--Verify page list and access-->
    <?php
    $validPage = true;
    $integer_pattern = '/[0123456789]/';

    $list_id_str = strval($_GET['list_id']);
    if (preg_match($integer_pattern, $list_id_str)) {
        $list_id = intval($list_id_str);
    } else {
        $validPage = false;
    }

    //access validation.
    if ($validPage) {
        $validAccess = true;
        $user_id = $_SESSION['user_id'];
        $sql = 'SELECT * FROM to_do_lists WHERE list_id = ? AND user_id = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$list_id, $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $validAccess = false;
        }
    }
    ?>

    <!--Parse search option-->
    <?php
    if (isset($_GET['filter']) && isset($_GET['search'])) { //if set
        $search = strval($_GET['search']);
        $filter_get = strval($_GET['filter']);
        if ($filter_get == 'finished') {
            $filter = 'finished';
        } else if ($filter_get == 'unfinished') {
            $filter = 'unfinished';
        } else {
            $filter = 'all';
        }
    } else {
        $filter = 'all';
        $search = '';
    }
    ?>

    <?php
    if ($validPage) {
        if ($validAccess) {
    ?>
            <!--Content of the Page-->
            <div class="d-flex flex-column justify-content-center align-items-center">

                <!--List Head-->
                <div id="list-head" class="d-flex flex-column justify-content-center align-items-center">
                    <h3 class="mt-4 mb-1 p-0"><?= $result['list_name'] ?></h3>
                    <div id="add-task" class="m-1">
                        <form action="task_insert.php" method="post" class="d-flex flex-row">
                            <input type="hidden" name="list_id" value="<?= $list_id ?>">
                            <input type="text" class="form-control m-1" placeholder="Task Name" name="task_name" />
                            <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Add Task</button>
                        </form>
                    </div>

                    <!--Search and Filter-->
                    <div id="search-filter" class="m-1 p-1 bg-info bg-opacity-10 d-flex flex-column">
                        <form action="individual.php" method="get" class="d-flex flex-row">
                            <div class="m-1">
                                <input type="hidden" name="list_id" value="<?= $list_id ?>">
                                <input type="text" class="form-control" placeholder="Search Bar" name="search" />
                            </div>
                            <div class="m-1">
                                <select class="form-select" name="filter" id="filter">
                                    <option value="all">All</option>
                                    <option value="finished">Finished</option>
                                    <option value="unfinished">Unfinished</option>
                                </select>
                            </div>
                            <div class="m-1">
                                <button type="submit" class="btn btn-info text-white" data-mdb-ripple-init>
                                    Search
                                </button>
                            </div>
                        </form>
                        <?php
                        if (isset($_GET['filter']) && isset($_GET['search'])) {
                            echo '<p>Search result:</p>';
                        }
                        ?>
                    </div>

                </div>

                <!--list-->
                <div id="content" class="w-50 p-2 bg-secondary bg-opacity-10" style="min-width: 450px; max-width: 700px;">
                    <?php
                    //filter options
                    if ($filter == 'finished') {
                        $sql = 'SELECT * FROM tasks WHERE list_id = ? AND task_name REGEXP ? AND task_completion = 1';
                    } else if ($filter == 'unfinished') {
                        $sql = 'SELECT * FROM tasks WHERE list_id = ? AND task_name REGEXP ? AND task_completion = 0';
                    } else {
                        $sql = 'SELECT * FROM tasks WHERE list_id = ? AND task_name REGEXP ?';
                    }

                    //fetching list
                    $stmt = $db->prepare($sql);
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $stmt->execute([$list_id, $search]);
                    $data = $stmt->fetchAll();

                    //if list is empty
                    if (!$data) {
                    ?>
                        <strong>No Task Detected</strong>
                        <?php
                    } else {
                        $numbering = 1;
                        foreach ($data as $d) {
                            $status = 'bg-secondary';
                            if ($d['task_completion']) {
                                $status = 'bg-success';
                            }
                        ?>
                            <!--List is not empty-->
                            <div id="alert-box"></div>
                            <div class="m-1 bg-white">
                                <div class="<?= $status ?> bg-opacity-25 p-1 d-flex flex-row justify-content-between">
                                    <div class="d-flex flex-row">
                                        <div class="m-1 p-1 bg-opacity-25 bg-secondary">
                                            <?= $numbering ?>
                                        </div>
                                        <div class="m-1 p-1" style="max-width: 100%;">
                                            <?= $d['task_name'] ?>
                                        </div>
                                    </div>
                                    <div class="my-auto mx-1 d-flex flex-row">
                                        <div class="mx-1">
                                            <form action="task_tick.php" method="post">
                                                <input type="hidden" name="task_id" value="<?= $d['task_id']?>" readonly>
                                                <button type="submit" class="btn btn-sm btn-primary mt-1">Finish/Unfinish</button>
                                            </form>
                                        </div>
                                        <div class="mx-1">
                                            <button onclick="alert_box_warning('<?= $d['task_id']?>', '<?= $d['task_name']?>')" class="btn btn-sm btn-warning mt-1">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                            $numbering++;
                        }
                    }
                    ?>
                </div>

            </div> <!--End of Page-->
        <?php
        } else {
        ?>
            <!--if user does not have access-->
            <div id="content" class="w-75 mx-auto my-4 p-2 bg-secondary bg-opacity-10">
                <strong>You don't have access to this list.</strong>
            </div>
        <?php
        }
    } else {
        ?>
        <!--if list id is invalid-->
        <div id="content" class="w-75 mx-auto my-4 p-2 bg-secondary bg-opacity-10">
            <strong>Invalid list ID.</strong>
        </div>
    <?php
    }
    ?>



    <script>
        //alert placement
        var alert_element = document.getElementById('alert-box');

        //close the alert box
        function close_alert_box() {
            alert_element.innerHTML = '';
        };

        //opening the alert box
        function alert_box_warning(task_id, task_name) {
        var task_id_str = task_id.toString();
        var task_name_str = task_name.toString();

        alert_element.innerHTML = '<div class="bg-success alertCover">asdf</div>';

        alert_element.innerHTML = '<div class="alertCover bg-dark bg-opacity-50">' +
        '<div class="bg-white rounded position-fixed top-50 start-50 translate-middle alertBox">' +
        '<div class="w-100 h-100 p-2 d-flex flex-column">' +
        '<div class="d-flex flex-row justify-content-between m-1">' +
        "<h4>Delete " + task_name_str + "?</h4>" +
        '<button onclick="close_alert_box()" class="btn btn-sm btn-secondary">&times</button>' +
        '</div>' +
        '<div class="d-flex flex-row justify-content-between m-1">' +
        "<form action='task_delete.php' method='post'>" +
        '<input type="hidden" readonly name="task_id" value=' + task_id_str + ' />' +
        '<button type="submit" class="btn btn-danger">Yes, delete this task</button>' +
        '</form>' +
        "<button onclick='close_alert_box()' class='btn btn-warning'>No, don't delete this task</button>" +
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