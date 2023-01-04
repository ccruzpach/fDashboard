<?php

function getFillingDatesFromFillingsList(string $cikNumber, string $fillingType, $fromDate)
{
    $edgarData = getHtmlDocument($cikNumber, $fillingType, $fromDate);        
    $tRows = getHtmlTags($edgarData, 'tr');

    $fillingList = [];

    foreach ($tRows as $row) {
        $row = explode("\n", trim($row->textContent));
        $tempArray = "";

        for ($i = 0; $i < count($row); $i++) 
        {
            if (($i == 3 or $i == 4) and preg_match("/\s\d{4}-\d{2}-\d{2}/", $row[$i])) {
                $tempArray = trim($row[$i]);
            }
        }
        $fillingList[] = $tempArray;
    }
    return array_slice($fillingList, 5);
}

//FIXME: MAKE SURE THIS WORKS WITH ANY FILLING, OR WRITE A NEW FUNCTIN FOR EACH FILLING
function getFillingDocument(string $cikNumber, string $fillingType, $fromDate)
{
    //NOTE: query only necessary if ticker is provided instead of a CIK number.
    // $cikNumber = getCompanyCIK('AAPL')->cik_number;

    $content = createSearchUrl($cikNumber, $fillingType, $fromDate);
    $content = getHtmlContent($content);
    $content = extractDOM($content);
    $results = extracHtmlByTag($content, 'a');
    $fillingDates = getFillingDatesFromFillingsList($cikNumber, $fillingType, $fromDate);

    $links = [];

    for ($i = 0; $i < count($results); $i++)
    {    
        $link = $results[$i]->getAttribute('href');
        if (str_contains($link, '/Archives/edgar'))
        {
            $link = 'https://www.sec.gov' . $link;
            $link = getHtmlContent($link);
            $link = extractDOM($link);
            $link = extracHtmlByTag($link, 'a');

            $links[] = $link;
        }
    }

    $newLinks = [];

    for ($j = 0; $j < count($links); $j++)
    {
        $tempArray = "";
        $companySymbol = strtolower(getCompanySymbol($cikNumber));

        for ($k = 0; $k < count ($links[$j]); $k++)
        {
            $link = $links[$j][$k]->getAttribute('href');
            
            //TODO: Find a way to find first result without using break;
            if (str_contains($link, '/Archives/edgar/'))
            {
                if (str_contains($link, $companySymbol)
                or
                (str_contains($link, '10k'))
                or
                (str_contains($link, '10-k'))
                or
                (str_contains($link, '10k')))
                {
                    $link = str_replace('/ix?doc=', '', $link);
                    $tempArray = 'https://www.sec.gov' . $link;
                    break;
                }
            }        
        }
        $newLinks[] = $tempArray;
    }
    return array_combine($newLinks, $fillingDates);
}




/*
 *
 * OLD CODE | IT WORKS JUST FINE!
 * 
 */




//TODO: FINANCIAL FORMS OTHER THAN 10-K/6-K REQUIRE DIFFERENT PREG_MATCH CRITERIA
function getXlsFillings($cikNumber, $fillingType, $fromDate)
{
    $content = extractDOM(getHtmlContent(createSearchUrl($cikNumber, $fillingType, $fromDate)));
    $results = extracHtmlByTag($content, 'a');
    $dates = getFillingDatesFromFillingsList($cikNumber, $fillingType, $fromDate);
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
        $filingDate = str_replace("-", "", $dates[$i]);
        $downloadPath = $path . "/" . $cikNumber . "_" . $modFillingType . "_" . $filingDate . ".xlsx";

        downloadFile($links[$i], $downloadPath);
    }
    echo "Files successfully saved to disks.";
}

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
