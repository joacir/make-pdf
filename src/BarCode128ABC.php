<?php
namespace Pdf\MakePdf;

use Pdf\MakePdf\Cell;

class BarCode128ABC extends Cell {

    public $T128 = array();
    public $ABCset = "";
    public $Aset = "";
    public $Bset = "";
    public $Cset = "";
    public $SetFrom;
    public $SetTo;
    public $JStart = array("A" => 103, "B" => 104, "C" => 105);
    public $JSwap = array("A" => 101, "B" => 100, "C" => 99);

    public function create() {
        $x = $this->GetX() + 1;
        $y = $this->GetY() + 1;
        $w = $this->getLineWidth() - 2;
        $h = $this->getLineHeight() - 2;
        $code = $this->getCode();

        $this->write($x, $y, $code, $w, $h);

        $y += $h + 1;
        $x += $w + 1;
        $this->Pdf->SetY($y);
        $this->Pdf->SetX($x);
        $this->Pdf->setLasth($h + 1);
    }

    public function getCode() {
        return $this->getText();
    }

    public function write($x, $y, $code, $w, $h) {
        $this->T128[] = array(2, 1, 2, 2, 2, 2);
        $this->T128[] = array(2, 2, 2, 1, 2, 2);
        $this->T128[] = array(2, 2, 2, 2, 2, 1);
        $this->T128[] = array(1, 2, 1, 2, 2, 3);
        $this->T128[] = array(1, 2, 1, 3, 2, 2);
        $this->T128[] = array(1, 3, 1, 2, 2, 2);
        $this->T128[] = array(1, 2, 2, 2, 1, 3);
        $this->T128[] = array(1, 2, 2, 3, 1, 2);
        $this->T128[] = array(1, 3, 2, 2, 1, 2);
        $this->T128[] = array(2, 2, 1, 2, 1, 3);
        $this->T128[] = array(2, 2, 1, 3, 1, 2);
        $this->T128[] = array(2, 3, 1, 2, 1, 2);
        $this->T128[] = array(1, 1, 2, 2, 3, 2);
        $this->T128[] = array(1, 2, 2, 1, 3, 2);
        $this->T128[] = array(1, 2, 2, 2, 3, 1);
        $this->T128[] = array(1, 1, 3, 2, 2, 2);
        $this->T128[] = array(1, 2, 3, 1, 2, 2);
        $this->T128[] = array(1, 2, 3, 2, 2, 1);
        $this->T128[] = array(2, 2, 3, 2, 1, 1);
        $this->T128[] = array(2, 2, 1, 1, 3, 2);
        $this->T128[] = array(2, 2, 1, 2, 3, 1);
        $this->T128[] = array(2, 1, 3, 2, 1, 2);
        $this->T128[] = array(2, 2, 3, 1, 1, 2);
        $this->T128[] = array(3, 1, 2, 1, 3, 1);
        $this->T128[] = array(3, 1, 1, 2, 2, 2);
        $this->T128[] = array(3, 2, 1, 1, 2, 2);
        $this->T128[] = array(3, 2, 1, 2, 2, 1);
        $this->T128[] = array(3, 1, 2, 2, 1, 2);
        $this->T128[] = array(3, 2, 2, 1, 1, 2);
        $this->T128[] = array(3, 2, 2, 2, 1, 1);
        $this->T128[] = array(2, 1, 2, 1, 2, 3);
        $this->T128[] = array(2, 1, 2, 3, 2, 1);
        $this->T128[] = array(2, 3, 2, 1, 2, 1);
        $this->T128[] = array(1, 1, 1, 3, 2, 3);
        $this->T128[] = array(1, 3, 1, 1, 2, 3);
        $this->T128[] = array(1, 3, 1, 3, 2, 1);
        $this->T128[] = array(1, 1, 2, 3, 1, 3);
        $this->T128[] = array(1, 3, 2, 1, 1, 3);
        $this->T128[] = array(1, 3, 2, 3, 1, 1);
        $this->T128[] = array(2, 1, 1, 3, 1, 3);
        $this->T128[] = array(2, 3, 1, 1, 1, 3);
        $this->T128[] = array(2, 3, 1, 3, 1, 1);
        $this->T128[] = array(1, 1, 2, 1, 3, 3);
        $this->T128[] = array(1, 1, 2, 3, 3, 1);
        $this->T128[] = array(1, 3, 2, 1, 3, 1);
        $this->T128[] = array(1, 1, 3, 1, 2, 3);
        $this->T128[] = array(1, 1, 3, 3, 2, 1);
        $this->T128[] = array(1, 3, 3, 1, 2, 1);
        $this->T128[] = array(3, 1, 3, 1, 2, 1);
        $this->T128[] = array(2, 1, 1, 3, 3, 1);
        $this->T128[] = array(2, 3, 1, 1, 3, 1);
        $this->T128[] = array(2, 1, 3, 1, 1, 3);
        $this->T128[] = array(2, 1, 3, 3, 1, 1);
        $this->T128[] = array(2, 1, 3, 1, 3, 1);
        $this->T128[] = array(3, 1, 1, 1, 2, 3);
        $this->T128[] = array(3, 1, 1, 3, 2, 1);
        $this->T128[] = array(3, 3, 1, 1, 2, 1);
        $this->T128[] = array(3, 1, 2, 1, 1, 3);
        $this->T128[] = array(3, 1, 2, 3, 1, 1);
        $this->T128[] = array(3, 3, 2, 1, 1, 1);
        $this->T128[] = array(3, 1, 4, 1, 1, 1);
        $this->T128[] = array(2, 2, 1, 4, 1, 1);
        $this->T128[] = array(4, 3, 1, 1, 1, 1);
        $this->T128[] = array(1, 1, 1, 2, 2, 4);
        $this->T128[] = array(1, 1, 1, 4, 2, 2);
        $this->T128[] = array(1, 2, 1, 1, 2, 4);
        $this->T128[] = array(1, 2, 1, 4, 2, 1);
        $this->T128[] = array(1, 4, 1, 1, 2, 2);
        $this->T128[] = array(1, 4, 1, 2, 2, 1);
        $this->T128[] = array(1, 1, 2, 2, 1, 4);
        $this->T128[] = array(1, 1, 2, 4, 1, 2);
        $this->T128[] = array(1, 2, 2, 1, 1, 4);
        $this->T128[] = array(1, 2, 2, 4, 1, 1);
        $this->T128[] = array(1, 4, 2, 1, 1, 2);
        $this->T128[] = array(1, 4, 2, 2, 1, 1);
        $this->T128[] = array(2, 4, 1, 2, 1, 1);
        $this->T128[] = array(2, 2, 1, 1, 1, 4);
        $this->T128[] = array(4, 1, 3, 1, 1, 1);
        $this->T128[] = array(2, 4, 1, 1, 1, 2);
        $this->T128[] = array(1, 3, 4, 1, 1, 1);
        $this->T128[] = array(1, 1, 1, 2, 4, 2);
        $this->T128[] = array(1, 2, 1, 1, 4, 2);
        $this->T128[] = array(1, 2, 1, 2, 4, 1);
        $this->T128[] = array(1, 1, 4, 2, 1, 2);
        $this->T128[] = array(1, 2, 4, 1, 1, 2);
        $this->T128[] = array(1, 2, 4, 2, 1, 1);
        $this->T128[] = array(4, 1, 1, 2, 1, 2);
        $this->T128[] = array(4, 2, 1, 1, 1, 2);
        $this->T128[] = array(4, 2, 1, 2, 1, 1);
        $this->T128[] = array(2, 1, 2, 1, 4, 1);
        $this->T128[] = array(2, 1, 4, 1, 2, 1);
        $this->T128[] = array(4, 1, 2, 1, 2, 1);
        $this->T128[] = array(1, 1, 1, 1, 4, 3);
        $this->T128[] = array(1, 1, 1, 3, 4, 1);
        $this->T128[] = array(1, 3, 1, 1, 4, 1);
        $this->T128[] = array(1, 1, 4, 1, 1, 3);
        $this->T128[] = array(1, 1, 4, 3, 1, 1);
        $this->T128[] = array(4, 1, 1, 1, 1, 3);
        $this->T128[] = array(4, 1, 1, 3, 1, 1);
        $this->T128[] = array(1, 1, 3, 1, 4, 1);
        $this->T128[] = array(1, 1, 4, 1, 3, 1);
        $this->T128[] = array(3, 1, 1, 1, 4, 1);
        $this->T128[] = array(4, 1, 1, 1, 3, 1);
        $this->T128[] = array(2, 1, 1, 4, 1, 2);
        $this->T128[] = array(2, 1, 1, 2, 1, 4);
        $this->T128[] = array(2, 1, 1, 2, 3, 2);
        $this->T128[] = array(2, 3, 3, 1, 1, 1);
        $this->T128[] = array(2, 1);
        for ($i = 32; $i <= 95; $i++) {
            $this->ABCset .= chr($i);
        }
        $this->Aset = $this->ABCset;
        $this->Bset = $this->ABCset;
        for ($i = 0; $i <= 31; $i++) {
            $this->ABCset .= chr($i);
            $this->Aset .= chr($i);
        }
        for ($i = 96; $i <= 126; $i++) {
            $this->ABCset .= chr($i);
            $this->Bset .= chr($i);
        }
        $this->Cset = "0123456789";
        for ($i = 0; $i < 96; $i++) {
            @$this->SetFrom["A"] .= chr($i);
            @$this->SetFrom["B"] .= chr($i + 32);
            @$this->SetTo["A"] .= chr(($i < 32) ? $i + 64 : $i - 32);
            @$this->SetTo["B"] .= chr($i);
        }
        $Aguid = "";
        $Bguid = "";
        $Cguid = "";
        for ($i = 0; $i < strlen($code); $i++) {
            $needle = substr($code, $i, 1);
            $Aguid .= ((strpos($this->Aset, $needle) === false) ? "N" : "O");
            $Bguid .= ((strpos($this->Bset, $needle) === false) ? "N" : "O");
            $Cguid .= ((strpos($this->Cset, $needle) === false) ? "N" : "O");
        }
        $SminiC = "OOOO";
        $IminiC = 4;
        $crypt = "";
        while ($code != "") {
            $i = strpos($Cguid, $SminiC);
            if ($i !== false) {
                $Aguid[$i] = "N";
                $Bguid[$i] = "N";
            }
            if (substr($Cguid, 0, $IminiC) == $SminiC) {
                $crypt .= chr(($crypt > "") ? $this->JSwap["C"] : $this->JStart["C"]);
                $made = strpos($Cguid, "N");
                if ($made === false) $made = strlen($Cguid);
                if (fmod($made, 2) == 1) $made--;
                for ($i = 0; $i < $made; $i += 2) $crypt .= chr((int)strval(substr($code, $i, 2)));
                $jeu = "C";
            } else {
                $madeA = strpos($Aguid, "N");
                if ($madeA === false) $madeA = strlen($Aguid);
                $madeB = strpos($Bguid, "N");
                if ($madeB === false) $madeB = strlen($Bguid);
                $made = (($madeA < $madeB) ? $madeB : $madeA);
                $jeu = (($madeA < $madeB) ? "B" : "A");
                $jeuguid = $jeu . "guid";
                $crypt .= chr(($crypt > "") ? $this->JSwap["$jeu"] : $this->JStart["$jeu"]);
                $crypt .= strtr(substr($code, 0, $made), $this->SetFrom[$jeu], $this->SetTo[$jeu]);
            }
            $code = substr($code, $made);
            $Aguid = substr($Aguid, $made);
            $Bguid = substr($Bguid, $made);
            $Cguid = substr($Cguid, $made);
        }
        $check = ord($crypt[0]);
        for ($i = 0; $i < strlen($crypt); $i++) {
            $check += (ord($crypt[$i]) * $i);
        }
        $check %= 103;
        $crypt .= chr($check) . chr(106) . chr(107);
        $i = (strlen($crypt) * 11) - 8;
        $modul = $w / $i;
        for ($i = 0; $i < strlen($crypt); $i++) {
            $c = $this->T128[ord($crypt[$i])];
            for ($j = 0; $j < count($c); $j++) {
                $this->Pdf->Rect($x, $y, $c[$j] * $modul, $h, "F");
                $x += ($c[$j++] + $c[$j]) * $modul;
            }
        }
    }
}