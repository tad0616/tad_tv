<?php
include_once "../../mainfile.php";
$m3u8 = false;
if (substr($_GET['url'], -5) != '.m3u8') {
    $handle = @fopen($_GET['url'], "r");
    while (($buffer = fgets($handle, 4096)) !== false) {
        if (strpos($buffer, '#EXTM3U') !== false) {
            $m3u8 = true;
            break;
        }
    }
}

if (substr($_GET['url'], -5) == '.m3u8' or $m3u8) {
    header('Content-Type: application/x-mpegURL');
    $filename = str_replace('/', '', $_GET['url']);
    $filename = str_replace(':', '', $filename);
    $filename = str_replace('.', '', $filename);
    $filename = str_replace('?', '', $filename);
    $filename = str_replace('&', '', $filename);
    $filename = str_replace('=', '', $filename);
    $fp       = fopen(XOOPS_ROOT_PATH . "/uploads/{$filename}.txt", 'w');

    $handle = @fopen($_GET['url'], "r");
    if ($handle) {
        $form_url = dirname($_GET['url']);
        while (($buffer = fgets($handle, 4096)) !== false) {
            if (substr($buffer, 0, 1) != '#') {
                $buffer = XOOPS_URL . "/modules/tad_tv/proxy.php?url={$form_url}/" . $buffer;
            }

            fwrite($fp, $buffer);

            echo $buffer;
        }
        fclose($fp);
        fclose($handle);
    }
} else {
    $data = file_get_contents($_GET['url']);
    echo $data;
}
