<?php
class Checkbox extends Cell {
    
    public function create() {
        $offset = 1;
        $x = $this->GetX() + $offset;
        $y = $this->GetY() + $offset;        
        $h = $this->getLineHeight();
        $w = $h;
        $this->Pdf->Rect($x, $y, $w - $offset, $h - $offset);

        $this->config['x'] = $x - ($offset / 2);
        $this->config['y'] = $y - ($offset / 2);
        $this->config['lineWidth'] = $w;
        $this->config['fontSizePt'] = $this->getFontSizePt() - 1;
        $this->config['border'] = 0;
        $this->config['align'] = 'C';
        parent::create();
        $this->SetXY($x + $w, $y + $h);
        $this->Pdf->setLasth($h + $this->titleLineHeight);        
    }
    
}

?>