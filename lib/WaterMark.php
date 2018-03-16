<?php
require_once('Cell.php');

class WaterMark extends Cell {
    
    public $angle = 45;
    
    public function create() {
        $x = $this->GetX();
        $y = $this->GetY();
        $angle = $this->getAngle();
        $this->Pdf->Rotate($angle, $x, $y);
        $this->Pdf->SetTextColor(205, 205, 205);
        parent::create();
        $this->Pdf->SetTextColor(0, 0, 0);
        $this->Pdf->Rotate(0);
    }
        
    public function getAngle() {
        $angle = $this->angle;
        if (isset($this->config['angle'])) {
            $angle = $this->config['angle'];
        }
        
        return $angle;
    }
    
}

?>