<?php
namespace Pdf\MakePdf;

use Pdf\MakePdf\Cell;

class GroupDetail extends Cell {

    public function create() {
        if (empty($this->Pdf->groupFieldValue) || $this->Pdf->groupFieldValue != $this->getText()) {
            $this->addNewPage();
            $this->Pdf->groupFieldValue = $this->getText();        
            foreach ($this->config as $key => $node) {
                if (is_array($node) && (string)$key != 'title') {
                    foreach ($node as $type => $config) {
                        $this->addChild(array($type => $config));
                    }
                }            
            }
        }
    }
    
    public function addNewPage() {
        if (isset($this->config['newPage']) && $this->config['newPage'] == 1 && !empty($this->Pdf->groupFieldValue)) {
            $this->Pdf->AddPage();
        }
    }
}