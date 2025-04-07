<?php

if (!function_exists('tcpdf')) {
    function tcpdf()
    {
        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
        return new TCPDF();
    }
} 