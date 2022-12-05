<?php



require public_path('EdgarDataProcessor.php');
require public_path('EdgarDataRetrival.php');

// $url = 'https://www.sec.gov/corpfin/division-of-corporation-finance-standard-industrial-classification-sic-code-list';


// $r = new EDGARDataRetriever();
// $p = new EDGARDataProcessor();


// $url = "https://www.sec.gov/files/company_tickers.json";

// $url = file_get_contents($url);
// dd($url);
// $decoded_url = json_decode($url, true);
// dd(count($decoded_url));





// $path = public_path('SICData.json');

// dd(file_get_contents($path));

// To get list of all fillings by company -- KEY!!!!
// https://data.sec.gov/api/xbrl/companyfacts/CIK0000320193.json


// To extract SIC number for ticker
// https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=0000861289&owner=include&count=40&hidefilings=0


// To extract all companies for a given SIC number
//Increment "start" to go to nex pages
$sample = 'https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&SIC=3571&owner=include&start=0&count=100&hidefilings=0';
$url1 = 'https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&SIC=100&owner=include&match=starts-with&start=40&count=100&hidefilings=0';
$url2 = 'https://www.sec.gov/cgi-bin/browse-edgar?company=&match=starts-with&filenum=&State=&Country=&SIC=100&myowner=exclude&action=getcompany';

$r = new EDGARDataRetriever();
$urlData = $r->getEdgarData($url1);
$results2 = $r->parseDOM($urlData, 'span');
$results = $r->parseDOM($urlData);
$companies = [];
$sicNumber = "";


for ($i = 0; $i < count($results); $i++)
{

    $result = explode("\n", trim($results[$i]->textContent));
    $tempList = [];

    for ($j = 0; $j < count($result); $j++)
    {
        $tempList[] = trim($result[$j]);
    }
    $companies[] = $tempList;
}

for ($i = 0; $i < count($results2); $i++)
{
    $r = $results2[$i]->textContent;
    preg_match('(\d+)', $r, $sicNumber);
    // $sicInfo = $industry;

    break;
}

$sicNumber = implode($sicNumber);
array_unshift($companies, $sicNumber);

$path = storage_path('/sic');
File::ensureDirectoryExists($path);
file_put_contents($path . $sicNumber . ".json",json_encode($companies));

dd($companies);



//THIS SEARCH GIVES YOU EVERYTHING YOU NEED!
//https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=0001058027&owner=include&count=40&hidefilings=0