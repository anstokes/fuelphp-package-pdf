<?php

return [
    'default_driver' => 'tcpdf',
    'drivers'        => [
        'tcpdf'  => [
            'includes' => [
                // TCPDF library
                VENDORPATH . 'tecnickcom/tcpdf/tcpdf.php'
            ],
            'class'    => 'TCPDF',
        ],
        'tcpdft' => [
            'includes' => [
                // Dependency on TCPDF library
                VENDORPATH . 'tecnickcom/tcpdf/tcpdf.php',
                // Extension
                __DIR__ . '/../lib/tcpdft/tcpdft.php',
            ],
            // 'class'    => 'TCPDFT',
            'class'    => 'Pdf\Matrix\TCPDFT',
        ],
        'tcpdfi' => [
            'includes' => [
                // Dependency on TCPDF library
                VENDORPATH . 'tecnickcom/tcpdf/tcpdf.php',
                // Extension
                VENDORPATH . 'setasign/fpdi/src/TcpdfFpdi.php',
            ],
            'class'    => 'setasign\Fpdi\TcpdfFpdi',
        ],
        'dompdf' => [
            'includes' => [
                // Dependency on DOMPDF library
                VENDORPATH . 'dompdf/dompdf/src/Dompdf.php'
            ],
            'class'    => 'Dompdf\Dompdf',
        ],
        'fpdf'   => [
            'includes' => [
                // FPDF library
                VENDORPATH . 'setasign/fpdf/fpdf.php',
            ],
            'class'    => 'FPDF',
        ],
        'fpdi'   => [
            'includes' => [
                // Dependency on FPDF library
                VENDORPATH . 'setasign/fpdf/fpdf.php',
                // Extension
                VENDORPATH . 'setasign/fpdi/src/Fpdi.php',
            ],
            'class'    => 'setasign\Fpdi\Fpdi',
        ],
    ],
];
