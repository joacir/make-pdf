<?php
namespace Pdf\MakePdf;

use Pdf\MakePdf\Title;

class Cell {

    /** @var PdfDocument|PdfReport|Cell $Parent */
    public $Parent;
    /** @var PdfDocument|PdfReport $Pdf */
    public $Pdf;
    public array $config;
    public array $Children = [];
    public int $titleLineHeight = 0;

    public function __construct($Parent, $config = array()) {
        $this->Parent = $Parent;
        $this->Pdf = $this->getPdf();
        $this->config = $config;
        if (!is_array($config)) {
            $this->config = array('text' => $config);
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

        $this->Pdf->MultiCell($w, $h, $text, $border, $align, $fill);

        $this->Pdf->setLasth($h + $this->titleLineHeight);
    }

    public function getBorder() {
        $border = $this->Parent->getBorder();
        if (isset($this->config['border'])) {
            $border = $this->config['border'];
        }

        return $border;
    }

    public function getAlign() {
        $align = $this->Parent->getAlign();
        if (isset($this->config['align'])) {
            $align = $this->config['align'];
        }

        return $align;
    }

    public function getFill() {
        $fill = false;
        if ($this->Pdf->fillOn) {
            $fill = $this->Parent->getFill();
            if (isset($this->config['fill'])) {
                $fill = $this->config['fill'];
            }
            if (!empty($fill)) {
                $this->Pdf->SetFillColor($fill, $fill, $fill);
            }
        }

        return $fill;
    }

    public function getLineHeight() {
        $h = $this->Parent->getLineHeight();
        if (isset($this->config['lineHeight'])) {
            $h = $this->config['lineHeight'];
        }

        return $h;
    }

    public function getLineWidth() {
        $w = $this->Parent->getLineWidth();
        if (isset($this->config['lineWidth'])) {
            $w = $this->config['lineWidth'];
        }

        return $w;
    }

    public function getText() {
        $text = null;
        if (isset($this->config['text'])) {
            $text = $this->config['text'];
        } else {
            $text = $this->getFieldText();
        }
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
        $text = null;
        if (isset($this->Pdf->header[$field])) {
            $text = $this->Pdf->header[$field];
        }

        return $text;
    }

    public function variablesToText($text) {
        if (!empty($text)) {
            $text = str_replace('[DATE]', date('d/m/Y'), $text);
            $text = str_replace('[PAGES]', "{nb}", $text);
            $text = str_replace('[PAGE]', $this->Pdf->PageNo(), $text);
            $text = str_replace('[RECORD_COUNT]', count($this->Pdf->records), $text);
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
        $titleFontFamily = $this->Parent->getTitleFontFamily();
        if (isset($this->config['titleFontFamily'])) {
            $titleFontFamily = $this->config['titleFontFamily'];
        }

        return $titleFontFamily;
    }

    public function getTitleFontSizePt() {
        $titleFontSizePt = $this->Parent->getTitleFontSizePt();
        if (isset($this->config['titleFontSizePt'])) {
            $titleFontSizePt = $this->config['titleFontSizePt'];
        }

        return $titleFontSizePt;
    }

    public function getTitleFontStyle() {
        $titleFontStyle = $this->Parent->getTitleFontStyle();
        if (isset($this->config['titleFontStyle'])) {
            $titleFontStyle = $this->config['titleFontStyle'];
        }

        return $titleFontStyle;
    }

    public function getFontFamily() {
        $fontFamily = $this->Parent->getFontFamily();
        if (isset($this->config['fontFamily'])) {
            $fontFamily = $this->config['fontFamily'];
        }

        return $fontFamily;
    }

    public function getFontSizePt() {
        $fontSizePt = $this->Parent->getFontSizePt();
        if (isset($this->config['fontSizePt'])) {
            $fontSizePt = $this->config['fontSizePt'];
        }

        return $fontSizePt;
    }

    public function getFontStyle() {
        $fontStyle = $this->Parent->getFontStyle();
        if (isset($this->config['fontStyle'])) {
            $fontStyle = $this->config['fontStyle'];
        }

        return $fontStyle;
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
        $x = $this->Pdf->GetX();
        if (isset($this->config['x'])) {
            $x = $this->config['x'];
        }
        $x += $this->GetRelativeX();

        return $x;
    }

    public function GetY() {
        $y = $this->Pdf->GetY();
        if (isset($this->config['y'])) {
            $y = $this->config['y'];
        }
        $y += $this->GetRelativeY();

        return $y;
    }

    public function GetRelativeX() {
        $relativeX = 0;
        if (isset($this->config['relativeX'])) {
            $relativeX = $this->config['relativeX'];
        }

        return $relativeX;
    }

    public function GetRelativeY() {
        $relativeY = 0;
        if (isset($this->config['relativeY'])) {
            $relativeY = $this->config['relativeY'];
        }

        return $relativeY;
    }

    public function getGroupSpacing() {
        $groupSpacing = $this->Parent->getGroupSpacing();
        if (isset($this->config['groupSpacing'])) {
            $groupSpacing = $this->config['groupSpacing'];
        }

        return $groupSpacing;
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