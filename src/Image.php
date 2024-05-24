<?php
namespace Pdf\MakePdf;

use Pdf\MakePdf\Cell;

class Image extends Cell {

    public function create() {
        $x = $this->GetX();
        $y = $this->GetY();
        $w = $this->getLineWidth();
        $h = $this->getLineHeight();
        $imageFile = $this->getImageFile();
        $exif = exif_read_data($imageFile);
        if (!empty($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
            $rotations = [3 => 180, 6 => -90, 8 => 90];
            $angle = $rotations[$orientation];
            $this->Pdf->Rotate($angle, $x + $w / 2, $y + $h / 2);
        }
        
        $this->Pdf->Image($imageFile, $x, $y, $w, $h);
        $this->Pdf->SetY($y + $h);
        $this->Pdf->SetX($x + $w);
        $this->Pdf->setLasth($h + $this->titleLineHeight);
    }

    public function getImageFile() {
        return $this->getText();
    }
}