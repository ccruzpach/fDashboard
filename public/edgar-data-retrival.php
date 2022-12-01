<?php

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem;

class EDGARDataRetriever
{
    public $action;
    public $cikNumber;
    public $fillingType;
    public $fromDate;

    public function createSearchUrl(string $cikNumber, $fillingType, $fromDate)
    {
        for ($i = 0; strlen($cikNumber) < 10; $i++) {
            $cikNumber = '0' . $cikNumber;
        }        
        return "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=$cikNumber&type=$fillingType&datea=$fromDate&start=&output=html&count=100&owner=excluded";
    }

    public function getEdgarData(string $url)
    {
        $agent = "Mozilla/5.0 (X11; Linux x86_64; rv:60.0)";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }

    //TODO: SHOULD THIS BE STATIC OR A SERVICE CLASS
    public function parseDOM($htmlSource, $htmlTag = 'tr')
    {
        $dom = new DOMDocument();
        $dom->validateOnParse = true;
        @$dom->loadHTML($htmlSource);

        return $dom->getElementsByTagName($htmlTag);
    }

    function getFillingDates($cik, $fillingType)
    {
        $edgarData = $this->getEdgarData($this->createSearchUrl($cik, $fillingType, 20000101));

        $tRows = $this->parseDOM($edgarData, 'tr');
        $fillingList = [];

        foreach ($tRows as $row) {
            $row = explode("\n", trim($row->textContent));
            $tempArray = [];

            for ($i = 0; $i < count($row); $i++) {
                if (($i == 3 or $i == 4) and preg_match("/\s\d{4}-\d{2}-\d{2}/", $row[$i])) {
                    $tempArray[] = trim($row[$i]);
                }
            }
            $fillingList[] = $tempArray;
        }
        return array_slice($fillingList, 5);
    }

    public function extractLinksReferences(string $htmlSource)
    {
        $links = $this->parseDOM($htmlSource, 'a');

        $selectedLinks = [];

        foreach ($links as $link) {
            $selectedLinks[] = $link->getAttribute('href');
        }
        return $selectedLinks;
    }

    public function createCikLinks($htmlSource)
    {
        $links = $this->extractLinksReferences($htmlSource);

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

    public function createXlsLinks($htmlSource)
    {
        $links = $this->extractLinksReferences($htmlSource);

        $selectedLinks = [];

        foreach ($links as $link) {
            (preg_match('(.xls)', $link)) ? $selectedLinks[] = 'https://www.sec.gov' . $link : '';
        }
        return $selectedLinks;
    }

    function createHtmlLinks($htmlSource)
    {
        $links = $this->extractLinksReferences($htmlSource);

        $selectedLinks = [];

        foreach ($links as $link) {
            (preg_match('(/Archives/edgar/data/)', $link)) ? $selectedLinks[] = 'https://www.sec.gov' . $link : '';
        }
        return $selectedLinks;
    }

    function createHtmLFillingLinks($htmlSource)
    {
        $links = $this->extractLinksReferences($htmlSource);

        $selectedLinks = '';

        foreach ($links as $link) {
            (preg_match('(Archives/edgar/data/)', $link)) ? $selectedLinks = 'https://www.sec.gov' . $link : '';
        }
        return $selectedLinks;
    }

    public function getFillingsXlsUrls(string $cikNumber, string $fillingType, int $fromDate)
    {
        $results = $this->getEdgarData($this->createSearchUrl($cikNumber, $fillingType, $fromDate));
        $results = $this->createCikLinks($results);
        $dates = $this->getFillingDates($cikNumber, $fillingType);
        $newResults = [];

        for ($i = 0; $i < count($results); $i++) {
            $result = $this->getEdgarData($results[$i]);
            $result = $this->createXlsLinks($result);
            $newResults[] = [$dates[$i][0], $result[0]];
        }
        return $newResults;
    }

    public function downloadFile($url, $downloadPath, $agent = "Mozilla/5.0 (X11; Linux x86_64; rv:60.0)")
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

    public function downloadExcelFillings(string $cikNumber, string $fillingType, int $fromDate)
    {
        $modFillingType = str_replace("-", "", $fillingType);
        //TODO: set this into a configuration file 
        $path = storage_path('fillings/') . "$cikNumber/$modFillingType";
        File::ensureDirectoryExists($path);

        $links = $this->getFillingsXlsUrls($cikNumber, $fillingType, $fromDate);

        for ($i = 0; $i < count($links); $i++) {
            $filingDate = str_replace("-", "", $links[$i][0]);
            $downloadPath = $path . "/" . $cikNumber . "_" . $modFillingType . "_" . $filingDate . ".xlsx";

            $this->downloadFile($links[$i][1], $downloadPath);
        }
        echo "Files successfully saved to disks.";
    }
}

// Apple    320193
// AMD      002488
// PFE      078003

//SAMPLES OF DOWNLOAD PROCESS FOR FILES (ONLY LAST METHOD IS NECESSARY):
// $r = new EDGARDataRetriever();
// $url = $r->createSearchURL('2488', '10-K', 20010101);
// $results = $r->getEdgarData($url);
// $results = createCikLinks($results);
// $results = $r->getFillingsUrls('320193', '10-K', 20050101);
// $results = $r->downloadExcelFillings('078003', '10-K', 20050101);

//TO MEASURE SPEED OF EXECUTION
// $time_start = microtime(true);
    // ADD METHOD HERE
// $time_end = microtime(true);
// $time = $time_end - $time_start;

// echo " Operational Time: $time";