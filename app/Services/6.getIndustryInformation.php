<?php

require_once app_path('Services/5.downloadFiles.php');
//CONFIRMED OPERATIONAL!
function getSICData($url)
{
    $results = extractDOM(getHtmlContent($url));
    $results = extracHtmlByTag($results, 'tr');

    $newResults = [];

    for ($i = 0; $i < count($results); $i++) {
        if ($i == 0) {
            continue;
        } else {
            $result = explode("\n", $results[$i]->textContent);
            $tempArray = [];

            for ($j = 0; $j < count($result); $j++) {
                if ($j == 1) {
                    $tempArray['sic_code'] = trim(str_replace("\t", "", $result[$j]));
                } elseif ($j == 2) {
                    $tempArray['sector'] = trim(str_replace("\t", "", $result[$j]));
                } elseif ($j == 3) {
                    $tempArray['industry'] = trim(str_replace("\t", "", $result[$j]));
                }
            }
            $newResults[] = $tempArray;
        }
    }
    $newResults = json_encode($newResults);
    return $newResults;
}

function getSICNumberbyCompany($cikNumber)
{
    $results = extracHtmlByTag(extractDOM(getHtmlDocument($cikNumber, '')), 'a');
    $sic = '';

    for ($i = 0; $i < count($results); $i++) {
        if ($i == 9) {
            $sic = $results[$i]->textContent;
            break;
        }
    }
    return array($cikNumber => $sic);
}
//CONFIRMED OPERATIONAL
function getSICCodes()
{
    $cikCodes = getCIKCodesfromDB();

    $sicCodes = [];


    for ($i = 0; $i < count($cikCodes); $i++) {
        $sicCodes[] = getSICNumberbyCompany($cikCodes[$i]->cik_number);
        break;
    }

    file_put_contents('cik_sic_data.json', json_encode($sicCodes));
    echo "SIC codes extraction succesful";

    return $sicCodes;
}
//CONFIRMED OPERATIONAL
function getCIKCodesfromDB()
{
    return DB::select('SELECT cik_number FROM companies');
}







//TODO: THIS FUNCTION DOES NOT WORK PROPERLY =. LINKS VARY FROM ONE SEARCH TO THE OTHER
function getsCompanyListByIndustry($sic, $pageCount = 0)
{
    // $sic = 900; 
    // $pageCount = 0;
    //This is the only url that works worked!
    $url0 = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&amp;SIC=$sic&amp;owner=include&amp;count=40";

    // $url0 = "https://www.sec.gov/cgi-bin/browse-edgar?company=&match=starts-with&filenum=&State=&Country=&SIC=$sic&myowner=exclude&start=$pageCount&count=100&action=getcompany";

    // $url0 = "https://www.sec.gov/cgi-bin/browse-edgar?company=&match=starts-with&filenum=&State=&Country=&SIC=$sic&myowner=exclude&action=getcompany";

    // $url10 = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&SIC=6199&owner=include&match=starts-with&start=40&count=40&hidefilings=0"

    $dom = extractDOM(getHtmlContent($url0));
    $dom = extracHtmlByTag($dom, 'span');
    $sic = '';

    for ($i = 0; $i < count($dom); $i++) {
        preg_match("(\d+)", $dom[$i]->textContent, $sic);
        break;
    }

    $dom2 = extractDOM(getHtmlContent($url0));
    $dom2 = extracHtmlByTag($dom2, 'tr');
    $companies = [];

    for ($i = 0; $i < count($dom2); $i++) {

        $result = explode("\n", trim($dom2[$i]->textContent));
        $tempList = [];

        for ($j = 0; $j < count($result); $j++) {
            $tempList[] = trim($result[$j]);
        }
        $companies[] = $tempList;
    }

    if (empty($sic)) {
        false;
        echo "No list found";
    } else {
        $sic = implode($sic);
        array_shift($companies); // to delete the header;
        array_unshift($companies, $sic);

        $path = storage_path("/sic/$sic/");
        File::ensureDirectoryExists($path);
        file_put_contents($path . $sic . "_page_" . $pageCount . ".json", json_encode($companies));
        echo "List of companies by industry has been downloaded successfully";
    }
}
