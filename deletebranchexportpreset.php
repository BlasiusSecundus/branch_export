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

define('WT_SCRIPT_NAME', 'modules_v3/branch_export/deletebranchexportpreset.php');
require '../../includes/session.php';

header('Content-Type: text/json; charset=UTF-8');

if(!Filter::checkCsrf()){
    http_response_code(406);
    return;
} 

$tree_id = $WT_TREE->getTreeId();
$name = htmlspecialchars(Filter::post("name"));
$member = Auth::isMember($WT_TREE);


try{

if(!$member)    
    throw new \Exception(sprintf(I18N::translate("The current user is not authorized to modify presets belonging to '%s'"), $WT_TREE->getName()));
    
$num_deleted = Database::prepare("DELETE FROM ##branch_export_presets WHERE tree_id = :tree_id AND name = :name")->execute(["tree_id"=>$tree_id,"name"=>$name])->rowCount();

if($num_deleted == 0)
{
    throw new \Exception(sprintf(I18N::translate("Unable to delete preset '%s'. Preset does not exist?"),$name));
}

//now getting the updated list of presets
$presets = Database::prepare("SELECT * FROM ##branch_export_presets WHERE tree_id = :tree_id ORDER BY name")->execute(["tree_id"=>$tree_id])->fetchAll(\PDO::FETCH_ASSOC);

echo json_encode( ["presets"=>$presets,"selected"=>Filter::postInteger("selected")]);
}
catch(\Exception $ex)
{
    echo json_encode([
        "error"=>[
            "message" => $ex->getMessage()
        ]
    ]);
}