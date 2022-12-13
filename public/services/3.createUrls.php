<?php

require_once public_path('services/1.urlContentProvider.php');


function extracHtmlByTag($htmlDocument, $htmlTag)
{
    return $htmlDocument->getElementsByTagName($htmlTag);
}


function getHtmlDocument($cikNumber, $fillingType, $fromDate = 20100101)
{
    return getHtmlContent(createSearchUrl($cikNumber, $fillingType, $fromDate));
}

function getHtmlTags($htmlDocument, $htmlTag)
{
    return extracHtmlByTag(extractDOM($htmlDocument), $htmlTag);
}

function extractLinksReferences(string $htmlSource, $htmlTag = 'a', $htmlAttribute = null)
{
    $links = getHtmlTags($htmlSource, $htmlTag);
    $selectedLinks = [];

    if ($htmlAttribute)
    {
        foreach ($links as $link) {
            $selectedLinks[] = $link->getAttribute($htmlAttribute);
        }
        return $selectedLinks;
    } else {
        return $links;
    }
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
