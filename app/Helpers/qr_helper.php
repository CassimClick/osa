<?php 

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

 function QRCode(array $data)
{
    

    $size = 150;
    $result = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->data(json_encode($data))
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size($size)
        ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
        ->logoPath('assets/images/wma1.png')
        ->logoResizeToWidth($size / 2)
        ->validateResult(false)
        ->build();


    header('Content-Type: ' . $result->getMimeType());
    
    return $result->getDataUri();
    
}
?>