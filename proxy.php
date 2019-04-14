<?php
include_once '../../mainfile.php';
$m3u8 = false;
if ('.m3u8' !== mb_substr($_GET['url'], -5)) {
    $handle = @fopen($_GET['url'], 'rb');
    while (false !== ($buffer = fgets($handle, 4096))) {
        if (false !== mb_strpos($buffer, '#EXTM3U')) {
            $m3u8 = true;
            break;
        }
    }
}

if ('.m3u8' === mb_substr($_GET['url'], -5) or $m3u8) {
    header('Content-Type: application/x-mpegURL');
    $filename = str_replace('/', '', $_GET['url']);
    $filename = str_replace(':', '', $filename);
    $filename = str_replace('.', '', $filename);
    $filename = str_replace('?', '', $filename);
    $filename = str_replace('&', '', $filename);
    $filename = str_replace('=', '', $filename);
    $fp = fopen(XOOPS_ROOT_PATH . "/uploads/{$filename}.txt", 'wb');

    $handle = @fopen($_GET['url'], 'rb');
    if ($handle) {
        $form_url = dirname($_GET['url']);
        while (false !== ($buffer = fgets($handle, 4096))) {
            if ('#' !== mb_substr($buffer, 0, 1)) {
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
