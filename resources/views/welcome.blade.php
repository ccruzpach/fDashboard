<?php

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Storage;

require '/home/cruzpach/Documents/dev/fDashboard/public/edgar-data-retrival.php';
require '/home/cruzpach/Documents/dev/fDashboard/public/edgar-data-processor.php';
$path = '/home/cruzpach/Documents/dev/fDashboard/public/fillings/002488/10K/002488_10K_1.xlsx';
$path2 = '/home/cruzpach/Documents/dev/fDashboard/public/fillings/002488/10Q/002488_10Q_1.xlsx';

$r = new EDGARDataRetriever();
$p = new EDGARDataProcessor();

$results = $r->getEdgarData('', '078003', '', 20010101, 20221231);
// dd($p->getFillingsListByCompany($results));

function populateCIKDatabase()
{
    $cikWithoutTicker = "https://www.sec.gov/Archives/edgar/cik-lookup-data.txt";
    $cikWithTicker = "https://www.sec.gov/files/company_tickers.json";

    $url = file_get_contents($cikWithTicker);
    $decoded_url = json_decode($url, true);
    $dde = [];

    foreach ($decoded_url as $url)
    {
        $dde[] = $url['ticker'];
    }
}

dd($dde);
// dd($decoded_url[3505]['ticker']);