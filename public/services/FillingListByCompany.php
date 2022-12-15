<?php

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

?>