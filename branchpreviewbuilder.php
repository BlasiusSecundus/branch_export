<?php

namespace BlasiusSecundus\WebtreesModules\BranchExport;
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Family;
use Fisharebest\Webtrees\Media;
use Fisharebest\Webtrees\Note;
use Fisharebest\Webtrees\Repository;
use Fisharebest\Webtrees\Source;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\Database;

/**
 * Branch generator class.
 */
class BranchPreviewBuilder
{
    
   
    /**
     *
     * @var BranchGenerator
     */
    protected $BranchGenerator = NULL;
    
    /**
     * 
     * @param BranchGenerator $branch_generatator
     */
    public function __construct($branch_generatator) {
        $this->BranchGenerator = $branch_generatator;
    }
    /**
     * Prints a preview table with the specified records.
     * @param string $caption Table caption.
     */
    protected function printBranchPreviewTable($caption, $type_filter = null)
    {
        ?>
        <table class="sortable list_table width50 branch-preview-table">
            <caption><?php echo $type_filter ? sprintf($caption,$type_filter) : $caption;?> (<?php echo $type_filter ? $this->BranchGenerator->numRecordInBranch($type_filter) : count($records);?>)</caption>
            <thead>
                <tr>
                    <th class="list_label">XREF</th>
                    <th class="list_label"><?php echo I18N::translate("Full name");?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($this->BranchGenerator->getBranchRecords() as $xref => $record):
    if($type_filter && $record::RECORD_TYPE !== $type_filter) { continue; }
                    ?>
                <tr>
                    <td class="list_value"><?php echo $xref;?></td>
                    <td class="list_value">
                        <?php 
                        $iconclass = "";
                        switch($record::RECORD_TYPE)
                        {
                            case Individual::RECORD_TYPE:
                                $iconclass="icon-indis";
                                break;
                            case Family::RECORD_TYPE:
                                $iconclass="icon-sfamily";
                                break;
                            case Media::RECORD_TYPE:
                                $iconclass="icon-media";
                                break;
                            case Note::RECORD_TYPE:
                                $iconclass="icon-note";
                                break;
                            case Source::RECORD_TYPE:
                                $iconclass="icon-source";
                                break;
                            case Repository::RECORD_TYPE:
                                $iconclass="icon-repository";
                                break;
                            
                        }?>
                        
                        
                        <i class="<?php echo $iconclass;?>"></i>
                        <?php if($record):?>
                        <a href="<?php echo $record->getHtmlUrl();?>" target="_blank"><?php echo $record->getFullName();?></a>
                        <?php else:?>
                        NULL
                        <?php endif;?>
                        
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>

        <?php
    }
    protected function printBranchRecordStats($record_type)
    {
        ?>
        <p>
            <span class="branch-preview-summary-label"><?php echo sprintf(I18N::translate("Total %s records in this tree",$record_type))?>:</span> <?php echo $num_records_in_tree = $this->numRecordOfTypeInTree($record_type);?>
                <br>
                <span class="branch-preview-summary-label"><?php echo sprintf(I18N::translate("%s records in this branch",$record_type))?>:</span> <?php echo $num_records_in_branch = $this->BranchGenerator->numRecordInBranch($record_type)?> 
                <?php if($num_records_in_tree) { echo "(".round(100*($num_records_in_branch / $num_records_in_tree),2)."%)"; }?>
            </p>
       <?php
    }
    
    /**
     * 
     * @global Tree $WT_TREE
     */
    protected function numRecordOfTypeInTree($type)
    {
        global $WT_TREE;
        
        $tree_id = $WT_TREE->getTreeId();
        
        $table_name = "";
        $id_field= "";
        $tree_id_field = "";
        $o_type = "";
        switch($type)
        {
            case Individual::RECORD_TYPE:
                $table_name="##individuals";
                $id_field="i_id";
                $tree_id_field="i_file";
                break;
            case Family::RECORD_TYPE:
                $table_name="##families";
                $id_field="f_id";
                $tree_id_field="f_file";
                break;
            case Media::RECORD_TYPE:
                $table_name="##media";
                $id_field="m_id";
                $tree_id_field="m_file";
                break;
            case Note::RECORD_TYPE:
                $table_name="##other";
                $id_field="o_id";
                $tree_id_field="o_file";
                $o_type = "NOTE";
                break;
            case Repository::RECORD_TYPE:
                
                if(WT_SCHEMA_VERSION > 37)//after migration 37, repos are moved to its own table
                {
                    $table_name="##repository";
                    $id_prefix="repository_id";
                    $tree_id_field="gedcom_id";
                }
                else//before that they were stored in the wt_other table
                    {
                    $table_name="##other";
                    $id_field="o_id";
                    $tree_id_field="o_file";
                    $o_type = "REPO";
                }

                break;
            case Source::RECORD_TYPE:
                $table_name="##sources";
                $id_field="s_id";
                $tree_id_field="s_file";
                break;
        }
        
        if(!$table_name)
        {
            return 0;
        }
        
        $query  = "SELECT COUNT(DISTINCT $id_field) FROM $table_name WHERE $tree_id_field = :tree_id";
        $query_params = ["tree_id"=>$tree_id];
        
        if($table_name === "##other")
        {
            $query_params["o_type"]=$o_type;
            $query.=" AND o_type = :o_type";
        }
        
        return intval(Database::prepare($query)->execute($query_params)->fetchOne());
    }
    
    /**
     * Prints the branch preview.
     */
    public function printBranchPreview()
    {

        ?>
        <h2 class="branch-preview-heading"><?php echo I18N::translate("Branch preview")?></h2>
        <div class="branch-preview-content">
            <p>
                <?php echo I18N::translate("Pivot individual")?>: <a href='<?php echo $this->BranchGenerator->getPivotIndi()->getHtmlUrl();?>' target='_blank'><?php echo $this->BranchGenerator->getPivotIndi()->getFullName();?></a>;
            </p>
            <p>
                <?php echo I18N::translate("Cutoff points:");?>
            </p>
            <ul>
                <?php foreach($this->BranchGenerator->getCutoffpointRecords() as $cutoff):?>
                <li><a href='<?php echo $cutoff->getHtmlUrl();?>' target='_blank'><?php echo $cutoff->getFullName();?></a> (<?php echo $cutoff->getXref();?>)</li>
                <?php endforeach;?>
            </ul>
            <?php 
            
            foreach([Individual::RECORD_TYPE, Family::RECORD_TYPE, Media::RECORD_TYPE, Note::RECORD_TYPE, Repository::RECORD_TYPE, Source::RECORD_TYPE] as $type)   {   
            $this->printBranchRecordStats($type);
                    
            }
            ?>
           
        </div>
        <?php
        
        foreach([Individual::RECORD_TYPE, Family::RECORD_TYPE, Media::RECORD_TYPE, Note::RECORD_TYPE, Repository::RECORD_TYPE, Source::RECORD_TYPE] as $type)   {   
         if($this->BranchGenerator->numRecordInBranch($type) === 0) {continue;}
         $this->printBranchPreviewTable(I18N::translate("%s records in the branch"),$type);
        }
        
    }
}