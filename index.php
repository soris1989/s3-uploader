<?php
session_start();
ob_start();

// Require the Composer autoloader.
require __DIR__ . '/./vendor/autoload.php';

use Inc\Helpers\AwsS3;

$statusMsg = '';
$status = 'danger';

// Instantiate an Amazon S3 client 
$s3 = new AwsS3();

if (isset($_POST['delete_file'])) {
    $key = trim($_POST['key']);
    // delete obkect from aws s3
    $success = $s3->deleteObject($key);

    if ($success) {
        $status = 'success';
        $statusMsg = "הקובץ נמחק בהצלחה מ- AWS S3";
    } else {
        $statusMsg = $s3->getMessage();
    }
}

if (isset($_POST['submit'])) {
    // Check whether user inputs are empty 
    if (!empty($_FILES["file_upload"]["name"])) {
        // File info 
        $file_name = basename($_FILES["file_upload"]["name"]);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allow certain file formats 
        $allowTypes = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'png', 'jpeg', 'gif');
        if (in_array($file_ext, $allowTypes)) {
            // File temp source 
            $file_temp_src = $_FILES["file_upload"]["tmp_name"];

            if (is_uploaded_file($file_temp_src)) {
                // Upload file to S3 bucket 
                $result = $s3->putObject($file_name, $file_temp_src);
                if ($result) {
                    $result_arr = $result->toArray();

                    if (!empty($result_arr['ObjectURL'])) {
                        $s3_file_link = $s3->getPresignedObjectUrl($file_name);
                    } else {
                        $api_error = 'העלאה נכשלה! כתובת אובייקט ב-AWS S3 לא נמצאה.';
                    }
                } else {
                    $api_error = $s3->getMessage();
                }

                if (empty($api_error)) {
                    $status = 'success';
                    $statusMsg = "הקובץ הועלה בהצלחה ל- AWS S3";
                } else {
                    $statusMsg = $api_error;
                }
            } else {
                $statusMsg = "העלאת הקובץ נכשלה!";
            }
        } else {
            $statusMsg = 'הקובץ חייב להיות באחד מהפורמטים הבאים: Pdf/Word/Excel/Image.';
        }
    } else {
        $statusMsg = 'לא נבחר קובץ להעלאה.';
    }
}

// get list of existing objects is bucket
$objects = $s3->listObjects();

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
                        <label><b>בחר קובץ:</b></label>
                        <input type="file" name="file_upload" class="form-control" required>
                    </div>
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-primary" name="submit" value="Upload">שלח טופס</button>
                    </div>
                </form>

                <h3 class="mt-5">רשימת קבצים שהועלו</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                שם קובץ
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($objects['Contents'] as $object) { ?>
                            <tr>
                                <td><?= $object['Key'] ?></td>
                                <td class="d-flex">
                                    <a class="btn btn-link btn-sm" href="<?= $s3->getPresignedObjectUrl($object['Key']) ?>" target="_blank" class="aws-s3-link">פתח</a>
                                    <form action="" method="post" class="ms-2">
                                        <input type="hidden" name="delete_file">
                                        <input type="hidden" name="key" value="<?= $object['Key'] ?>">
                                        <button type="submit" class="btn btn-link btn-sm">מחק</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
