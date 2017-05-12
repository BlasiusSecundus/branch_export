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

define('WT_SCRIPT_NAME', 'modules_v3/branch_export/deletebranchexportpreset.php');
require '../../includes/session.php';

header('Content-Type: text/json; charset=UTF-8');

$tree_id = $WT_TREE->getTreeId();
$name = Filter::post("name");


try{

$num_deleted = Database::prepare("DELETE FROM ##branch_export_presets WHERE tree_id = :tree_id AND name = :name")->execute(["tree_id"=>$tree_id,"name"=>$name])->rowCount();

if($num_deleted == 0)
{
    throw new \Exception(sprintf(I18N::translate("Unable to delete preset '%s'. Preset does not exist?"),$name));
}

//now getting the updated list of presets
$presets = Database::prepare("SELECT * FROM ##branch_export_presets WHERE tree_id = :tree_id ORDER BY name")->execute(["tree_id"=>$tree_id])->fetchAll(\PDO::FETCH_ASSOC);

echo json_encode( ["presets"=>$presets,"selected"=>Filter::post("selected")]);
}
catch(\Exception $ex)
{
    echo json_encode([
        "error"=>[
            "message" => $ex->getMessage()
        ]
    ]);
}