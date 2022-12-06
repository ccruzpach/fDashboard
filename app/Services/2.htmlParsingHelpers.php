<?php

require app_path('Services/1.urlContentProvider.php');

function getHtmlDocument($cikNumber, $fillingType, $fromDate)
{
    return getHtmlContent(createSearchUrl($cikNumber, $fillingType, $fromDate));
}

function extracHtmlByTag($domDocument, $htmlTag)
{
    return $domDocument->getElementsByTagName($htmlTag);
}

function getHtmlTags($htmlDocument, $htmlTag)
{
    return extracHtmlByTag(extractDOM($htmlDocument), $htmlTag);
}
