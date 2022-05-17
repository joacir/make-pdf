<?php
namespace Pdf\MakePdf;

use Pdf\MakePdf\Cell;

class Group extends Cell {

    public function create() {
        $footerPage = $this->getFooterPage();
        if (empty($footerPage) || $footerPage == $this->Pdf->PageNo()) {
            if (!empty($this->config['alternateFill'])) {
                $this->Pdf->fillOn = !$this->Pdf->fillOn;
            }
            $this->setMiddlePage();
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
    }
    
    public function setMiddlePage() {
        if (!empty($this->config['middlePage'])) {
            $y = $this->GetY();
            $page = $this->Pdf->PageNo();
            $height = $this->Pdf->GetPageHeight();
            if ($page == 1 && $y < ($height / 2)) {
                $this->Pdf->SetY($height / 2);
            }
        }
    }

    public function getFooterPage() {
        $footerPage = 0;
        if (isset($this->config['footerPage'])) {
            $footerPage = $this->config['footerPage'];            
        }

        return $footerPage;
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