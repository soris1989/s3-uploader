<?php

function styled_print_r_exit($arr)
{
    styled_print_r($arr);
    exit;
}

function styled_var_dump_exit($data)
{
    styled_var_dump($data);
    exit;
}

function echo_br($str)
{
    echo $str . '<br>';
}

function plotJson($data)
{
    header('Content-Type: application/json');
    echo json_decode(json_encode($data), true);
    exit();
}

function getParamaterizedValuesString()
{
    $str = str_repeat('?,', 26);
    return substr($str, 0, strlen($str));
}

// function db_connect()
// {
//     // db connection params
//     $host = config('mysql/host');
//     $db = config('mysql/db_name');
//     $username = config('mysql/username');
//     $password = config('mysql/password');
//     $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

//     try {
//         $pdo = new PDO($dsn, $username, $password);
//         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         return $pdo;
//     } catch (PDOException $e) {
//         throw $e;
//     }
// }

function format_added_time(int $seconds, $format = 'Y-m-d H:i:s')
{
    return date($format, strtotime("+$seconds sec"));
}

function is_outdated($date)
{
    $now = strtotime('now'); //gives value in Unix Timestamp (seconds since 1970)
    $expires_at = strtotime($date);
    return $now > $expires_at;
}

function has_query_param($param)
{
    return isset($_GET[$param]) && $_GET[$param];
}

function generate_random_string($length = 10)
{
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

function create_dir($dir)
{
    if (!is_dir($dir) && !mkdir($dir, 0777, true)) {
        exit("Error creating folder $dir");
    }
}

function delete_file($file_path)
{
    if (file_exists($file_path)) {
        chmod($file_path, 0755); //Change the file permissions if allowed
        // delete file
        $is_file_deleted = unlink($file_path);
        if (!$is_file_deleted) return false;

        // // delete file if empty
        // $dirname = dirname($file_path);
        // $isDirEmpty = !(new \FilesystemIterator($dirname))->valid();
        // if ($isDirEmpty) {
        //     return rmdir($dirname);
        // }
    }
    return false;
}

function array_filter_shift(array &$array, callable $callback)
{
    $filteredItems = [];
    $indexes = [];

    $i = 0;
    foreach ($array as $item) {
        if (call_user_func($callback, $item)) {
            $filteredItems[] = $item;
            $indexes[] = $i;
        }
        $i++;
    }

    foreach ($indexes as $index) {
        unset($array[$index]);
    }

    $array = array_values($array);

    return $filteredItems;
}

function array_find_index(array $array, callable $callback)
{
    $i = 0;
    foreach ($array as $item) {
        if (call_user_func($callback, $item)) {
            return $i;
        }
        $i++;
    }
    return false;
}

function array_find(array $array, callable $callback)
{
    foreach ($array as $item) {
        if (call_user_func($callback, $item)) {
            return $item;
        }
    }
    return false;
}

function array_some(array $array, callable $callback)
{
    foreach ($array as $item) {
        if (call_user_func($callback, $item)) {
            return true;
        }
    }
    return false;
}

function escape($value)
{
    return htmlentities(trim($value), ENT_QUOTES, 'UTF-8');
}

function escape_array($arr)
{
    return htmlspecialchars(json_encode($arr), ENT_QUOTES, 'UTF-8');
}

function striptags($value)
{
    return strip_tags(escape($value));
}

function timezone()
{
    return TIMEZONE;
}

function datetime_format($date_str = 'now', $destFormat = 'Y-m-d H:i:s', $timezone = 'Asia/Jerusalem')
{
    $date = new DateTime($date_str, new DateTimeZone($timezone));
    return $date->format($destFormat);
}

function remove_special_chars($value)
{
    // return preg_replace("/[^א-תa-z0-9\_\-\.]/i", '_', escape($value));
    return preg_replace("/[$%#@!*&\^\)\(\~\\\]/i", '', escape($value));
}

function styled_print_r($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function plot_json($data)
{
    header('Content-Type: application/json');
    echo json_decode(json_encode($data), true);
    exit();
}

function styled_var_dump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function plot_alert($text, $type = 'info')
{
    echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">' . $text . '</div>';
}


function plot_dismissible_alert($text, $type = 'info')
{
    echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">' . $text .
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}

function plot_sidebar_accordion_link(string $title, string $titleIcon = '', array $links = [], string $parentEl = '#sidenavAccordion')
{
    $target = 'collapse_' . generate_random_string(10);

    $contains_url = false;
    foreach ($links as $str => $url) {
        if (is_page_url($url)) {
            $contains_url = true;
            break;
        }
    }
?>
    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#<?= $target ?>" aria-expanded="false" aria-controls="collapseLayouts">
        <div class="sb-nav-link-icon"><i class="<?= $titleIcon ?> fa-fw"></i></div>
        <?= $title ?>
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down fa-fw"></i></div>
    </a>
    <div class="collapse <?= $contains_url ? 'show' : '' ?>" id="<?= $target ?>" aria-labelledby="headingOne" data-bs-parent="<?= $parentEl ?>">
        <nav class="sb-sidenav-menu-nested nav">
            <?php foreach ($links as $title => $url) { ?>
                <a class="nav-link <?= is_page_url($url) ? 'active' : '' ?>" href="<?= $url ?>"><?= $title ?></a>
            <?php } ?>
        </nav>
    </div>

<?php }

function plot_sidebar_link(string $title, string $titleIcon = '', string $url = '')
{ ?>
    <a class="nav-link <?= is_page_url($url) ? 'active' : '' ?>" href="<?= $url ?>">
        <div class="sb-nav-link-icon"><i class="<?= $titleIcon ?> fa-fw"></i></div>
        <?= $title ?>
    </a>
<?php }

function old($key, $defaultValue = null)
{
    if (isset($_POST[$key])) {
        return trim($_POST[$key]);
    } else if (isset($_GET[$key])) {
        return trim($_GET[$key]);
    }
    return $defaultValue;
}

function path_concat($prefix, ...$segments)
{
    array_unshift($segments, $prefix);
    return implode(DIRECTORY_SEPARATOR, $segments);
}

function url_concat($prefix, ...$segments)
{
    array_unshift($segments, $prefix);
    return implode('/', $segments);
}

function app_name($title = '')
{
    return APP_NAME . (isset($title) && $title ? ' | ' . $title : '');
}

function rel_path($path_to, $path_from)
{
    $pos = strpos($path_to, $path_from);
    $end_pos = $pos + strlen($path_from) + 1;
    return substr($path_to, $end_pos);
}

function root_path(...$segments)
{
    return APP_ENV === 'local'
        ? path_concat(APP_PATH, ...$segments)
        : path_concat($_SERVER['DOCUMENT_ROOT'], ...$segments);
}

function public_path(...$segments)
{
    array_unshift($segments, 'public');
    return path_concat(root_path(), ...$segments);
}

function storage_path(...$segments)
{
    array_unshift($segments, 'storage');
    return path_concat(root_path(), ...$segments);
}

function root_url(...$segments)
{
    return url_concat(APP_URL, ...$segments);
}

function public_url(...$segments)
{
    array_unshift($segments, 'public');
    return url_concat(root_url(), ...$segments);
}

function assets_url(...$segments)
{
    array_unshift($segments, 'assets');
    return url_concat(public_url(), ...$segments);
}

function curr_page_full_url()
{
    return sprintf('%s://%s%s', $_SERVER['REQUEST_SCHEME'], $_SERVER['SERVER_NAME'], $_SERVER['PHP_SELF']);
}

function curr_page_relative_url()
{
    return $_SERVER['REQUEST_URI'];
}

function is_page_url($url)
{
    return curr_page_full_url() === $url;
}

function send_json(array $arr)
{
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}

function cleaner($string)
{
    return ucfirst(preg_replace('/_/', ' ', $string));
}

// For 4.3.0 <= PHP <= 5.4.0
if (!function_exists('http_response_code')) {
    function http_response_code($newcode = NULL)
    {
        static $code = 200;
        if ($newcode !== NULL) {
            header('X-PHP-Response-Code: ' . $newcode, true, $newcode);
            if (!headers_sent())
                $code = $newcode;
        }
        return $code;
    }
}

/**
 * Sets the response code and reason
 *
 * @param int    $code
 * @param string $reason
 */
function http_response($code, $reason = null)
{
    $sapi_type = php_sapi_name();
    if (substr($sapi_type, 0, 3) == 'cgi')
        header("Status: $code $reason");
    else
        header("HTTP/1.1 $code $reason");
}

function echo_response($msg, $http_code)
{
    if ($http_code >= 500 && $http_code < 600) {
        if (!display_errors()) {
            $msg = get_http_error($http_code);
        }
    }
    http_response_code($http_code);
    echo $msg;
}

function get_field_error(array $errors, $field_name)
{
    if (isset($errors) && isset($errors[$field_name])) {
        return $errors[$field_name];
    }
    return null;
}

function plot_field_error($error)
{
    if (isset($error)) {
        echo '<span class="invalid d-block">' . $error . '</span>';
    }
    return null;
}

function random_string($length = 16)
{
    $bytes = random_bytes(floor($length / 2));
    return bin2hex($bytes);
}

function random_number()
{
    return mt_rand(10000, 99999);
}

function find_by_key(array $array, $value, $key)
{
    $results = array_filter($array, function ($item) use ($value, $key) {
        return isset($item[$key])
            && $item[$key] === $value;
    });

    return count($results) ? reset($results) : null;
}

// function slugify(string $str, string $table, string $delimitter = '-')
// {
//     // get pdo connection
//     $pdo = db_connect();

//     // make a slug
//     $str = escape($str);
//     $str = preg_replace("#(\p{P}|\p{C}|\p{S}|\p{Z})+#u", $delimitter,  $str);

//     $sql = "SELECT * FROM $table WHERE slug = ?";
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute([$str]);
//     if ($stmt->rowCount() > 0) {
//         $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         $mapped_matches = array_map(function ($item) {
//             return $item['slug'];
//         }, $matches);

//         $is_found = false;
//         $i = 1;
//         while (!$is_found) {
//             $concat = $str . $delimitter . $i;
//             if (!in_array($concat, $mapped_matches)) {
//                 $is_found = true;
//                 $slug = $concat;
//             }
//             $i++;
//         }

//         return $slug;
//     } else {
//         return $str;
//     }
// }

function format_date($date, $src_format = 'Y-m-d H:i:s', $dest_format = 'd/m/Y H:i')
{
    if (!$date) return null;

    try {
        $date = DateTime::createFromFormat($src_format, $date);
        if ($date) {
            return $date->format($dest_format);
        }
        return null;
    } catch (\Exception $e) {
        return null;
    }
}

function format_date_il($date, $src_format = 'Y-m-d H:i:s')
{
    return format_date($date, $src_format, 'd/m/Y H:i');
}

function format_date_iso($date, $src_format = 'd/m/Y H:i')
{
    return format_date($date, $src_format, 'Y-m-d H:i:s');
}

function basename_x($url, $ext = NULL)
{
    $Array_Check = TRUE;
    $url = explode("/", $url);
    $Array_Check = (is_array($url) ? TRUE : FALSE);
    $key = ($Array_Check ? count($url) - 1 : NULL);
    if ($ext != NULL) {
        if ($Array_Check) {
            $url[$key] = preg_replace("/$ext/", '', $url[$key]);
        } else {
            $url       = preg_replace("/$ext/", '', $url);
        }
    }
    $base_name = ($Array_Check ? $url[$key] : $url);
    return $base_name;
}

function unique_filename($filename)
{
    $path_parts = pathinfo($filename);
    $file_basename = $path_parts['filename'];
    $file_ext = $path_parts['extension'];
    return $file_basename . '_' . time() . '.' . $file_ext;
}

function ext2mime($ext)
{
    $ext_map = [
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',
        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',
        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',
        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    ];

    return isset($ext_map[$ext]) ? $ext_map[$ext] : false;
}

function mime2ext($mime)
{
    $mime_map = [
        'video/3gpp2'                                                               => '3g2',
        'video/3gp'                                                                 => '3gp',
        'video/3gpp'                                                                => '3gp',
        'application/x-compressed'                                                  => '7zip',
        'audio/x-acc'                                                               => 'aac',
        'audio/ac3'                                                                 => 'ac3',
        'application/postscript'                                                    => 'ai',
        'audio/x-aiff'                                                              => 'aif',
        'audio/aiff'                                                                => 'aif',
        'audio/x-au'                                                                => 'au',
        'video/x-msvideo'                                                           => 'avi',
        'video/msvideo'                                                             => 'avi',
        'video/avi'                                                                 => 'avi',
        'application/x-troff-msvideo'                                               => 'avi',
        'application/macbinary'                                                     => 'bin',
        'application/mac-binary'                                                    => 'bin',
        'application/x-binary'                                                      => 'bin',
        'application/x-macbinary'                                                   => 'bin',
        'image/bmp'                                                                 => 'bmp',
        'image/x-bmp'                                                               => 'bmp',
        'image/x-bitmap'                                                            => 'bmp',
        'image/x-xbitmap'                                                           => 'bmp',
        'image/x-win-bitmap'                                                        => 'bmp',
        'image/x-windows-bmp'                                                       => 'bmp',
        'image/ms-bmp'                                                              => 'bmp',
        'image/x-ms-bmp'                                                            => 'bmp',
        'application/bmp'                                                           => 'bmp',
        'application/x-bmp'                                                         => 'bmp',
        'application/x-win-bitmap'                                                  => 'bmp',
        'application/cdr'                                                           => 'cdr',
        'application/coreldraw'                                                     => 'cdr',
        'application/x-cdr'                                                         => 'cdr',
        'application/x-coreldraw'                                                   => 'cdr',
        'image/cdr'                                                                 => 'cdr',
        'image/x-cdr'                                                               => 'cdr',
        'zz-application/zz-winassoc-cdr'                                            => 'cdr',
        'application/mac-compactpro'                                                => 'cpt',
        'application/pkix-crl'                                                      => 'crl',
        'application/pkcs-crl'                                                      => 'crl',
        'application/x-x509-ca-cert'                                                => 'crt',
        'application/pkix-cert'                                                     => 'crt',
        'text/css'                                                                  => 'css',
        'text/x-comma-separated-values'                                             => 'csv',
        'text/comma-separated-values'                                               => 'csv',
        'application/vnd.msexcel'                                                   => 'csv',
        'application/x-director'                                                    => 'dcr',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
        'application/x-dvi'                                                         => 'dvi',
        'message/rfc822'                                                            => 'eml',
        'application/x-msdownload'                                                  => 'exe',
        'video/x-f4v'                                                               => 'f4v',
        'audio/x-flac'                                                              => 'flac',
        'video/x-flv'                                                               => 'flv',
        'image/gif'                                                                 => 'gif',
        'application/gpg-keys'                                                      => 'gpg',
        'application/x-gtar'                                                        => 'gtar',
        'application/x-gzip'                                                        => 'gzip',
        'application/mac-binhex40'                                                  => 'hqx',
        'application/mac-binhex'                                                    => 'hqx',
        'application/x-binhex40'                                                    => 'hqx',
        'application/x-mac-binhex40'                                                => 'hqx',
        'text/html'                                                                 => 'html',
        'image/x-icon'                                                              => 'ico',
        'image/x-ico'                                                               => 'ico',
        'image/vnd.microsoft.icon'                                                  => 'ico',
        'text/calendar'                                                             => 'ics',
        'application/java-archive'                                                  => 'jar',
        'application/x-java-application'                                            => 'jar',
        'application/x-jar'                                                         => 'jar',
        'image/jp2'                                                                 => 'jp2',
        'video/mj2'                                                                 => 'jp2',
        'image/jpx'                                                                 => 'jp2',
        'image/jpm'                                                                 => 'jp2',
        'image/jpeg'                                                                => 'jpeg',
        'image/pjpeg'                                                               => 'jpeg',
        'application/x-javascript'                                                  => 'js',
        'application/json'                                                          => 'json',
        'text/json'                                                                 => 'json',
        'application/vnd.google-earth.kml+xml'                                      => 'kml',
        'application/vnd.google-earth.kmz'                                          => 'kmz',
        'text/x-log'                                                                => 'log',
        'audio/x-m4a'                                                               => 'm4a',
        'audio/mp4'                                                                 => 'm4a',
        'application/vnd.mpegurl'                                                   => 'm4u',
        'audio/midi'                                                                => 'mid',
        'application/vnd.mif'                                                       => 'mif',
        'video/quicktime'                                                           => 'mov',
        'video/x-sgi-movie'                                                         => 'movie',
        'audio/mpeg'                                                                => 'mp3',
        'audio/mpg'                                                                 => 'mp3',
        'audio/mpeg3'                                                               => 'mp3',
        'audio/mp3'                                                                 => 'mp3',
        'video/mp4'                                                                 => 'mp4',
        'video/mpeg'                                                                => 'mpeg',
        'application/oda'                                                           => 'oda',
        'audio/ogg'                                                                 => 'ogg',
        'video/ogg'                                                                 => 'ogg',
        'application/ogg'                                                           => 'ogg',
        'font/otf'                                                                  => 'otf',
        'application/x-pkcs10'                                                      => 'p10',
        'application/pkcs10'                                                        => 'p10',
        'application/x-pkcs12'                                                      => 'p12',
        'application/x-pkcs7-signature'                                             => 'p7a',
        'application/pkcs7-mime'                                                    => 'p7c',
        'application/x-pkcs7-mime'                                                  => 'p7c',
        'application/x-pkcs7-certreqresp'                                           => 'p7r',
        'application/pkcs7-signature'                                               => 'p7s',
        'application/pdf'                                                           => 'pdf',
        'application/octet-stream'                                                  => 'pdf',
        'application/x-x509-user-cert'                                              => 'pem',
        'application/x-pem-file'                                                    => 'pem',
        'application/pgp'                                                           => 'pgp',
        'application/x-httpd-php'                                                   => 'php',
        'application/php'                                                           => 'php',
        'application/x-php'                                                         => 'php',
        'text/php'                                                                  => 'php',
        'text/x-php'                                                                => 'php',
        'application/x-httpd-php-source'                                            => 'php',
        'image/png'                                                                 => 'png',
        'image/x-png'                                                               => 'png',
        'application/powerpoint'                                                    => 'ppt',
        'application/vnd.ms-powerpoint'                                             => 'ppt',
        'application/vnd.ms-office'                                                 => 'ppt',
        'application/msword'                                                        => 'doc',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'application/x-photoshop'                                                   => 'psd',
        'image/vnd.adobe.photoshop'                                                 => 'psd',
        'audio/x-realaudio'                                                         => 'ra',
        'audio/x-pn-realaudio'                                                      => 'ram',
        'application/x-rar'                                                         => 'rar',
        'application/rar'                                                           => 'rar',
        'application/x-rar-compressed'                                              => 'rar',
        'audio/x-pn-realaudio-plugin'                                               => 'rpm',
        'application/x-pkcs7'                                                       => 'rsa',
        'text/rtf'                                                                  => 'rtf',
        'text/richtext'                                                             => 'rtx',
        'video/vnd.rn-realvideo'                                                    => 'rv',
        'application/x-stuffit'                                                     => 'sit',
        'application/smil'                                                          => 'smil',
        'text/srt'                                                                  => 'srt',
        'image/svg+xml'                                                             => 'svg',
        'application/x-shockwave-flash'                                             => 'swf',
        'application/x-tar'                                                         => 'tar',
        'application/x-gzip-compressed'                                             => 'tgz',
        'image/tiff'                                                                => 'tiff',
        'font/ttf'                                                                  => 'ttf',
        'text/plain'                                                                => 'txt',
        'text/x-vcard'                                                              => 'vcf',
        'application/videolan'                                                      => 'vlc',
        'text/vtt'                                                                  => 'vtt',
        'audio/x-wav'                                                               => 'wav',
        'audio/wave'                                                                => 'wav',
        'audio/wav'                                                                 => 'wav',
        'application/wbxml'                                                         => 'wbxml',
        'video/webm'                                                                => 'webm',
        'image/webp'                                                                => 'webp',
        'audio/x-ms-wma'                                                            => 'wma',
        'application/wmlc'                                                          => 'wmlc',
        'video/x-ms-wmv'                                                            => 'wmv',
        'video/x-ms-asf'                                                            => 'wmv',
        'font/woff'                                                                 => 'woff',
        'font/woff2'                                                                => 'woff2',
        'application/xhtml+xml'                                                     => 'xhtml',
        'application/excel'                                                         => 'xl',
        'application/msexcel'                                                       => 'xls',
        'application/x-msexcel'                                                     => 'xls',
        'application/x-ms-excel'                                                    => 'xls',
        'application/x-excel'                                                       => 'xls',
        'application/x-dos_ms_excel'                                                => 'xls',
        'application/xls'                                                           => 'xls',
        'application/x-xls'                                                         => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
        'application/vnd.ms-excel'                                                  => 'xlsx',
        'application/xml'                                                           => 'xml',
        'text/xml'                                                                  => 'xml',
        'text/xsl'                                                                  => 'xsl',
        'application/xspf+xml'                                                      => 'xspf',
        'application/x-compress'                                                    => 'z',
        'application/x-zip'                                                         => 'zip',
        'application/zip'                                                           => 'zip',
        'application/x-zip-compressed'                                              => 'zip',
        'application/s-compressed'                                                  => 'zip',
        'multipart/x-zip'                                                           => 'zip',
        'text/x-scriptzsh'                                                          => 'zsh',
    ];

    return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
}

function empty_obj($obj)
{
    return !isset($obj) || !$obj;
}

function is_not_nil($val)
{
    $val = trim($val);
    return isset($val) && $val && !empty($val);
}

function is_nil($obj)
{
    return !is_not_nil($obj);
}

function is_session_started()
{
    if (version_compare(phpversion(), '5.4.0', '<')) {
        if (session_id() == '') {
            session_start();
        }
    } else {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}

function sanitize_obj(array &$data, $convert_to_numeric = true)
{
    foreach ($data as $key => $value) {
        if ($value === '') {
            $data[$key] = null;
        } else if ($convert_to_numeric && is_numeric($value)) {
            if (isInt($value)) {
                $data[$key] = parseInt($value);
            } else if (isFloat($value)) {
                $data[$key] = parseFloat($value);
            }
        }
    }
}

function map_sanitize_obj(array $data)
{
    $mapped = [];
    foreach ($data as $key => $value) {
        $mapped[$key] = $value !== '' ? $value : null;
    }
    return $mapped;
}

// function get_from_table($table)
// {
//     // get pdo connection
//     $pdo = db_connect();

//     $sql = "SELECT * FROM {$table}";
//     $stmt = $pdo->query($sql);
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

// function get_from_table_by_id($table, $id, $trashed = null)
// {
//     // get pdo connection
//     $pdo = db_connect();

//     $sql = "SELECT * FROM {$table} WHERE id = ?";
//     if ($trashed === true) {
//         $sql .= ' AND deleted_at IS NOT NULL';
//     } else if ($trashed === false) {
//         $sql .= ' AND deleted_at IS NULL';
//     }
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute([$id]);
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }

// function get_table_col_names($table)
// {
//     // get pdo connection
//     $pdo = db_connect();

//     $sql = "SHOW COLUMNS FROM $table";
//     $stmt = $pdo->query($sql);
//     $col_names = [];
//     while ($row = $stmt->fetch()) {
//         $col_names[] = $row['Field'];
//     }
//     return $col_names;
// }

// /**
//  * Check if a table exists in the current database.
//  *
//  * @param PDO $pdo PDO instance connected to a database.
//  * @param string $table Table to search for.
//  * @return bool TRUE if table exists, FALSE if no table found.
//  */
// function table_exists($table)
// {
//     $pdo = db_connect();
//     // Try a select statement against the table
//     // Run it in try-catch in case PDO is in ERRMODE_EXCEPTION.
//     try {
//         $result = $pdo->query("SELECT 1 FROM {$table} LIMIT 1");
//         // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
//         return !!$result;
//     } catch (Exception $e) {
//         // We got an exception (table not found)
//         return false;
//     }
// }

// function empty_table($table)
// {
//     if (!table_exists($table)) return true;

//     $pdo = db_connect();
//     // Try a select statement against the table
//     // Run it in try-catch in case PDO is in ERRMODE_EXCEPTION.
//     try {
//         $stmt = $pdo->query("SELECT * FROM {$table} LIMIT 1");
//         // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
//         return $stmt->rowCount() === 0;
//     } catch (Exception $e) {
//         // We got an exception (table not found)
//         return false;
//     }
// }

function get_http_error($code)
{
    $http_codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Checkpoint',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    );

    return isset($http_codes[$code]) ? $http_codes[$code] : null;
}

function display_errors()
{
    return ini_get('display_errors') === 'on';
}

function get_obj_data($obj, $key)
{
    return isset($obj[$key]) ? $obj[$key] : null;
}

/**
 * Move array element by index.  Only works with zero-based,
 * contiguously-indexed arrays
 *
 * @param array $array
 * @param integer $from Use NULL when you want to move the last element
 * @param integer $to   New index for moved element. Use NULL to push
 * 
 * @throws Exception
 * 
 * @link http://stackoverflow.com/questions/4126502/move-value-in-php-array-to-the-beginning-of-the-array
 * 
 * @return array Newly re-ordered array
 */
function move_values_by_index(array $array, $from = null, $to = null)
{
    if (null === $from) {
        $from = count($array) - 1;
    }

    if (!isset($array[$from])) {
        throw new Exception("Offset $from does not exist");
    }

    if (array_keys($array) != range(0, count($array) - 1)) {
        throw new Exception("Invalid array keys");
    }

    $value = $array[$from];
    unset($array[$from]);

    if (null === $to) {
        array_push($array, $value);
    } else {
        $tail = array_splice($array, $to);
        array_push($array, $value);
        $array = array_merge($array, $tail);
    }

    return $array;
}

function to_kebab($str)
{
    $string = str_replace('_', '-', ucwords($str, '_'));
    // Replace repeated spaces to underscore
    $string = preg_replace('/[\s.]+/', '_', $string);
    // Replace un-willing chars to hyphen.
    $string = preg_replace('/[^0-9a-zA-Z_\-]/', '-', $string);
    // Skewer the capital letters
    $string = strtolower(preg_replace('/[A-Z]+/', '-\0', $string));
    $string = trim($string, '-_');

    return preg_replace('/[_\-][_\-]+/', '-', $string);
}

function str_sp($str, array $spaces, $ps = '', $pe = '')
{
    $i = 0;
    $splitted_arr = str_split($str);
    $spaced_str = $ps;
    foreach ($splitted_arr as $char) {
        $repeated_str = $i < count($splitted_arr) - 1 ? str_repeat(' ', $spaces[$i]) : '';
        $spaced_str .= $char . $repeated_str;
        $i++;
    }
    return $spaced_str . $pe;
}

function remove_slashes($str)
{
    return str_replace("/", "", $str);
}

function only($data, array $fields = [], $with_nulls = true)
{
    $only = [];
    foreach ($fields as $key) {
        if ($with_nulls || isset($data[$key])) {
            $only[$key] = $data[$key];
        }
    }
    return $only;
}

function isInt($value)
{
    if (!isset($value) || !$value) return false;
    return (!is_int($value) ? (ctype_digit($value)) : true);
}

function isFloat($value)
{
    if (!isset($value) || !$value) return false;
    return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
}

function parseInt($value)
{
    return $value !== '' ? intval($value) : $value;
}

function parseFloat($value)
{
    return $value !== '' ? (float) $value : $value;
}

function number_format_il($val)
{
    return number_format($val, 2, '.', ',');
}

function flatten_keys(array $dict): string
{
    return implode(',', array_keys($dict));
}

function flatten_values(array $dict): string
{
    return implode(',', array_values($dict));
}

function get_previous_page_url()
{
    $previous = "javascript:history.go(-1)";
    // if (isset($_SERVER['HTTP_REFERER'])) {
    //     $previous = $_SERVER['HTTP_REFERER'];
    // }
    return $previous;
}

function is_datauri(string $value)
{
    $args = explode(',', $value);
    if (count($args) > 1) {
        $base64 = $args[1];
        return base64_encode(base64_decode($base64, true)) === $base64;
    }
    return false;
}

function get_datauri_data(string $value)
{
    $args = explode(',', $value);
    if (count($args) > 1) {
        $base64 = $args[1];

        // assume you've set $image_uri to be the URI from the database
        $file_parts = explode(";", $args[0]); // split on the ; after the mime type
        $mime_type = substr($file_parts[0], 5); // get the information after the data: text

        return [$base64, $mime_type];
    }
    return null;
}
