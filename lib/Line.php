<?php
require_once('Cell.php');

class Line extends Cell {
   
    public function create() {
        $fill = $this->alternateFill();
        $y = $this->GetY();
        $x = $this->GetX();
        $maxLineHeight = 0;
        foreach ($this->config as $key => $nodes) {
            if (is_array($nodes) && (string)$key != 'title') {
                foreach ($nodes as $type => $config) {
                    if (!isset($config['lineWidth'])) {
                        if (!is_array($config)) {
                            $config = array('text' => $config);
                        }
                        $config['lineWidth'] = $this->getCellWidth();                            
                    }
                    $config['y'] = $y;                    
                    $config['x'] = $x;
                    $config['fill'] = $fill;
                    $x += $config['lineWidth'];
                    $this->addChild(array($type => $config));
                    if ($this->Pdf->getLasth() > $maxLineHeight) {
                        $maxLineHeight = $this->Pdf->getLasth();
                    }
                    $currentY = $this->Pdf->GetY();
                    if ($currentY < $y) {
                        $y = $currentY - $maxLineHeight;
                    }
                }
            }            
        }
        $this->config['y'] = $y;
        $this->config['lineHeight'] = $maxLineHeight;
        parent::create();
    }
    
    public function getCellWidth() {
        $width = $this->getLineWidth();
        $count = 0;
        foreach ($this->config as $type => $node) {
            if (is_array($node) && (string)$type != 'title') {
                foreach ($node as $cell) {
                    if (isset($cell['lineWidth'])) {
                        $width -= $cell['lineWidth'];                    
                        $count--;
                    }                             
                }
                $count++;                                                   
            }
        }      
        $width = $width / $count;

        return $width;
    }

    public function GetX() {
        $x = $this->Pdf->getLeftMargin();
        if (!empty($this->x)) {
            $x = $this->x;
        }
        
        return $x;
    }
    
    public function alternateFill() {
        $fill = $this->getFill();
        if (!empty($this->config['alternateFill'])) {
            $this->Pdf->fillOn = !$this->Pdf->fillOn;
            if ($this->Pdf->fillOn) {
                $fill = $this->config['alternateFill'];
            }
        }
        
        return $fill;
    }
    
}
?>
