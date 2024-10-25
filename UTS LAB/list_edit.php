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
    <title>Edit To-Do-List</title>
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

    <?php
    if ($validPage) {
        if ($validAccess) {
    ?>
            <!--Content of the Page-->
            <div class="d-flex flex-column justify-content-center align-items-center">

                <!--Form for Edit-->
                <div id="content" class="w-50 my-4 p-2 bg-secondary bg-opacity-10 rounded">
                    <form action="list_edit_process.php" method="post" class="was-validated">
                        <div class="mb-3 ms-2">
                            <input type="hidden" name="list_id" value="<?= $list_id ?>">
                            <h1>Change List Name</h1>
                            <input type="text" class="form-control" name="list_name" placeholder="New List Name" required />
                            <div class="invalid-feedback">
                                Please input the New List Name.
                            </div><br />
                            <div class="d-flex flex-row">
                                <button type="submit" name="submitform" class="m-2 btn btn-primary" data-mdb-ripple-init>Submit</button>
                                <a href="home.php" class="m-2"><button class="btn btn-warning">Cancel</button></a>
                            </div>
                        </div>
                    </form>
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



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.0/mdb.min.js"></script>
</body>

</html>