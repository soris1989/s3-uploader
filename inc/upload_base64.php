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
    // get data from request body
    $file_base64 = trim($_POST['file_base64']);

    // Check whether user inputs are empty 
    if ($file_base64) {
        if (is_datauri($file_base64)) {
            [$base64, $mime_type] = get_datauri_data($file_base64);

            // File info 
            $file_ext = mime2ext($mime_type);
            $file_name = 'file_' . time() . ".$file_ext";

            // Allow certain file formats 
            $allowTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'png', 'jpeg', 'gif', 'svg'];
            if (in_array($file_ext, $allowTypes)) {

                // Upload file to S3 bucket 
                $result = $s3->putObjectBase64($file_name, $base64);
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
                $statusMsg = 'הקובץ חייב להיות באחד מהפורמטים הבאים: Pdf/Word/Excel/Image.';
            }
        } else {
            $statusMsg = 'קובץ לא בפורמט של Base64.';
        }
    } else {
        $statusMsg = 'לא נבחר קובץ להעלאה.';
    }
}

// get list of existing objects is bucket
$objects = $s3->listObjects();
