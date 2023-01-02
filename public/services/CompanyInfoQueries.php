<?php

use App\Models\Cik;
use App\Models\Sic;
use App\Models\Industry;
use App\Models\Sector;
use App\Models\Company;

function getCompanyName($stockSymbol)
{
    return Company::where('stock_symbol', $stockSymbol)->get()->first();
}

function getCompanySymbol($cikNumber)
{
    $cikId = Cik::where('cik_number', $cikNumber)->get()->first()->id;
    return Company::where('cik_id', $cikId)->get()->first()->stock_symbol;
}

function getCompanyCIK($stockSymbol)
{
    return Cik::where('id', getCompanyName($stockSymbol)->cik_id)->get()->first();
}

function getCompanySIC($stockSymbol)
{
    return Sic::where('id', getCompanyCIK($stockSymbol)->sic_id)->get()->first();
}

function getCompanyIndustry($stockSymbol)
{
    return Industry::where('id', getCompanySIC($stockSymbol)->industry_id)->first();
}

function getCompanySector($stockSymbol)
{
    return Sector::where('id', getCompanyIndustry($stockSymbol)->sector_id)->first();
}

?>