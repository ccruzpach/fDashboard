<?php

require_once app_path('Services/4.extractEdgarFillingsUrls.php');

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
//TODO: FUNCITION SHOULD BE SPLIT SO THAT THE FOR LOOP IS IN THE SAME FILE AS THE FUNCTION ABOVE IT?????
function downloadExcelFillings(string $cikNumber, string $fillingType, int $fromDate)
{
    $modFillingType = str_replace("-", "", $fillingType);
    //TODO: set this into a configuration file 
    $path = storage_path('fillings/') . "$cikNumber/$modFillingType";
    File::ensureDirectoryExists($path);

    $links = getFillingsXlsUrls($cikNumber, $fillingType, $fromDate);

    for ($i = 0; $i < count($links); $i++) {
        $filingDate = str_replace("-", "", $links[$i][0]);
        $downloadPath = $path . "/" . $cikNumber . "_" . $modFillingType . "_" . $filingDate . ".xlsx";

        downloadFile($links[$i][1], $downloadPath);
    }
    echo "Files successfully saved to disks.";
}
