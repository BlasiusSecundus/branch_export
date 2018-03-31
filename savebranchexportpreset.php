<?php

namespace BlasiusSecundus\WebtreesModules\BranchExport;
use Fisharebest\Webtrees\Filter;
use Fisharebest\Webtrees\Database;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Auth;

/**
 * Defined in session.php
 *
 * @global Tree   $WT_TREE
 */
global $WT_TREE;

define('WT_SCRIPT_NAME', 'modules_v3/branch_export/savebranchexportpreset.php');
require '../../includes/session.php';

header('Content-Type: text/json; charset=UTF-8');

if(!Filter::checkCsrf()){
    http_response_code(406);
    return;
} 

$tree_id = $WT_TREE->getTreeId();
$pivot = Filter::escapeHtml(Filter::post("pivot"));
$name = Filter::escapeHtml(Filter::post("name"));
$cutoff = Filter::post("cutoff");
$rename = Filter::escapeHtml(Filter::post("rename"));
$preset_to_rename = Filter::escapeHtml(Filter::post("preset_to_rename"));
$member = Auth::isMember($WT_TREE);



if(is_array($cutoff)){
    $cutoff = implode(",",$cutoff);
}

$cutoff = Filter::escapeHtml($cutoff);

try{
//checking if a preset with the same name exists
if(!$member)    
    throw new \Exception(sprintf(I18N::translate("The current user is not authorized to modify presets belonging to '%s'"), $WT_TREE->getName()));

$preset_id = null;

if($rename)
{
    $preset_id = $preset_to_rename;
}
else
{

    $search_existing = Database::prepare("SELECT preset_id FROM ##branch_export_presets WHERE name = :name AND tree_id = :tree_id")->execute(["name"=>$name,"tree_id"=>$tree_id])->fetchOneRow();
   
    $preset_id = $search_existing ? $search_existing->preset_id : NULL;

}

if($preset_id){
//update
$num_udapted = Database::prepare("UPDATE ##branch_export_presets SET pivot = :pivot, cutoff = :cutoff, name = :name WHERE preset_id =:preset_id")->execute(["name"=>$name,"pivot"=>$pivot,"cutoff"=>$cutoff,"preset_id"=>$preset_id])->rowCount();
}
else{
//insert new entry
$new_added = Database::prepare("INSERT INTO ##branch_export_presets SET tree_id = :tree_id, name=:name, pivot = :pivot, cutoff = :cutoff")->execute(["tree_id"=>$tree_id,"name"=>$name,"pivot"=>$pivot,"cutoff"=>$cutoff])->rowCount();
}
//now getting the updated list of presets
$presets = Database::prepare("SELECT * FROM ##branch_export_presets WHERE tree_id = :tree_id ORDER BY name")->execute(["tree_id"=>$tree_id])->fetchAll(\PDO::FETCH_ASSOC);

echo json_encode( ["presets"=>$presets,"selected"=>$name]);
}
catch(\Exception $ex)
{
    echo json_encode([
        "error"=>[
            "message" => $ex->getMessage()
        ]
    ]);
}