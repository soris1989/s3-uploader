<?php

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
        $allowTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'png', 'jpeg', 'gif', 'svg'];
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
