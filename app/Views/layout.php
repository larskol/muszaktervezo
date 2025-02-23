<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteTitle ?? lang('Site.sitePageTitle') ?></title>
    <link rel="icon" href="/icon.png">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css">
    <script src="/assets/js/jquery.js"></script>
    <script src="/assets/js/popper.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/custom.css">
    <script src="/assets/js/custom.js"></script>

</head>

<body>
    <div id="main-wrapper">
        <?= (isset($route) && $route !== false) ? view_cell('\App\Controllers\BaseController::showSidebar', $route) : "" ?>
        <div class="container-fluid" id="content-wrapper">
        <div class="row" id="topbar-wrapper">
            <div class="col-lg-6 col-12 topbar-left"><?= $userLoginInfo ?? "" ?></div>
            <div class="col-lg-6 col-12 topbar-right"><?= $departmentSelector ?? "" ?></div>
        </div>
            <div class="row">
                <div class="col-lg-9 mx-auto">
                    <?= isset($content) ? $content : "" ?>
                </div>
            </div>            
        </div>
    </div>
</body>
</html>