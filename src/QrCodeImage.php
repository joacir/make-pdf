<?php
namespace Pdf\MakePdf;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Pdf\MakePdf\Cell;

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
        if (empty($data)) {
            return null;
        }
        $options = $this->getQrOptions();
        $qrcode = new QRCode($options);

        return $qrcode->render($data);
    }

    public function getQrOptions() {
        return new QROptions([
            'version' => $this->getVersion(),
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
        ]);
    }

    public function getVersion() {
        $version = 5;
        if (isset($this->config['version'])) {
            $version = (int)$this->config['version'];
        }

        return $version;
    }
}