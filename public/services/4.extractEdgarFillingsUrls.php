<?php

require_once public_path('services/3.createUrls.php');

function getFillingDates(string $cikNumber, string $fillingType, $fromDate)
{
    $edgarData = getHtmlDocument($cikNumber, $fillingType, $fromDate);        
    $tRows = getHtmlTags($edgarData, 'tr');
    $fillingList = [];

    foreach ($tRows as $row) {
        $row = explode("\n", trim($row->textContent));
        $tempArray = [];

        for ($i = 0; $i < count($row); $i++) 
        {
            if (($i == 3 or $i == 4) and preg_match("/\s\d{4}-\d{2}-\d{2}/", $row[$i])) {
                $tempArray[] = trim($row[$i]);
            }
        }
        $fillingList[] = $tempArray;
    }
    return array_slice($fillingList, 5);
}

function getAllFillingsListByCompany(string $cikNumber, int $fromDate)
{
    $edgarData = getHtmlDocument($cikNumber, '', $fromDate);
    $tRows = getHtmlTags($edgarData, 'tr');
    $fillingList = [];
    $tDocLinks = getHtmlTags($edgarData, 'a');
    $fillingLinks = [];

    foreach ($tDocLinks as $link) {
        $l = $link->getAttribute('href');
        (preg_match('(Archives/edgar/data)', $l)) ? $fillingLinks[] = 'https://www.sec.gov' . $l : '';
    }

    foreach ($tRows as $row) {
        $row = trim($row->textContent);
        $row = explode("\n", $row);
        $tempArray = [];

        for ($i = 0; $i < count($row); $i++) {
            if (($i == 0)
                or
                ($i == 3 or $i == 4) and preg_match("/\s\d{4}-\d{2}-\d{2}/", $row[$i])
            ) {
                $tempArray[] = trim($row[$i]);
            }
        }
        $fillingList[] = implode(' | ', $tempArray);
    }
    return array_combine(array_slice($fillingList, 5, -1), $fillingLinks);
}

//TODO: MAKE SURE THIS WORKS ALRIGHT!!!!
function getFilingsHtmlsUrls(string $cikNumber, string $fillingType, int $fromDate)
    {
        $edgarData = getHtmlDocument($cikNumber, $fillingType, $fromDate);
        $results = createHtmlLinks($edgarData);
        $newResults = [];

        for ($i = 0; $i < count($results); $i++) {
            $result = getHtmlContent($results[$i]);
            $result = createHtmlFillingLink($result);
            $newResults[] = $result;
        }
        return $newResults;
    }


/*
 *THIS HAS BEEN SUBSIDED BY getXlsFillings METHOD
 */
// function getFillingsXlsUrls(string $cikNumber, string $fillingType, int $fromDate)
//     {
//         $results = extractDOM(getHtmlDocument($cikNumber, $fillingType, $fromDate));
//         $results = createCikLinks($results);
//         $dates = getFillingDates($cikNumber, $fillingType, $fromDate);
//         $newResults = [];

//         for ($i = 0; $i < count($results); $i++) {
//             $result = getHtmlContent($results[$i]);
//             $result = createXlsLinks($result);
//             $newResults[] = [$dates[$i][0], $result[0]];
//         }
//         return $newResults;
//     }