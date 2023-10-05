<?php

// Add Javascript functionality
trait AutoPrint
{
    protected $javascript;
    protected $n_js;

    public function printerDialog($printer, $dialog = "full")
    {
        // Embed some JavaScript to show the print dialog or start printing immediately
        $this->javascript = "					var objDoc = this;
						var objPrintParams = objDoc.getPrintParams();
						objPrintParams.printerName = \"" . $printer . "\";
						objPrintParams.interactive = objPrintParams.constants.interactionLevel." . $dialog . ";
						objDoc.print(objPrintParams);";
    }

    protected function putJavascript()
    {
        $this->_newobj();
        $this->n_js = $this->n;
        $this->_out('<<');
        $this->_out('/Names [(EmbeddedJS) ' . ($this->n + 1) . ' 0 R]');
        $this->_out('>>');
        $this->_out('endobj');
        $this->_newobj();
        $this->_out('<<');
        $this->_out('/S /JavaScript');
        $this->_out('/JS ' . $this->_textstring($this->javascript));
        $this->_out('>>');
        $this->_out('endobj');
    }

    function _putresources()
    {
        parent::_putresources();
        if (! empty($this->javascript)) {
            $this->_putjavascript();
        }
    }

    function _putcatalog()
    {
        parent::_putcatalog();
        if (! empty($this->javascript)) {
            $this->_out('/Names <</JavaScript ' . ($this->n_js) . ' 0 R>>');
        }
    }
}
