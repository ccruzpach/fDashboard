<!DOCTYPE html>

<title>Document</title>
<link rel="stylesheet" href="/app.css">

<body>
    
<div>Trading Informaiton</div>    
<div>Company Description</div>
<div style="display: flex; flex-direction: row">
    <div id="main-column" style="margin-right: 20px;">Financial Table</div>
    <div id="right-side-column">All Fillings for Ticker</div>
</div>

</body>

<?php

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Storage;

require '/home/cruzpach/Documents/dev/fDashboard/public/edgar-data-retrival.php';
require '/home/cruzpach/Documents/dev/fDashboard/public/edgar-data-processor.php';
// $path = '/home/cruzpach/Documents/dev/fDashboard/public/fillings/002488/10K/002488_10K_1.xlsx';
// $path2 = '/home/cruzpach/Documents/dev/fDashboard/public/fillings/002488/10Q/002488_10Q_1.xlsx';

//TODO
// EXTRACT FILLING DATE FROM SEARCH RESULTS AND ADD IT TO FILLING NAME!

// $r = new EDGARDataRetriever();
$p = new EDGARDataProcessor();


// $results = $p->getFillingsListByCompany('078003');
// Apple    320193
// AMD      002488
// PFE      078003

dd($p->populateCIKDatabase())


