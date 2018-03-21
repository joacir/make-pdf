<?php
class Group extends Cell {

    public function create() {
        $y = $this->GetY();
        $x = $this->GetX();
        foreach ($this->config as $key => $node) {
            if (is_array($node) && (string)$key != 'title') {
                foreach ($node as $type => $config) {
                    $this->addChild(array($type => $config));
                }
            }            
        }
        $this->setLineHeight($y);
        $this->setLineWidth($x);
        $this->config['y'] = $y;        
        parent::create();
        $groupSpacing = $this->getGroupSpacing();
        if (!empty($groupSpacing)) {
            $this->Pdf->SetY($this->Pdf->GetY() + $groupSpacing);
        }
    }
    
    public function setLineHeight($y) {
        if (!isset($this->config['lineHeight'])) {
            $this->config['lineHeight'] = $this->GetY() - $y;            
        }
    }
    
    public function setLineWidth($x) {
        if (!isset($this->config['lineWidth'])) {
            $this->config['lineWidth'] = $this->GetX() - $x;            
        }
    }
    
}
?>