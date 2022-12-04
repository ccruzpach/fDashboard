<?php

require public_path('edgar-data-processor.php');
require public_path('edgar-data-retrival.php');

$url = 'https://www.sec.gov/corpfin/division-of-corporation-finance-standard-industrial-classification-sic-code-list';


$r = new EDGARDataRetriever();



function getSICData()
{
    $url = 'https://www.sec.gov/corpfin/division-of-corporation-finance-standard-industrial-classification-sic-code-list';

    $results = $this->parseDOM($this->getEdgarData($url));

    $newResults = [];

    for ($i = 0; $i < count($results); $i++)
    {
        if ($i == 0)
        {
            continue;
        } else {
            $result = explode("\n", $results[$i]->textContent);
            $tempArray = [];

            for ($j = 0; $j < count($result); $j++)
            {
                if ($j == 1) {
                    $tempArray['sic_code'] = trim(str_replace("\t", "", $result[$j]));
                } elseif ($j == 2) {
                    $tempArray['sector'] = trim(str_replace("\t", "", $result[$j]));
                }
                elseif ($j == 3) {
                    $tempArray['industry'] = trim(str_replace("\t", "", $result[$j]));
                }
            }
            $newResults[] = $tempArray;
        }
    }
    return $newResults;
}



$url = 'https://www.sec.gov/corpfin/division-of-corporation-finance-standard-industrial-classification-sic-code-list';
$results = $r->parseDOM($r->getEdgarData($url));

$newResults = [];

for ($i = 0; $i < count($results); $i++)
{
    if ($i == 0)
    {
        continue;
    } else {
        $result = explode("\n", $results[$i]->textContent);
        $tempArray = [];

        for ($j = 0; $j < count($result); $j++)
        {
            if ($j == 1) {
                $tempArray['sic_code'] = trim(str_replace("\t", "", $result[$j]));
            } elseif ($j == 2) {
                $tempArray['code'] = trim(str_replace("\t", "", $result[$j]));
            }
            elseif ($j == 3) {
                $tempArray['industry'] = trim(str_replace("\t", "", $result[$j]));
            }
        }
        $newResults[] = $tempArray;
    }
}


// foreach ($results as $result)
// {
//     $result = explode("\n", $result->textContent);
//     $tempArray = [];
    
//     foreach ($result as $r)
//     {
//         $tempArray[] = trim(str_replace("\t", "", $r));
//     }
//     array_shift($tempArray);
//     array_pop($tempArray);

//     $newResults[] = $tempArray;
// }
// array_shift($newResults);
// $keys = ['SIC Code','Office','Industry Title'];


dd($newResults);













?> 

