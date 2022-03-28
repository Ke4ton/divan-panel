<?php

require_once '../../controllers/DbController.php';
include_once '../../controllers/UserController.php';
include_once '../../controllers/CheatController.php';

$UserCookie = login($_COOKIE['login'], $_COOKIE['password']);

if (!$UserCookie) {
    return header('Location: /index.php');
} else {
    $userRole = getUserInfo($_COOKIE['login']);
    if ($userRole['role'] == "admin" || $userRole['role'] == "renter") {
        // nothing
    } else {
        return header('Location: index.php');
    }
}

$userInfo = getUserInfo($_COOKIE['login']);

$allCheats = getAllCheats();

if (isset($_GET['type'])) {
    if ($_GET['type'] == "create") {
        сreateNewCheat($_COOKIE['login'], $_GET['name'], $_GET['dll_name'], $_GET['process'], false);
    } else if ($_GET['type'] == "freeze") {
        freezeCheat($_GET['id']);
    } else if ($_GET['type'] == "unfreeze") {
        unfreezeCheat($_GET['id']);
    } else if ($_GET['type'] == "delete") {
        deleteCheat($_GET['id']);
    }

    return header("Location: ../admin/cheats");
}

if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload') {
    
}
?>

<!doctype html>
<html lang="en">
<?php include '../content/header.php'; ?>
<title>Cheats | Divan Technologies</title>
<link rel="icon" type="image/png" href="https://divan-technologies.ru/favicon.ico"/>

<body class="theme-dark">
    <div class="wrapper">

        <?php include '../content/navigation.php'; ?>

        <div class="content">
            <div class="container-xl">

                <div class="row row-cards">

                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Create new cheat</h3>
                            </div>
                            <div class="card-body">
                                <form action="cheats.php" method="GET">
                                    <div class="mb-3">
                                        <div class="form-group mb-3 ">

                                            <label class="form-label">Cheat Name</label>
                                            <input type="text" name="name" class="form-control" autocomplete="off">
                                        </div>

                                        <div class="form-group mb-3 ">
                                            <label class="form-label">Process Name</label>
                                            <input type="text" name="process" class="form-control" autocomplete="off">
                                        </div>

                                        <div class="form-group mb-3 ">
                                            <label class="form-label">Dll Name</label>
                                            <input type="text" name="dll_name" class="form-control" autocomplete="off">
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" name="isUsermode" type="checkbox" value="Yes" id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">Usermode [indev]</label>
                                            <input type="hidden" name="type" value="create">
                                        </div>

                                    </div>
                                    <button type="submit" class="btn btn-primary ms-auto">Create</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Cheats</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Process</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                            <th>File</th>
                                            <th>Owner</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($userInfo['role'] == "admin") {
                                            if (is_array($allCheats)) {
                                                foreach ($allCheats as $cheat) { ?>
                                                    <tr>
                                                        <td style="font-size: 12px;">
                                                            <?php echo $cheat['id']; ?>
                                                        </td>
                                                        <td style="font-size: 12px;">
                                                            <?php echo $cheat['name']; ?>
                                                        </td>
                                                        <td style="font-size: 12px;">
                                                            <?php echo $cheat['process']; ?>
                                                        </td>
                                                        <td style="font-size: 12px;">
                                                            <?php echo $cheat['status']; ?>
                                                        </td>
                                                        <td style="font-size: 12px;">
                                                            <a href="../admin/cheats.php?type=freeze&id=<?php echo $cheat['id']; ?>" class="badge bg-blue-lt">Freeze</a>

                                                            <a href="../admin/cheats.php?type=unfreeze&id=<?php echo $cheat['id']; ?>" class="badge bg-green-lt">Unfreeze</a>

                                                            <a href="../admin/cheats.php?type=delete&id=<?php echo $cheat['id']; ?>" class="badge bg-red-lt">Delete</a>
                                                        </td>
                                                        <td style="font-size: 12px;">
                                                            <div>
                                                                <form method="POST" action="upload.php" enctype="multipart/form-data">

                                                                    <input type="file" name="govno" />
                                                                    <input type="submit" name="uploadBtn" value="Upload" />
                                                                </form>
                                                            </div>

                                                        </td>
                                                        <td style="font-size: 12px;">
                                                            <?php echo $cheat['creator']; ?>
                                                        </td>
                                                    </tr>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                        <?php
                                        if ($userInfo['role'] == "renter") {
                                            if (is_array($allCheats)) {
                                                foreach ($allCheats as $cheat) {
                                                    if ($cheat['creator'] == $_COOKIE['login']) {
                                        ?>
                                                        <tr>
                                                            <td style="font-size: 12px;">
                                                                <?php echo $cheat['id']; ?>
                                                            </td>
                                                            <td style="font-size: 12px;">
                                                                <?php echo $cheat['name']; ?>
                                                            </td>
                                                            <td style="font-size: 12px;">
                                                                <?php echo $cheat['process']; ?>
                                                            </td>
                                                            <td style="font-size: 12px;">
                                                                <?php echo $cheat['status']; ?>
                                                            </td>
                                                            <td style="font-size: 12px;">
                                                                <a href="../admin/cheats.php?type=freeze&id=<?php echo $cheat['id']; ?>" class="badge bg-blue-lt">Freeze</a>

                                                                <a href="../admin/cheats.php?type=unfreeze&id=<?php echo $cheat['id']; ?>" class="badge bg-green-lt">Unfreeze</a>

                                                                <a href="../admin/cheats.php?type=delete&id=<?php echo $cheat['id']; ?>" class="badge bg-red-lt">Delete</a>
                                                            </td>
                                                            <td style="font-size: 12px;">
                                                                <div>
                                                                    <input type="file" name="uploadedFile" />
                                                                    <input type="submit" name="uploadBtn" value="Upload" />

                                                                </div>

                                                            </td>
                                                            <td style="font-size: 12px;">
                                                                <?php echo $cheat['creator']; ?>
                                                            </td>
                                                        </tr>
                                        <?php       }
                                                }
                                            }
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include '../content/footer.php'; ?>
        </div>
    </div>
</body>

</html>