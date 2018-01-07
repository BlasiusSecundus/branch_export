<?php

namespace BlasiusSecundus\WebtreesModules\BranchExport;
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\Database;

/**
 * Branch generator class.
 */
class BranchGenerator
{
    
    /**
     *
     * @var string[] An array of indi and family XREFs, used as cutoff points. 
     */
    protected $InputCutoffPoints = array();
    
    /**
     *
     * @var string[] An array containing only indi XREFs - all user-provided cutoff individuals + members of cutoff families.  
     */
    protected $CutoffPoints = array();
    
    /**
     *
     * @var Individual The pivot individual.
     */
    protected $PivotIndi = NULL;
    /**
     *
     * @var Tree The current family tree.
     */
    protected $Tree = NULL;
    
    
     /**
     *
     * @var Individual[] Associative array of individuals included in the branch (key = XREF, value = Individual object). 
     */
    protected $BranchIndies = array();
    
    /**
     *
     * @var string[] XREF of individuals who failed to load while traversing the tree. 
     */
    protected $NullIndies = array();
    
    /**
     *
     * @var string[] XREF of individuals who are already processed. 
     */
    protected $ProcessedIndies = array();
    
    /**
     *
     * @var type string[] XREFs of records in the branch.
     */
    protected $BranchContentXREFs = array();
    
    /**
     * Adds all members of the family as cutoff point.
     * @param string $cutoff_point The family XREF.
     */
    protected function addFamilyCutoffPoint($cutoff_point){
        $tree_id = $this->Tree->getTreeId();
        $member_rows = Database::prepare("SELECT DISTINCT l_to FROM ##link WHERE l_from = :family AND l_file = :tree_id")->execute(["tree_id"=>$tree_id,"family"=>$cutoff_point])->fetchAll();

        foreach($member_rows as $row)
        {
            if($row->l_to != $this->PivotIndi->getXref())//we don't add the pivot point as cutoff
            {

                    $this->CutoffPoints[] = $row->l_to;
            }
        }
    }
    /**
     * Preprocesses cutoff points. Family cutoff points will be resolved to individuals.
     */
    protected function preprocessCutoffPoints()
    {
        
        $this->CutoffPoints = [];

        foreach($this->InputCutoffPoints as $cutoff_point)
        {
            //if individual, we add the indi xref
            if(strpos($cutoff_point,"I")!==FALSE)
            {
                $this->CutoffPoints[] = $cutoff_point;
            }
            //if family: add family members
            else if(strpos($cutoff_point,"F") !== FALSE)
            {
                $this->addFamilyCutoffPoint($cutoff_point);
            }
        }
    }
    
    /**
     * Gets the immediate relatives of an individual.
     * @param Individual $indi The individual.
     * @return string[] A list of relatives (individual XREFs).
     */
    protected function getImmediateRelatives($indi)
    {

        $related_persons = [];

        $tree_id = $this->Tree->getTreeId();


        $families = Database::prepare("SELECT DISTINCT l_to FROM `##link` WHERE l_from = :my_xref AND l_file = :tree_id AND (l_type='FAMC' OR l_type='FAMS')")->execute(["my_xref" => $indi->getXref(), "tree_id" => $tree_id])->fetchAll();

        foreach($families as $family_row)
        {
            $relatives_for_current_fam = Database::prepare("SELECT DISTINCT l_from FROM `##link` WHERE l_to = :family_xref AND l_file = :tree_id")->execute(["family_xref" => $family_row->l_to, "tree_id" => $tree_id])->fetchAll();

            foreach($relatives_for_current_fam as $relative)
            {
                $related_persons[] = $relative->l_from;
            }
        }

        return array_unique($related_persons);
    }
    
    /**
     * Gets the XREFs for all Gedcom records linked to the specified individual.
     * @param Individual $indi The individual. 
     * @return string[] Array containg the XREFs. Returns empty array, if no families found. 
     */
    protected function getRelatedRecordXrefs($indi)
    {
        $record_xrefs = array();

        $record_rows = Database::prepare("SELECT DISTINCT l_to FROM ##link WHERE l_from = :indi AND l_file = :tree")->execute(["indi"=>$indi->getXref(), "tree" => $this->Tree->getTreeId()])->fetchAll();
        
        foreach($record_rows as $row)
        {
            $record_obj = \Fisharebest\Webtrees\GedcomRecord::getInstance($row->l_to, $this->Tree);
            if($record_obj->canShow()) {
                $record_xrefs[] = $row->l_to;
            }
        }
        
        return $record_xrefs;
    }
    
    
    protected function addNULLIndiToBranch($indi_xref)
    {
        $this->NullIndies[] = $indi_xref;
        
        if(!in_array($indi_xref, $this->ProcessedIndies))
        {
            $this->ProcessedIndies[] = $indi_xref;
        }
        
        if(!in_array($indi_xref,$this->BranchContentXREFs))
        {
            $this->BranchContentXREFs[]= $indi_xref;
        }
    }
    
    protected function addIndiToBranch($indi)
    {
        if(!$indi->canShow())
            return false;
        
        $this->BranchIndies[$indi->getXref()] = $indi;
        
        if(!in_array($indi->getXref(), $this->ProcessedIndies))
        {
            $this->ProcessedIndies[] = $indi->getXref();
        }
        
        $this->BranchContentXREFs[] = $indi->getXref();
        $this->BranchContentXREFs = array_unique(array_merge($this->BranchContentXREFs, $this->getRelatedRecordXrefs($indi)));
        
        return true;
    }
    
    protected function generateBranchStep($pivot_indi)
    {

        //pivot indi is always in the branch
        
        if(!$this->addIndiToBranch($pivot_indi))
            return;

        if(in_array($pivot_indi->getXref(),$this->CutoffPoints))
        {
            return;
        }

        $immediate_relatives = $this->getImmediateRelatives($pivot_indi);

        foreach($immediate_relatives as $irel_xref)
        {

            $irel = Individual::getInstance($irel_xref, $this->Tree);
            
            //null indi
            if(!$irel)
            {
               $this->addNULLIndiToBranch($irel_xref);
               continue;
            }
            
            if(!$irel->canShow())
                continue;
            
            if(in_array($irel_xref, $this->CutoffPoints))
            {
               $this->addIndiToBranch($irel);
            }
            else if(!in_array($irel_xref, $this->ProcessedIndies))
            {
                $this->addIndiToBranch($irel);
                $this->generateBranchStep($irel);
            }


        }

    }
    
    /**
     * Constructor.
     * @param Individual $pivot_indi
     * @param array $cutoff_points
     */
    public function __construct($pivot_indi, $cutoff_points){
        global $WT_TREE;
        $this->Tree = $WT_TREE;
        $this->PivotIndi = is_a($pivot_indi,"Fisharebest\Webtrees\Individual") ? $pivot_indi : Individual::getInstance($pivot_indi, $this->Tree);
        
        $this->InputCutoffPoints = $cutoff_points;
        
        $this->preprocessCutoffPoints();
    }
    
    /**
     * 
     * @return string[] Generates the array of XREF in the branch.
     */
    public function generateBranch()
    {
        $this->BranchIndies = array();
        $this->NullIndies = array();
        $this->ProcessedIndies = array();
        $this->BranchContentXREFs = array();
        $this->generateBranchStep($this->PivotIndi);
        return $this->BranchContentXREFs;
    }
}
