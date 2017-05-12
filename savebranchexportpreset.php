<?php

namespace BlasiusSecundus\WebtreesModules\BranchExport;
use Fisharebest\Webtrees\Filter;
use Fisharebest\Webtrees\Database;
use Fisharebest\Webtrees\I18N;

/**
 * Defined in session.php
 *
 * @global Tree   $WT_TREE
 */
global $WT_TREE;

define('WT_SCRIPT_NAME', 'modules_v3/branch_export/savebranchexportpreset.php');
require '../../includes/session.php';

header('Content-Type: text/json; charset=UTF-8');

$tree_id = $WT_TREE->getTreeId();
$pivot = Filter::post("pivot");
$name = Filter::post("name");
$cutoff = Filter::post("cutoff");
$rename = Filter::post("rename");
$preset_to_rename = Filter::post("preset_to_rename");

if(is_array($cutoff)){
    $cutoff = implode(",",$cutoff);
}

try{
//checking if a preset with the same name exists

$preset_id = null;



$search_existing = Database::prepare("SELECT preset_id FROM ##branch_export_presets WHERE name = :name AND tree_id = :tree_id")->execute(["name"=>$rename ? $preset_to_rename : $name,"tree_id"=>$tree_id])->fetchOneRow();


if($search_existing)
{

  $preset_id = $search_existing->preset_id;
    
}

if($preset_id){
//update
Database::prepare("UPDATE ##branch_export_presets SET pivot = :pivot, cutoff = :cutoff, name = :name WHERE preset_id =:preset_id")->execute(["name"=>$name,"pivot"=>$pivot,"cutoff"=>$cutoff,"preset_id"=>$preset_id]);
}
else{
//insert new entry
Database::prepare("INSERT INTO ##branch_export_presets SET tree_id = :tree_id, name=:name, pivot = :pivot, cutoff = :cutoff")->execute(["tree_id"=>$tree_id,"name"=>$name,"pivot"=>$pivot,"cutoff"=>$cutoff]);
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