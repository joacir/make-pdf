<?php
namespace Pdf\MakePdf;

use Pdf\MakePdf\Cell;

class PostNetCode extends Cell {

    /**
     * BAR DEFINITIONS
     *
     * 1 digit has represented by 5 binaries, where:
     * 1 => full bar
     * 0 => half bar
     */
    protected const BAR_DEFINITIONS = [
        [1, 1, 0, 0, 0],
        [0, 0, 0, 1, 1],
        [0, 0, 1, 0, 1],
        [0, 0, 1, 1, 0],
        [0, 1, 0, 0, 1],
        [0, 1, 0, 1, 0],
        [0, 1, 1, 0, 0],
        [1, 0, 0, 0, 1],
        [1, 0, 0, 1, 0],
        [1, 0, 1, 0, 0],
    ];

    /**
     * Height Fator definition
     * to calculate line height in user unit
     * 1 mm = 72/25.4
     */
    protected const HEIGHT_FACTOR = 72/25.4;

    protected const FULL_BAR_HEIGHT = 9;
    protected const HALF_BAR_HEIGHT = 3.6;
    protected const BAR_WIDTH = 1.44;
    protected const BAR_SPACING_WIDTH = 3.6;

    /**
     * Full Bar Nominal Height = 0.125"
     */
    protected float $fullBarHeight;

    /**
     * Half Bar Nominal Height = 0.050"
     */
    protected float $halfBarHeight;

    /**
     * Full and Half Bar Nominal Width = 0.020"
     */
    protected float $barWidth;

    /**
     * Bar Spacing = 0.050"
     */
    protected float $barSpacingWidth;

    /**
     * 5 Bars Spacing
     */
    protected float $fiveBarsWidth;

    public function create() {
        $x = $this->GetX();
        $y = $this->GetY();
        $code = $this->getCode();

        $this->write($x, $y, $code);
    }

    public function getCode() {
        return $this->getText();
    }

    public function write($x, $y, $zipcode) {
        $h = $this->getLineHeight();
        $y += $this->titleLineHeight;

        $this->fullBarHeight = ($this::FULL_BAR_HEIGHT / $this::HEIGHT_FACTOR) * $h;
        $this->halfBarHeight = ($this::HALF_BAR_HEIGHT / $this::HEIGHT_FACTOR) * $h;
        $this->barWidth = ($this::BAR_WIDTH / $this::HEIGHT_FACTOR) * $h;
        $this->barSpacingWidth = ($this::BAR_SPACING_WIDTH / $this::HEIGHT_FACTOR) * $h;
        $this->fiveBarsWidth = $this->barSpacingWidth * 5;

        $this->Pdf->SetLineWidth($this->barWidth);

        // draw start frame bar
        $this->Pdf->Line($x, $y, $x, $y - $this->fullBarHeight);
        $x += $this->barSpacingWidth;

        // draw digit bars
        for ($i = 0; $i < strlen($zipcode); $i++) {
            $this->drawDigitBars($x, $y, $zipcode[$i]);
            $x += $this->fiveBarsWidth;
        }

        // draw check sum digit
        $this->drawDigitBars($x, $y, $this->calculateCheckSumDigit($zipcode));
        $x += $this->fiveBarsWidth;

        // draw end frame bar
        $this->Pdf->Line($x, $y, $x, $y - $this->fullBarHeight);

        $this->Pdf->SetY($y + $this->fullBarHeight);
        $this->Pdf->setLasth($this->fullBarHeight + $this->titleLineHeight);
        $this->Pdf->SetLineWidth(0.2);
    }

    public function getLineHeight() {
        return $this->config['lineHeight'] ?? 1;
    }

    protected function drawDigitBars($x, $y, $digit) {
        for ($i = 0; $i < 5; $i++) {
            $barHeight = $this::BAR_DEFINITIONS[$digit][$i] === 1 ?
                $this->fullBarHeight : $this->halfBarHeight;
            $this->Pdf->Line($x, $y, $x, $y - $barHeight);
            $x += $this->barSpacingWidth;
        }
    }

    protected function calculateCheckSumDigit($zipcode) {
        $sumOfDigits = 0;
        for ($i = 0; $i < strlen($zipcode); $i++) {
            $sumOfDigits += (int)$zipcode[$i];
        }

        return ($sumOfDigits % 10) === 0 ?
            0 : 10 - ($sumOfDigits % 10);
    }
}