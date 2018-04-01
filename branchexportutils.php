<?php
namespace BlasiusSecundus\WebtreesModules\BranchExport;

final class BranchExportUtils {
    const PIVOT_PATTERN = "/^I[0-9]+$/";
    const CUTOFF_PATTERN = "/^(F|I)[0-9]+$/";
    
    public static function validatePivot($pivot){
        
        if (!is_string($pivot)) {
            return false;
        }

        return preg_match(BranchExportUtils::PIVOT_PATTERN, $pivot);
    }
    
    public static function validateCutoff($cutoff){
        
        if (!is_string($cutoff)) {
            return false;
        }

        return preg_match(BranchExportUtils::CUTOFF_PATTERN, $cutoff);
    }
    
    public static function validateCutoffArray($cutoffArray){
        
        if (!is_array($cutoffArray)) {
            return false;
        }

        foreach($cutoffArray as $cutoff){
            if (!BranchExportUtils::validateCutoff($cutoff)) {
                return false;
            }
        }
        
        return true;
    }
}
