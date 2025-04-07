<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Tcpdf extends BaseConfig
{
    public $tcpdf = [
        'path' => ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php',
    ];
} 