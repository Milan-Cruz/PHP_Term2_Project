<?php
session_start();

// try {
    $captcha_text = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
    $_SESSION['captcha_code'] = $captcha_text;

    $image = imagecreate(150, 50);
    $bg_color = imagecolorallocate($image, 255, 255, 255);
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $font = __DIR__ . '/arial.ttf';

    imagettftext($image, 20, 0, 20, 35, $text_color, $font, $captcha_text);

    header("Content-type: image/png");
    imagepng($image);
    imagedestroy($image);


    // echo "<script>console.log('Generated CAPTCHA: " . $captcha_text . "');</script>";
    // ob_flush();
    // flush();

    // Output the captcha code to the console
    // echo "<script>console.log('Generated CAPTCHA: ');</script>";
    // echo "<script>console.log('Generated CAPTCHA: " . $_SESSION['captcha_code'] . "');</script>";
// } catch (Exception $e) {
//     echo "<script>console.error('Error generating CAPTCHA: " . $e->getMessage() . "');</script>";
// }
 ?>
