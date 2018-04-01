<?php

namespace BlasiusSecundus\WebtreesModules\BranchExport;
use Fisharebest\Webtrees\Filter;
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Auth;

require_once 'branchgenerator.php';
require_once 'branchpreviewbuilder.php';
require_once 'branchexportutils.php';

/**
 * Defined in session.php
 *
 * @global Tree   $WT_TREE
 */
global $WT_TREE;

define('WT_SCRIPT_NAME', 'modules_v3/branch_export/getbranchpreview.php');
require '../../includes/session.php';

header('Content-Type: text/html; charset=UTF-8');

if(!Filter::checkCsrf()){
    http_response_code(406);
    return;
} 

$tree_id = $WT_TREE->getTreeId();
$member = Auth::isMember($WT_TREE);


try{

if(!$member)    
    throw new \Exception(sprintf(I18N::translate("The current user is not authorized to use branch export for '%s'"), $WT_TREE->getName()));
    
    ///

    $cutoff_points = Filter::post("cutoff");
    
    
    if($cutoff_points && is_string($cutoff_points))
    {
        $cutoff_points = explode(",", $cutoff_points);
    }
    
    if($cutoff_points){
            if(!BranchExportUtils::validateCutoffArray($cutoff_points)){
                throw new \Exception("Bad cutoff XREF.");
            }
    }
    
    $pivot_xref = Filter::escapeHtml(Filter::post("pivot"));
    
    if(!$pivot_xref)
    {
        throw new \Exception(I18N::translate("No pivot individual provided."));
    }
    
    //validating pivot
    if(!BranchExportUtils::validatePivot($pivot_xref)){
            throw new \Exception("Bad pivot XREF: $pivot_xref.");
    }
    
    $pivot_indi = Individual::getInstance($pivot_xref, $WT_TREE);
    
    if(!$pivot_indi)
    {
        throw new \Exception(I18N::translate(sprintf("Invalid pivot point selected: %s. No such individual found in the current tree.", $pivot_xref)));
    }
    
    $generator = new BranchGenerator($pivot_indi, $cutoff_points);
    
    $preview_builder = new BranchPreviewBuilder($generator);
    
    $preview_builder->printBranchPreview();
    

    ///
}
catch(\Exception $ex)
{
    header('Content-Type: text/json; charset=UTF-8');
    echo json_encode([
        "error"=>[
            "message" => $ex->getMessage()
        ]
    ]);
}