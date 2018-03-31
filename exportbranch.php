<?php

namespace BlasiusSecundus\WebtreesModules\BranchExport;
use Fisharebest\Webtrees\Database;
use Fisharebest\Webtrees\Filter;
use Fisharebest\Webtrees\Auth;
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Session;

require_once 'branchgenerator.php';

/**
 * Defined in session.php
 *
 * @global Tree   $WT_TREE
 */
global $WT_TREE;

define('WT_SCRIPT_NAME', 'modules_v3/branch_export/exportbranch.php');
require '../../includes/session.php';

header('Content-Type: text/html; charset=UTF-8');

if(!Auth::isMember($WT_TREE))
{    
    http_response_code(403);
    return; 
}

if(!Filter::checkCsrf()){
    http_response_code(406);
    return;
}


$cart = [];

$preset = Filter::postInteger("preset");
$use_preset = Filter::postBool("use_preset");

if($preset && $use_preset){
    $preset_data = Database::prepare("SELECT * FROM ##branch_export_presets WHERE preset_id = :preset_id AND tree_id = :tree_id")->execute(["preset_id"=>$preset, "tree_id"=>$WT_TREE->getTreeId()])->fetchOneRow(\PDO::FETCH_ASSOC);
    
    if(!$preset_data){
        die("Preset does not exists or does not belongs to the current tree.");
    }
    
    $pivot = $preset_data["pivot"];
    $cutoff = $preset_data["cutoff"];
   
}

else {
    $pivot = Filter::escapeHtml(Filter::post("pivot"));
    $cutoff = Filter::post("cutoff");
}

//validating pivot
if(!preg_match("/^I[0-9]+$/", $pivot)){
        die("Bad pivot XREF: $pivot");
}

$pivot_indi = Individual::getInstance($pivot, $WT_TREE);

if(!$pivot_indi)
{
    die("Cannot load pivot indi: ".Filter::escapeHtml($pivot));
}

//validating cutoff points
if(is_string($cutoff))
{
    $cutoff = explode(",",$cutoff);
}

if($cutoff){
    foreach($cutoff as $c)
    {
        if(!preg_match("/^(F|I)[0-9]+$/", $c)){
            die("Bad cutoff XREF: ".Filter::escapeHtml($c));
        }
    }
}
else 
{
    $cutoff = NULL;
}

$branchgenerator = new BranchGenerator($pivot, $cutoff);

foreach($branchgenerator->generateBranch() as $item)
{
    $cart[$item] = true;
}

Session::put("cart", [$WT_TREE->getTreeId() => $cart]);
Session::put("branch_export_pivot", $pivot);
Session::put("branch_export_cutoff", $cutoff);
Session::put("branch_export_preset", $preset);

header('Location:'.WT_BASE_URL.'module.php?mod=clippings&mod_action=index');