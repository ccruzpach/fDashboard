<?php

function createSearchUrl(string $cikNumber, string $fillingType, $fromDate = null)
{
    for ($i = 0; strlen($cikNumber) < 10; $i++) {
        $cikNumber = '0' . $cikNumber;
    }

    //owner=include ====> gets forms 3, 4, and 5;
    //datea=$fromDate

    return "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=$cikNumber&type=$fillingType&dateb=&owner=include&count=100&search_text=";
}

function getHtmlContent(string $url)
{
    $agent = "Mozilla/5.0 (X11; Linux x86_64; rv:60.0)";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    return curl_exec($ch);
}

function extractDOM($htmlSource)
{
    $domDocument = new DOMDocument();
    $domDocument->validateOnParse = true;
    @$domDocument->loadHTML($htmlSource);

    return $domDocument;
}
