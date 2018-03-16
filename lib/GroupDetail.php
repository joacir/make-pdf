<?php
require_once('Cell.php');

class GroupDetail extends Cell {

    public function create() {
        if (empty($this->Pdf->groupFieldValue) || $this->Pdf->groupFieldValue != $this->getText()) {
            $this->addNewPage();
            $this->Pdf->groupFieldValue = $this->getText();        
            foreach ($this->config as $key => $nodes) {
                if (is_array($nodes) && (string)$key != 'title') {
                    foreach ($nodes as $node) {
                        $this->addChild($node);
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

?>