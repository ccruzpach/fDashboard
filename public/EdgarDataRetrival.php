<?php

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem;

class EDGARDataRetriever
{
    // SECTION
    // CREATE URLS AND RETRIEVE HTML DATA FROM URL

    public function createSearchUrl(string $cikNumber, string $fillingType, $fromDate = null)
    {
        for ($i = 0; strlen($cikNumber) < 10; $i++) {
            $cikNumber = '0' . $cikNumber;
        }

        //owner=include ====> gets forms 3, 4, and 5;
        //datea=$fromDate

        return "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=$cikNumber&type=$fillingType&dateb=&owner=include&count=100&search_text=";
    }

    public function getHtmlContent(string $url)
    {
        $agent = "Mozilla/5.0 (X11; Linux x86_64; rv:60.0)";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }

    public function getHtmlDocument($cikNumber, $fillingType, $fromDate)
    {
        return $this->getHtmlContent($this->createSearchUrl($cikNumber, $fillingType, $fromDate));
    }

    function extracHtmlByTag($domDocument, $htmlTag)
    {
        return $domDocument->getElementsByTagName($htmlTag);
    }

    public function getHtmlTags($htmlDocument, $htmlTag)
    {
        return $this->extracHtmlByTag($this->extractDOM($htmlDocument), $htmlTag);
    }

    // SECTION
    // PARSE HTML EXTRACT SPECIFIC HTML TAGS/ATTRIBUTES

    function extractDOM($htmlSource)
    {
        $domDocument = new DOMDocument();
        $domDocument->validateOnParse = true;
        @$domDocument->loadHTML($htmlSource);

        return $domDocument;
    }

    public function getFillingDates(string $cikNumber, string $fillingType, $fromDate)
    {
        $edgarData = $this->getHtmlDocument($cikNumber, $fillingType, $fromDate);        
        $tRows = $this->getHtmlTags($edgarData, 'tr');
        $fillingList = [];

        foreach ($tRows as $row) {
            $row = explode("\n", trim($row->textContent));
            $tempArray = [];

            for ($i = 0; $i < count($row); $i++) 
            {
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
        $links = $this->getHtmlTags($htmlSource, 'a');
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

    function createHtmlFillingLink($htmlSource)
    {
        $links = $this->extractLinksReferences($htmlSource);
        $selectedLinks = '';

        for ($j = 0; $j < count($links); $j++) {
            if (preg_match('(/Archives/edgar/data/)', $links[$j])) {
                $selectedLinks = 'https://www.sec.gov/' . $links[$j];
                break;
            }
        }
        return $selectedLinks;
    }

    //SECTION
    // EXTRACT ACCESSIBLE/DOWNLOADABLE LINKS FOR EDGAR FILLINGS

    public function getAllFillingsListByCompany(string $cikNumber, int $fromDate)
    {
        $edgarData = $this->getHtmlDocument($cikNumber, '', $fromDate);
        $tRows = $this->getHtmlTags($edgarData, 'tr');
        $fillingList = [];
        $tDocLinks = $this->getHtmlTags($edgarData, 'a');
        $fillingLinks = [];

        foreach ($tDocLinks as $link) {
            $l = $link->getAttribute('href');
            (preg_match('(Archives/edgar/data)', $l)) ? $fillingLinks[] = 'https://www.sec.gov' . $l : '';
        }

        foreach ($tRows as $row) {
            $row = trim($row->textContent);
            $row = explode("\n", $row);
            $tempArray = [];

            for ($i = 0; $i < count($row); $i++) {
                if (($i == 0)
                    or
                    ($i == 3 or $i == 4) and preg_match("/\s\d{4}-\d{2}-\d{2}/", $row[$i]))
                {
                    $tempArray[] = trim($row[$i]);
                }
            }
            $fillingList[] = implode(' | ', $tempArray);
        }
        return array_combine(array_slice($fillingList, 5, -1), $fillingLinks);
    }

    public function getFilingsHtmlsUrls(string $cikNumber, string $fillingType, int $fromDate)
    {
        $edgarData = $this->getHtmlDocument($cikNumber, $cikNumber, $fromDate);
        $results = $this->createHtmlLinks($edgarData);
        $newResults = [];

        for ($i = 0; $i < count($results); $i++) {
            $result = $this->getHtmlContent($results[$i]);
            $result = $this->createHtmlFillingLink($result);
            $newResults[] = $result;
        }
        return $newResults;
    }

    public function getFillingsXlsUrls(string $cikNumber, string $fillingType, int $fromDate)
    {
        $results = $this->getHtmlDocument($cikNumber, $fillingType, $fromDate);        
        $results = $this->createCikLinks($results);
        $dates = $this->getFillingDates($cikNumber, $fillingType, $fromDate);
        $newResults = [];

        for ($i = 0; $i < count($results); $i++) {
            $result = $this->getHtmlContent($results[$i]);
            $result = $this->createXlsLinks($result);
            $newResults[] = [$dates[$i][0], $result[0]];
        }
        return $newResults;
    }

    // SECTION
    // DOWNLOAD XLS FILES FROM EDGAR DATABASE

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



    /*
    *
    *
    * TESTED UP TO THIS POINT!
    *
    *
    *
    */




    // SECTION
    // GET SECTOR AND INDUSTRY INFORMATION
    
    public function getSICData($url)
    {
        $results = $this->extractDOM($this->getHtmlContent($url));
        $results = $this->extracHtmlByTag($results, 'tr');

        $newResults = [];

        for ($i = 0; $i < count($results); $i++) {
            if ($i == 0) {
                continue;
            } else {
                $result = explode("\n", $results[$i]->textContent);
                $tempArray = [];

                for ($j = 0; $j < count($result); $j++) {
                    if ($j == 1) {
                        $tempArray['sic_code'] = trim(str_replace("\t", "", $result[$j]));
                    } elseif ($j == 2) {
                        $tempArray['sector'] = trim(str_replace("\t", "", $result[$j]));
                    } elseif ($j == 3) {
                        $tempArray['industry'] = trim(str_replace("\t", "", $result[$j]));
                    }
                }
                $newResults[] = $tempArray;
            }
        }
        $newResults = json_encode($newResults);
        return $newResults;
    }

    //TODO: THIS FUNCTION DOES NOT WORK PROPERLY
    public function getsCompanyListByIndustry()
    {
        $sample = 'https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&SIC=3571&owner=include&start=0&count=100&hidefilings=0';
        $url1 = 'https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&SIC=100&owner=include&match=starts-with&start=40&count=100&hidefilings=0';
        $url2 = 'https://www.sec.gov/cgi-bin/browse-edgar?company=&match=starts-with&filenum=&State=&Country=&SIC=100&myowner=exclude&action=getcompany';

        $r = new EDGARDataRetriever();
        $urlData = $r->getHtmlContent($url1);
        $results2 = $r->parseDOM($urlData, 'span');
        $results = $r->parseDOM($urlData);
        $companies = [];
        $sicNumber = "";

        for ($i = 0; $i < count($results); $i++) {

            $result = explode("\n", trim($results[$i]->textContent));
            $tempList = [];

            for ($j = 0; $j < count($result); $j++) {
                $tempList[] = trim($result[$j]);
            }
            $companies[] = $tempList;
        }

        for ($i = 0; $i < count($results2); $i++) {
            $r = $results2[$i]->textContent;
            preg_match('(\d+)', $r, $sicNumber);
            // $sicInfo = $industry;

            break;
        }

        $sicNumber = implode($sicNumber);
        array_unshift($companies, $sicNumber);

        $path = storage_path('/sic');
        File::ensureDirectoryExists($path);
        file_put_contents($path . $sicNumber . ".json", json_encode($companies));

        dd($companies);
    }
}

// Apple    320193
// AMD      002488
// PFE      078003

//SAMPLES OF DOWNLOAD PROCESS FOR FILES (ONLY LAST METHOD IS NECESSARY):
// $r = new EDGARDataRetriever();
// $url = $r->createSearchURL('2488', '10-K', 20010101);
// $results = $r->getHtmlContent($url);
// $results = createCikLinks($results);
// $results = $r->getFillingsUrls('320193', '10-K', 20050101);
// $results = $r->downloadExcelFillings('078003', '10-K', 20050101);

//TO MEASURE SPEED OF EXECUTION
// $time_start = microtime(true);
    // ADD METHOD HERE
// $time_end = microtime(true);
// $time = $time_end - $time_start;

// echo " Operational Time: $time";