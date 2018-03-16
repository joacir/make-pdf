<?php
require_once('Cell.php');

class Title extends Cell {
    
    public function create() {
        parent::create();      
        if (isset($this->Parent->config['y'])) {
            $this->Parent->config['y'] += $this->getLineHeight();            
        }
        $this->Parent->titleLineHeight = $this->getLineHeight();
    }
    
    public function getFontFamily() {
        return $this->getTitleFontFamily();
    }   
     
    public function getFontStyle() {
        return $this->getTitleFontStyle();
    }   
     
    public function getFontSizePt() {
        return $this->getTitleFontSizePt();
    }   
     
    public function getLineHeight() {
        $h = $this->Pdf->getFontSize();
        if (isset($this->config['lineHeight'])) {
            $h = $this->config['lineHeight'];
        }
        
        return $h;
    }
    
    public function GetX() {
        return $this->Parent->GetX();
    }
        
    public function GetY() {
        return $this->Parent->GetY();
    }
}
?>