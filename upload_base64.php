<?php
session_start();
ob_start();

// Require the Composer autoloader.
require_once __DIR__ . '/./vendor/autoload.php';
require_once __DIR__ . '/./inc/upload_base64.php';

?>

<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>העלאת קבצים ל- AWS S3</title>

    <!-- Bootstrap core-->
    <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" />
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <h1 class="mb-3">העלאת קבצים ל- AWS S3</h1>

                <?php if ($statusMsg) {
                    if ($status === 'success') { ?>
                        <div class="alert alert-success"><?= $statusMsg ?></div>
                    <?php } else { ?>
                        <div class="alert alert-danger"><?= $statusMsg ?></div>
                <?php }
                } ?>

                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><b>הדבק קובץ בפורמט של Base64:</b></label>
                        <textarea name="file_upload" class="form-control" rows="10" required></textarea>
                    </div>
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-primary" name="submit" value="Upload">שלח טופס</button>
                    </div>
                </form>

                <div id="file-list" class="mt-5">
                    <h3>רשימת קבצים שהועלו</h3>
                    <?php include __DIR__ . '/./inc/file_list.php' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Bootstrap 5 -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Cuustom Script -->
    <script type="text/javascript" src="<?= public_url('js/index.js?v=' . time()) ?>"></script>
</body>

</html>

<?php
ob_end_flush();
