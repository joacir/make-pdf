<?php
namespace Pdf\MakePdf;

use Pdf\MakePdf\Cell;

class Verse extends Cell {
    
    public function create() {
        $this->Pdf->AddPage();
        foreach ($this->config as $key => $nodes) {
            if (is_array($nodes) && (string)$key != 'title') {
                foreach ($nodes as $node) {
                    $this->addChild($node);
                }
            }            
        }        
    }
}