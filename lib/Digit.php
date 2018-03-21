<?php
class Digit extends Cell {
    
    public function create() {
        $h = $this->getLineHeight();
        $y = $this->GetY();
        $y1 = $y + ($h / 2);
        $y2 = $y + $h;
        $x = $this->GetX();
        $digits = $this->getDigits();
        $digitWidth = ($h / 2) + 1.7;
        for ($i = 1; $i < $digits; $i++) {
            $x += $digitWidth;
            $this->Pdf->Line($x, $y1, $x, $y2);
        }
        $x += $digitWidth;
        $this->config['text'] = null;
        parent::create();
        $this->SetXY($x, $y2);
        $this->Pdf->setLasth($h + $this->titleLineHeight);        
    }
    
    public function getDigits() {
        return $this->getText();
    }
    
}

?>