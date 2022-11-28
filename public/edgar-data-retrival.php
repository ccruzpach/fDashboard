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

    public function getEdgarData(string $urlString, string $cikNumber = null, $fillingType = null, $fromDate = null) 
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
        ($urlString) ? $url = $urlString : $url = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=$cikNumber&type=$fillingType&datea=$fromDate&start=&output=html&count=100&owner=excluded";

        $agent = "Mozilla/5.0 (X11; Linux x86_64; rv:60.0)";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }

    public function getSeachResulsURLs(string $htmlSource, bool $isExcelFile = false)
    {
        $dom = new DOMDocument();
        @ $dom->loadHTML($htmlSource);

        $links = $dom->getElementsByTagName('a');
        $selectedLinks = [];

        foreach ($links as $link)
        {
            $link = $link->getAttribute('href');

            if ((preg_match('(CIK|cik)', $link) and preg_match('(action=view)', $link) and !preg_match('(output=atom)', $link))
                or
                ($isExcelFile and preg_match('(.xls)', $link)))
            {
                $selectedLinks[] = 'https://www.sec.gov' . $link;
            } 
        }
        return $selectedLinks;
    }

    public function getFillingsUrls(string $cikNumber, string $fillingType, int $fromDate, bool $isExcelFile = false)
    {
        $results = $this->getEdgarData("", $cikNumber, $fillingType, $fromDate);
        $results = $this->getSeachResulsURLs($results);

        $newResults = [];

        foreach ($results as $result)
        {
            $result = $this->getEdgarData($result);
            $result = $this->getSeachResulsURLs($result, true);
            $newResults[] = $result[0];
        }
        return $newResults;
    }

    public function downloadExcelFillings(string $cikNumber, string $fillingType, int $fromDate, $agent = "Mozilla/5.0 (X11; Linux x86_64; rv:60.0)")
    {
        $modFillingType = str_replace("-", "", $fillingType);
        $counter = 1;
        $path = "./fillings/$cikNumber/$modFillingType";

        File::ensureDirectoryExists($path);
        
        $links = $this->getFillingsUrls($cikNumber, $fillingType, $fromDate);

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

//SAMPLES OF DOWNLOAD PROCESS FOR FILES (ONLY LAST METHOD IS NECESSARY):
// $r= new EDGARDataRetriever();
// $results = $r->getEdgarData('', '0320193', '10-K', 20010101);
// $results = $r->getSeachResulsURLs($results);
// $results = $r->getFillingsUrls('320193', '10-K', 20050101);
// $results = $r->downloadExcelFillings('078003', '10-K', 20050101);


//TO GET A LIST OF LATEST FILLINGS BY COMPANY:
// $results = $r->getEdgarData('', '078003', '', 20010101, 20221231);
// $results = $p->getFillingsListByCompany($results);


//TO MEASURE SPEED OF EXECUTION
// $time_start = microtime(true);
    // ADD METHOD HERE
// $time_end = microtime(true);
// $time = $time_end - $time_start;

// echo " Operational Time: $time";