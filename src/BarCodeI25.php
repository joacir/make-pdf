<?php
namespace Pdf\MakePdf;

use Pdf\MakePdf\Cell;

class BarCodeI25 extends Cell {
    
    public function create() {
        $x = $this->GetX() + 1;        
        $y = $this->GetY() + 1;        
        $w = $this->getLineWidth();
        $h = $this->getLineHeight() - 2;
        $code = $this->getCode();        
       
        $this->write($x, $y, $code, 1, $h);

        $y += $h + 1;
        $x += $w + 1;        
        $this->Pdf->SetY($y);
        $this->Pdf->SetX($x);
        $this->Pdf->setLasth($h + 1);
    }
    
    public function getCode() {
        return $this->getText();
    }

    public function write($x, $y, $code, $w = 1, $h = 10) {
        $wide = $w;
        $narrow = $w / 3;
        $barChar["0"] = "nnwwn";
        $barChar["1"] = "wnnnw";
        $barChar["2"] = "nwnnw";
        $barChar["3"] = "wwnnn";
        $barChar["4"] = "nnwnw";
        $barChar["5"] = "wnwnn";
        $barChar["6"] = "nwwnn";
        $barChar["7"] = "nnnww";
        $barChar["8"] = "wnnwn";
        $barChar["9"] = "nwnwn";
        $barChar["A"] = "nn";
        $barChar["Z"] = "wn";
        if (strlen($code) % 2 != 0) {
            $code = "0" . $code;
        }
        $this->Pdf->SetFillColor(0);
        $code = "AA" . strtolower($code) . "ZA";
        for ($i = 0; $i < strlen($code); $i = $i + 2) {
            $charBar = $code[$i];
            $charSpace = $code[$i + 1];
            if (!isset($barChar[$charBar])) {
                $this->Pdf->Error("Invalid character in barcode: " . $charBar);
            }
            if (!isset($barChar[$charSpace])) {
                $this->Pdf->Error("Invalid character in barcode: " . $charSpace);
            }
            $seq = "";
            for ($s = 0; $s < strlen($barChar[$charBar]); $s++) {
                $seq .= $barChar[$charBar][$s] . $barChar[$charSpace][$s];
            }
            for ($bar = 0; $bar < strlen($seq); $bar++) {
                if ($seq[$bar] == "n") {
                    $lineWidth = $narrow;
                } else {
                    $lineWidth = $wide;
                }
                if ($bar % 2 == 0) {
                    $this->Pdf->Rect($x, $y, $lineWidth, $h, "F");
                }
                $x += $lineWidth;
            }
        }
    }    
}