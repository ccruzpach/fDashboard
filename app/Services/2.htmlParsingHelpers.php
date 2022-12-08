<?php

require_once app_path('Services/1.urlContentProvider.php');


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
