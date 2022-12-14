<?php

/*
 * GET LIST OF SIC CODES BY INDUSTRY AND SECTOR
 */

function getSicByIndustryList($url)
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

/*
 * GET FILE WITH ALL SIC CODES AND THEIR CORRESPONDING CIKs
 */

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


function getCIKCodesfromDB()
{
    return DB::select('SELECT cik_number FROM companies');
}

function getSICCodes()
{
    ini_set('max_execution_time', 15000);
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

?>