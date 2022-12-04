
@extends('components/layout')

{{-- 
<link rel="stylesheet" href="/app.css">

<?php

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Storage;

require public_path('edgar-data-processor.php');
require public_path('edgar-data-retrival.php');

$sectorsAndInsdustries = 'https://www.sec.gov/corpfin/division-of-corporation-finance-standard-industrial-classification-sic-code-list'


$r = new EDGARDataRetriever();
$p = new EDGARDataProcessor();





?>




{{--
<!DOCTYPE html>

<title>Document</title>
<link rel="stylesheet" href="/app.css">

<body>
    <header>Header</header>

    <div class="main">        
        <div class="section">Search Bar</div>
        
        <div class="section" style="">
            <p style="text-align: center">Trading Information</p>
            <div style="display:flex; justify-content: space-evenly">
                <p>High: 100</p>
                <p>Lo: 35</p>
                <p>Daily Open: 86.5</p>
                <p>Daily Close: 89.99</p>
            </div>
        </div>
        
        <div class="section">
            <p>Company Description</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        </div>
        
        <div class="section-flex">
            <div id="main-column" class="section" style="width:65%">Financial Table</div>
            <div id="right-side-column" class="section" style="width=35%">All Fillings for Ticker
            <ul>
                @foreach(array_slice($results, 0, 7) as $result)
                {
                    '<li>{{$result}}</li>';
}
@endforeach
</ul>

</div>
</div>
</div>

<footer>Footer</footer>
</body>
--}}
