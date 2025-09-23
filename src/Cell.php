<?php
namespace Pdf\MakePdf;

use Pdf\MakePdf\Title;

class Cell {

    public $Parent = null;
    public $Pdf = null;
    public $config = null;
    public $Children = [];
    public $titleLineHeight = 0;

    public function __construct($Parent, $config = array()) {
        $this->Parent = $Parent;
        $this->Pdf = $this->getPdf();
        $this->config = $config;
        if (!is_array($config)) {
            $this->config = array('text' => $config);
        }
        if (
            isset($config['configField']) &&
            isset($this->Pdf->configFields[$config['configField']])
        ) {
            $this->config = array_merge($config, $this->Pdf->configFields[$config['configField']]);
        }
        $this->setAutoPageBreak();
        $this->createTitle();
        $this->create();
    }

    public function getPdf() {
        return $this->Parent->getPdf();
    }

    public function setAutoPageBreak() {
        if (!empty($this->config['autoPageBreak'])) {
            $this->Pdf->SetAutoPageBreak(true, $this->config['autoPageBreak']);
        }
    }

    public function createTitle() {
        if (!empty($this->config['title'])) {
            $this->Children[] = new Title($this, $this->config['title']);
        }
    }

    public function create() {
        $this->setFont();
        $this->setTextColor();
        $this->setXY();
        $border = $this->getBorder();
        $align = $this->getAlign();
        $fill = $this->getFill();
        $h = $this->getLineHeight();
        $w = $this->getLineWidth();
        $text = $this->getText();
        $useTag = $this->getUseTag();

        if ($useTag) {
            $x = $this->GetX();
            if (!preg_match('/<p>(.*)<\/p>/', $text)) {
                $text = "<p>{$text}</p>";
            }
            $this->Pdf->WriteTag($w, $h, $text, $border, $align, $fill, '1');
            $this->Pdf->SetX($x);
        } else {
            $this->Pdf->MultiCell($w, $h, $text, $border, $align, $fill);
        }

        $this->Pdf->setLasth($h + $this->titleLineHeight);
    }

    public function getBorder() {
        return $this->config['border'] ?? $this->Parent->getBorder();
    }

    public function getAlign() {
        return $this->config['align'] ?? $this->Parent->getAlign();
    }

    public function getFill() {
        $fill = false;
        if ($this->Pdf->fillOn) {
            $fill = $this->config['fill'] ?? $this->Parent->getFill();
            if (!empty($fill)) {
                $this->Pdf->SetFillColor($fill, $fill, $fill);
            }
        }

        return $fill;
    }

    public function getLineHeight() {
        return $this->config['lineHeight'] ?? $this->Parent->getLineHeight();
    }

    public function getLineWidth() {
        return $this->config['lineWidth'] ?? $this->Parent->getLineWidth();
    }

    public function getText() {
        $text = $this->config['text'] ?? $this->getFieldText();
        if ($text !== null) {
            $text = $this->variablesToText($text);
            $text = $this->decimal($text);
            $text = $this->date($text);
            $text = $this->tab($text);
        }

        return $text;
    }

    public function getFieldText() {
        $text = null;
        if (isset($this->config['fieldName'])) {
            $nodes = explode('.', $this->config['fieldName']);
            if (!empty($nodes[0]) && $nodes[0] == 'Header') {
                $text = $this->getHeaderText($nodes[1]);
            } else {
                $value = $this->Pdf->record;
                foreach ($nodes as $node) {
                    if (isset($value[$node])) {
                        $value = $value[$node];
                        if (!is_array($value)) {
                            $text = $value;
                        }
                    }
                }
            }
        }

        return $text;
    }

    public function getHeaderText($field) {
        return $this->Pdf->header[$field] ?? null;
    }

    public function variablesToText($text) {
        if (!empty($text)) {
            $text = str_replace('[DATETIME]', date('d/m/Y - H:i:s'), $text);
            $text = str_replace('[DATE]', date('d/m/Y'), $text);
            $text = str_replace('[PAGES]', "{nb}", $text);
            $text = str_replace('[PAGE]', $this->Pdf->PageNo(), $text);
            $text = str_replace('[RECORD_COUNT]', (string)count($this->Pdf->records), $text);
            $text = $this->sumaryToText($text);
        }

        return $text;
    }

    public function sumaryToText($text) {
        if (!empty($this->Pdf->sumaryFields)) {
            foreach ($this->Pdf->sumaryFields as $fieldName => $maskFieldName) {
                $text = str_replace('[' . $maskFieldName . ']', $this->Pdf->{$maskFieldName}, $text);
            }
        }

        return $text;
    }

    public function decimal($text) {
        if (isset($this->config['decimal'])) {
            $text = number_format($this->sqlDouble($text), $this->config['decimal'], ',', '.');
        }

        return $text;
    }

    public function sqlDouble($userDouble) {
        $sqlDouble = $userDouble;
        if (substr_count($userDouble, '.') >= 1 && substr_count($userDouble, ',') == 1) {
            $sqlDouble = str_replace(",", ".", str_replace(".", "", $userDouble));
        } else {
            $sqlDouble = str_replace(",", ".", $userDouble);
        }

        return $sqlDouble;
    }

    public function date($text) {
        if (isset($this->config['date'])) {
            $text = date($this->config['date'], strtotime($text));
        }

        return $text;
    }

    public function tab($text) {
        if (isset($this->config['tab'])) {
            $text = str_repeat(" ", $this->config['tab']) . $text;
        }

        return $text;
    }

    public function setFont() {
        $this->Pdf->SetFont($this->getFontFamily(), $this->getFontStyle(), $this->getFontSizePt());
    }

    public function getTitleFontFamily() {
        return $this->config['titleFontFamily'] ?? $this->Parent->getTitleFontFamily();
    }

    public function getTitleFontSizePt() {
        return $this->config['titleFontSizePt'] ?? $this->Parent->getTitleFontSizePt();
    }

    public function getTitleFontStyle() {
        return $this->config['titleFontStyle'] ?? $this->Parent->getTitleFontStyle();
    }

    public function getFontFamily() {
        return $this->config['fontFamily'] ?? $this->Parent->getFontFamily();
    }

    public function getFontSizePt() {
        return $this->config['fontSizePt'] ?? $this->Parent->getFontSizePt();
    }

    public function getFontStyle() {
        return $this->config['fontStyle'] ?? $this->Parent->getFontStyle();
    }

    public function setTextColor() {
        $rgb = $this->getTextColor();
        $this->Pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
    }

    public function getTextColor() {
        $textColor = $this->Parent->getTextColor();
        if (isset($this->config['textColor'])) {
            $textColor = explode(',', $this->config['textColor']);
        }

        return $textColor;
    }

    public function SetXY($x = null, $y = null) {
        $x = empty($x) ? $this->GetX() : $x;
        $y = empty($y) ? $this->GetY() : $y;

        $this->Pdf->SetXY($x, $y);
    }

    public function GetX() {
        $x = $this->config['x'] ?? $this->Pdf->GetX();
        $x += $this->GetRelativeX();

        return $x;
    }

    public function GetY() {
        $y = $this->config['y'] ?? $this->Pdf->GetY();
        $y += $this->GetRelativeY();

        return $y;
    }

    public function GetRelativeX() {
        return $this->config['relativeX'] ?? 0;
    }

    public function GetRelativeY() {
        return $this->config['relativeY'] ?? 0;
    }

    public function getGroupSpacing() {
        return $this->config['groupSpacing'] ?? $this->Parent->getGroupSpacing();
    }

    public function getUseTag() {
        return $this->config['useTag'] ?? $this->Parent->getUseTag();
    }

    public function addChild($node) {
        if (is_array($node)) {
            $type = array_keys($node)[0];
            $config = array_values($node)[0];
            $type = 'Pdf\\MakePdf\\' . ucfirst($type);
            if (class_exists($type)) {
                $this->Children[] = new $type($this, $config);
            }
        }
    }
}