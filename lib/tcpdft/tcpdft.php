<?php

//include table class
require_once(dirname(__FILE__) . '/pdftable.php');

//set default table settings
global $table_default_table_type, $table_default_header_type, $table_default_data_type;
require_once(dirname(__FILE__) . '/table.config.php');

class TCPDFT extends TCPDF
{
    /**
     * Wrap functions that are now protected in TCPDF so the table library still functions
     */

    // Margins
    public function lMargin()
    {
        return $this->lMargin;
    }

    public function rMargin()
    {
        return $this->rMargin;
    }

    public function tMargin()
    {
        return $this->tMargin;
    }

    public function bMargin()
    {
        return $this->bMargin;
    }

    // Height / Width
    public function w()
    {
        return $this->w;
    }

    public function h()
    {
        return $this->h;
    }

    // Position
    public function x($x = false)
    {
        if ($x === false) {
            return $this->x;
        } else {
            $this->x = $x;
        }
    }

    public function y($y = false)
    {
        if ($y === false) {
            return $this->y;
        } else {
            $this->y = $y;
        }
    }

    public function k()
    {
        return $this->k;
    }

    // Page related
    public function autoPageBreak()
    {
        return $this->AutoPageBreak;
    }

    public function curOrientation()
    {
        return $this->CurOrientation;
    }

    // Font related
    public function currentFont()
    {
        return $this->CurrentFont;
    }

    public function fontSize()
    {
        return $this->FontSize;
    }

    public function isunicode()
    {
        return $this->isunicode;
    }

    public function textColor($setColor = false)
    {
        if ($setColor === false) {
            return $this->TextColor;
        } else {
            $this->TextColor = $setColor;
        }
    }

    // Colour related
    public function fillColor()
    {
        return $this->FillColor;
    }

    public function colorFlag($setColor = false)
    {
        if ($setColor === false) {
            return $this->ColorFlag;
        } else {
            $this->ColorFlag = $setColor;
        }
    }

    // Output related
    public function getCellCodePublic($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'T', $valign = 'M')
    {
        return $this->getCellCode($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
    }

    public function _outPublic($s)
    {
        return $this->_out($s);
    }

    public function getDefaultFontName()
    {
        return 'calibri';
    }

    /*
    public function AddFont($family, $style='', $fontfile='', $subset='default') {
        if (!is_object($style) && $fontfile) {
            return parent::AddFont($family, $style, $fontfile, $subset);
        }
    }

    public function isUnicodeFont() {
        if (isset($this->CurrentFont['type'])) {
            return parent::isUnicodeFont();
        }
    }
    */
}
