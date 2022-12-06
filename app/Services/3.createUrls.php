<?php

require app_path('Services/2.htmlParsingHelpers.php');

function extractLinksReferences(string $htmlSource)
{
    $links = getHtmlTags($htmlSource, 'a');
    $selectedLinks = [];

    foreach ($links as $link) {
        $selectedLinks[] = $link->getAttribute('href');
    }
    return $selectedLinks;
}

function createCikLinks($htmlSource)
{
    $links = extractLinksReferences($htmlSource);
    $selectedLinks = [];

    for ($i = 0; $i < count($links); $i++) {
        if (preg_match('/cik/i', $links[$i]) and preg_match('(action=view)', $links[$i])) {
            $selectedLinks[] = 'https://www.sec.gov' . $links[$i];
        } elseif (
            !empty($selectedLinks)
            and
            !preg_match('/cik/i', $links[$i])
            and
            (!preg_match('/cik/i', $links[$i - 1]) and (!preg_match('/cik/i', $links[$i - 2])) and !preg_match('/cik/i', $links[$i - 3]))
        ) {
            break;
        }
    }
    return $selectedLinks;
}

function createXlsLinks($htmlSource)
{
    $links = extractLinksReferences($htmlSource);
    $selectedLinks = [];

    foreach ($links as $link) {
        (preg_match('(.xls)', $link)) ? $selectedLinks[] = 'https://www.sec.gov' . $link : '';
    }
    return $selectedLinks;
}

function createHtmlLinks($htmlSource)
{
    $links = extractLinksReferences($htmlSource);
    $selectedLinks = [];

    foreach ($links as $link) {
        (preg_match('(/Archives/edgar/data/)', $link)) ? $selectedLinks[] = 'https://www.sec.gov' . $link : '';
    }
    return $selectedLinks;
}

function createHtmlFillingLink($htmlSource)
{
    $links = extractLinksReferences($htmlSource);
    $selectedLinks = '';

    for ($j = 0; $j < count($links); $j++) {
        if (preg_match('(/Archives/edgar/data/)', $links[$j])) {
            $selectedLinks = 'https://www.sec.gov/' . $links[$j];
            break;
        }
    }
    return $selectedLinks;
}
