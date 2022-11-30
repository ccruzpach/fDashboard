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
        for ($i = 0; strlen($cikNumber) < 10; $i++)
        {
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

    // THESE METHODS EXIST TO SUPPORT THE XLSX/XLS FILE DOWNLOAD
    public function extractLinksReferences(string $htmlSource)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlSource);
        $links = $dom->getElementsByTagName('a');

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

        foreach ($links as $link) {
            if (preg_match('/cik/i', $link) and preg_match('(action=view)', $link)) {
                $selectedLinks[] = 'https://www.sec.gov' . $link;
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

    public function getFillingsUrls(string $cikNumber, string $fillingType, int $fromDate, bool $isExcelFile = false)
    {
        $results = $this->getEdgarData($this->createSearchUrl($cikNumber, $fillingType, $fromDate));
        $results = $this->createCikLinks($results);

        $newResults = [];

        foreach ($results as $result) {
            $result = $this->getEdgarData($result);
            $result = $this->createXlsLinks($result);
            $newResults[] = $result[0];
        }
        return $newResults;
    }

    public function downloadExcelFillings(string $cikNumber, string $fillingType, int $fromDate, $agent = "Mozilla/5.0 (X11; Linux x86_64; rv:60.0)")
    {
        $modFillingType = str_replace("-", "", $fillingType);

        //TODO: set this into a configuration fiule | Change folder to storage 
        $path = "./fillings/$cikNumber/$modFillingType";
        File::ensureDirectoryExists($path);

        $links = $this->getFillingsUrls($cikNumber, $fillingType, $fromDate);
        //TODO: Change counter for filling date.
        $counter = 1;
        foreach ($links as $link) {
            //TODO: Extract to function "downloadFile"
            set_time_limit(0);

            $ch = curl_init(str_replace(" ", "%20", $link));
            curl_setopt($ch, CURLOPT_TIMEOUT, 600);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent); // THIS MESSES UP THE XLSX FILE
            $fp = fopen(public_path('fillings/') . "$cikNumber/$modFillingType/$cikNumber" . "_" . "$modFillingType" . "_" . "$counter.xlsx", 'w+');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            $counter++;
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