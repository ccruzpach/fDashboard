<?php

require_once public_path('services/3.createUrls.php');

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

