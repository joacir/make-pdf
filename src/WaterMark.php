<?php
namespace Pdf\MakePdf;

use Pdf\MakePdf\Cell;

class WaterMark extends Cell {
    
    public $angle = 45;
    
    public function create() {
        $x = $this->GetX();
        $y = $this->GetY();
        $this->config['textColor'] = '205,205,205' ;
        $angle = $this->getAngle();
        $this->Pdf->Rotate($angle, $x, $y);
        parent::create();
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