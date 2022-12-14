<?php

function downloadFile($url, $downloadPath, $agent = "Mozilla/5.0 (X11; Linux x86_64; rv:60.0)")
{
    set_time_limit(0);
    $ch = curl_init(str_replace(" ", "%20", $url));
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    $fp = fopen($downloadPath, 'w+');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

//THIS METHOD IS PROPERLY WORKING!
function getXlsFillings($cikNumber, $fillingType, $fromDate)
{
    $content = extractDOM(getHtmlContent(createSearchUrl($cikNumber, $fillingType, $fromDate)));
    $results = extracHtmlByTag($content, 'a');
    $dates = getFillingDates($cikNumber, $fillingType, $fromDate);

    $links = [];

    for ($i = 0; $i < count($results); $i++)
    {
        $link = $results[$i]->getAttribute('href');

        if (preg_match('/cik/i', $link) and preg_match('(action=view)', $link))
        {
            $link = 'https://www.sec.gov' . $link;
            $link = extractDOM(getHtmlContent($link));
            $link = extracHtmlByTag($link, 'a');

            for ($j = 0; $j < count($link); $j++)
            {
                $l = $link[$j]->getAttribute('href');

                (preg_match('(.xls)', $l)) ? $links[] = 'https://www.sec.gov' . $l : '';
            }
        } 
    }

    $modFillingType = str_replace("-", "", $fillingType);
    $path = storage_path('fillings/') . "$cikNumber/$modFillingType";
    File::ensureDirectoryExists($path);

    for ($i = 0; $i < count($links); $i++) {
        $filingDate = str_replace("-", "", $dates[$i][0]);
        $downloadPath = $path . "/" . $cikNumber . "_" . $modFillingType . "_" . $filingDate . ".xlsx";

        downloadFile($links[$i], $downloadPath);
    }
    echo "Files successfully saved to disks.";
}



?>