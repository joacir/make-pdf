<?php
require_once('PdfDocument.php');

class PdfReport extends PdfDocument {

    public $groupFieldValue = null;
    public $sumaryFields = array();
    public $isFirstPage = true;
    
    public function create($settings) {
        $documentPdf = false;
        $this->setup($settings);
        if (!empty($this->templateFile)) {
            $this->setTemplate();
        }
        if (!empty($this->template) && !empty($this->records)) {
            $this->configure();
            $this->AddPage();
            foreach ($this->records as $record) {
                $this->record = $record;
                $this->Body();                
                $this->sumarize($record);
            }
            $this->Sumary();     
            if (empty($this->fileName)) {
                $documentPdf = $this->Output();            
            } else {
                $documentPdf = $this->saveFile($this->fileName);
            }            
        }
        
        return $documentPdf;
    }

    public function configure() {
        parent::configure();
        $this->SetAutoPageBreak(true, $this->getPageBreakTrigger());        
        $this->setSumary();
    }

    public function getPageBreakTrigger() {
        $pageBreakTrigger = $this->tMargin;
        if (isset($this->config['pageBreakTrigger'])) {
            $pageBreakTrigger = $this->config['pageBreakTrigger'];
        }
        
        return $pageBreakTrigger;
    }    
    
    public function Header() {
        if($this->isFirstPage) {
            $this->addNodes('uniqueHeader');
            $this->isFirstPage = false;
        }
        $this->addNodes('header');
        $this->addNodes('columnTitles');
    }

    public function Sumary() {
        $this->addNodes('sumary');
    }
    
    public function setSumary() {
        if (!empty($this->template['sumary'])) {
            foreach ($this->template['sumary'] as $sumFieldName => $fieldName) {
                if (is_string($fieldName)) {
                    $this->{$sumFieldName} = 0;
                    $this->sumaryFields[$fieldName] = $sumFieldName;
                }
            }                        
        }
    }
    
    public function sumarize($record) {
        if (!empty($this->sumaryFields)) {
            foreach ($record as $modelName => $fields) {
                foreach ($fields as $fieldName => $value) {
                    $fieldName = $modelName . '.' . $fieldName;
                    if (!empty($this->sumaryFields[$fieldName])) {
                        if (!is_numeric($value)) {
                            $value = str_replace(',', '.', str_replace('.', '', $value));
                        }
                        $this->{$this->sumaryFields[$fieldName]} += $value;
                        $this->{$this->sumaryFields[$fieldName]} =  str_replace(',', '.', $this->{$this->sumaryFields[$fieldName]});
                    }                            
                }
            }            
        }
    }

}
?>