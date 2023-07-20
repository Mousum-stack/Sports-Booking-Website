<?php
require('../vendor/autoload.php');

$mpdf = new \Mpdf\Mpdf();

// Write some HTML code:
$mpdf->WriteHTML('<h1>Hello, Mpdf!</h1>');

// Output a PDF file directly to the browser
$mpdf->Output('test.pdf', 'D');
?>
