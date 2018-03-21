<?php
class Image extends Cell {
    
    public function create() {
        $x = $this->GetX();        
        $y = $this->GetY();
        $w = $this->getLineWidth();
        $h = $this->getLineHeight();
        $imageFile = $this->getImageFile();        

        $this->Pdf->Image($imageFile, $x, $y, $w, $h);
        $this->Pdf->SetY($y + $h);
        $this->Pdf->SetX($x + $w);
        $this->Pdf->setLasth($h + $this->titleLineHeight);        
    }
    
    public function getImageFile() {
        return $this->getText();
    }
}
?>