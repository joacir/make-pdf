<?php
require_once __DIR__ . '/../vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeImage extends Cell {
    
    public function create() {
        $x = $this->GetX();        
        $y = $this->GetY();
        $w = $this->getLineWidth();
        $h = $this->getLineHeight();

        $qrCode = $this->getQrCode();        

        $this->Pdf->Image($qrCode, $x, $y, $w, $h, 'PNG');
        
        $this->Pdf->SetY($y + $h);
        $this->Pdf->SetX($x + $w);
        $this->Pdf->setLasth($h + $this->titleLineHeight);        
    }

    public function getQrCode() {
        $data = $this->getText();
        $options = $this->getQrOptions();
        $qrcode = new QRCode($options);

        return $qrcode->render($data);
    }

    public function getQrOptions() {
        return new QROptions([
            'version' => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
        ]);        
    }
}