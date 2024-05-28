<?php
// Require the Composer autoloader.
require __DIR__ . '/./vendor/autoload.php';

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

// Amazon S3 API credentials 
$access_key_id = $_ENV['S3_ACCESS_KEY_ID'];
$secret_access_key = $_ENV['S3_SECRET_ACCESS_KEY'];
$version = $_ENV['S3_VERSION'];
$region = $_ENV['S3_REGION'];
$bucket = $_ENV['S3_BUCKET'];

$statusMsg = '';
$status = 'danger';

if (isset($_POST['submit'])) {
    // Check whether user inputs are empty 
    if (!empty($_FILES["file_upload"]["name"])) {
        // File info 
        $file_name = basename($_FILES["file_upload"]["name"]);
        $file_type = pathinfo($file_name, PATHINFO_EXTENSION);

        // Allow certain file formats 
        $allowTypes = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'png', 'jpeg', 'gif');
        if (in_array($file_type, $allowTypes)) {
            // File temp source 
            $file_temp_src = $_FILES["file_upload"]["tmp_name"];

            if (is_uploaded_file($file_temp_src)) {
                // Instantiate an Amazon S3 client 
                $s3 = new S3Client([
                    'version' => $version,
                    'region'  => $region,
                    'credentials' => [
                        'key'    => $access_key_id,
                        'secret' => $secret_access_key,
                    ]
                ]);

                // Upload file to S3 bucket 
                try {
                    $result = $s3->putObject([
                        'Bucket' => $bucket,
                        'Key'    => $file_name,
                        'ACL'    => 'public-read',
                        'SourceFile' => $file_temp_src
                    ]);
                    $result_arr = $result->toArray();

                    if (!empty($result_arr['ObjectURL'])) {
                        $s3_file_link = $result_arr['ObjectURL'];
                    } else {
                        $api_error = 'העלאה נכשלה! כתובת אובייקט ב-AWS S3 לא נמצאה.';
                    }
                } catch (S3Exception $e) {
                    $api_error = $e->getMessage();
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
                <?php if ($statusMsg) {
                    if ($status === 'success') { ?>
                        <div class="alert alert-success"><?= $statusMsg ?></div>
                        <div><a href="<?= $s3_file_link ?>" target="_blank"><?= $s3_file_link ?></a></div>
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
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>