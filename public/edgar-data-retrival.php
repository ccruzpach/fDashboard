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
    public $toDate;
    public $owner;
    public $output;
    public $count;
    public $agent;

    public function getEdgarData(string $urlString, string $cikNumber = null, $fillingType = null, $fromDate = null, $toDate = null, $output = 'html', $count = 100, $owner = 'exclude', $action = 'getcompany', $agent = "Mozilla/5.0 (X11; Linux x86_64; rv:60.0)") 
    {
        // For Loop for readability?????
        switch (strlen($cikNumber)) {
            case 5:
                $cikNumber = '00000' . $cikNumber;
                break;
            case 6:
                $cikNumber = '0000' . $cikNumber;
                break;
            case 7:
                $cikNumber = '000' . $cikNumber;
                break;
            case 8:
                $cikNumber = '00' . $cikNumber;
                break;
            case 9:
                $cikNumber = '0' . $cikNumber;
                break;
        }

        $url = "";
        ($urlString) ? $url = $urlString : $url = "https://www.sec.gov/cgi-bin/browse-edgar?action=$action&CIK=$cikNumber&type=$fillingType&datea=$fromDate&start=&output=$output&count=$count&owner=$owner";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }

    public function getSeachResulsURLs(string $htmlSource, string $htmlAttribute, string $stringMatcher, bool $isExcelFile = false)
    {
        $dom = new DOMDocument();
        @ $dom->loadHTML($htmlSource);

        $links = $dom->getElementsByTagName('a');
        $selectedLinks = [];

        foreach ($links as $link)
        {
            $link = $link->getAttribute($htmlAttribute);

            if ((preg_match($stringMatcher, $link) and preg_match('(action=view)', $link) and !preg_match('(output=atom)', $link))
                or
                ($isExcelFile and preg_match('(.xls)', $link)))
            {
                $selectedLinks[] = 'https://www.sec.gov' . $link;
            } 
        }
        return $selectedLinks;
    }

    public function getFillingsUrls(string $urlString, string $cikNumber, string $fillingType, int $fromDate, int $toDate, string $htmlAttribute, string $stringMatcher, bool $isExcelFile = false)
    {
        $results = $this->getEdgarData($urlString, $cikNumber, $fillingType, $fromDate, $toDate);
        $results = $this->getSeachResulsURLs($results, $htmlAttribute, $stringMatcher);

        $newResults = [];

        foreach ($results as $result)
        {
            $result = $this->getEdgarData($result);
            $result = $this->getSeachResulsURLs($result, $htmlAttribute, '(.xls)', true);
            $newResults[] = $result[0];
        }
        return $newResults;
    }

    public function downloadExcelFillings(string $urlString, string $cikNumber, string $fillingType, int $fromDate, int $toDate, string $htmlAttribute, string $stringMatcher, $agent = "Mozilla/5.0 (X11; Linux x86_64; rv:60.0)")
    {
        $modFillingType = str_replace("-", "", $fillingType);
        $counter = 1;
        $path = "./fillings/$cikNumber/$modFillingType";

        File::ensureDirectoryExists($path);
        
        $links = $this->getFillingsUrls($urlString, $cikNumber, $fillingType, $fromDate, $toDate, $htmlAttribute, $stringMatcher);

        foreach ($links as $link)
        {
            set_time_limit(0);            
            $fp = fopen (public_path('fillings/') . "$cikNumber/$modFillingType/$cikNumber" . "_" . "$modFillingType" . "_" . "$counter.xlsx", 'w+');
            $ch = curl_init(str_replace(" ","%20", $link));
            curl_setopt($ch, CURLOPT_TIMEOUT, 600);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent); // THIS MESSES UP THE XLSX FILE
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
// $results = $test->getEdgarData('', '0320193', '10-K', 20010101, 20221231);
// $results = $test->getSeachResulsURLs($results3, 'href', '(CIK|cik)');
// $results = $test->getFillingsUrls('', '320193', '10-K', 20050101, 20221231, 'href', '(CIK|cik)');
// $results = $test->downloadExcelFillings('', '320193', '10-K', 20050101, 20221231, 'href', '(CIK|cik)');

// $time_start = microtime(true);
// $test = new EDGARDataRetriever();
// $results = $test->downloadExcelFillings('', '002488', '10-K', 20050101, 20221231, 'href', '(CIK|cik)', false);
// $time_end = microtime(true);
// $time = $time_end - $time_start;

// echo " Operational Time: $time";