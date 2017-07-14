
var translator = null;

function OnTreeIdChange(event)
{
    var tree_id = event.target.value;
    if(tree_id === "NULL")
    {
        jQuery("#presets option").show();
    }
    else
    {
        jQuery("#presets option").hide();
        jQuery("#presets option[data-tree='"+tree_id+"']").show();
        jQuery("#presets option:not(:visible)").prop("selected",false);
    }
}

function OnConfigCustomActionSubmit(event)
{
    var config_action = event.target.id;
    
    jQuery("[name='config_action']").val(config_action);
    
    
}

function OnPresetSelected(event)
{
    var selected = jQuery(event.target).find("option:selected").first();
    
    if(selected.length > 0)
    {
        var tree_id = selected.data("tree");

        var tree = jQuery("#tree_id option[value='"+tree_id+"']");
        
        if(tree.length === 0)
        {
            jQuery("#selected_preset_tree").html(tree_id+" <span class=\"preset-orphaned\" title=\""+translator.translate("The tree to which this preset belonged was deleted")+".\">("+translator.translate("Orphaned")+")</strong>");
        }
        else
        {
            jQuery("#selected_preset_tree").text(tree.text());
        }
        jQuery("#selected_preset_name").text(selected.text());
        jQuery("#selected_preset_pivot").text(selected.data("pivot"));
        jQuery("#selected_preset_cutoff").text(selected.data("cutoff"));
    }
}

function OnPresetDelete(event)
{
    if(jQuery("#presets option:selected").length === 0)
    {
        alert(translator.translate("No preset selected."));
        event.stopPropagation();
        event.preventDefault();
    }
    else if(!confirm(translator.translate("Delete selected presets?")))
    {
        event.stopPropagation();
        event.preventDefault();
    }
}

function OnSelectOrphaned()
{
    var orphaned_presets = jQuery("#presets option.preset-orphaned");
    var num_orphaned = orphaned_presets.length;
    if(num_orphaned === 0)
    {
        alert(translator.translate("No orphaned presets found."));
    }
    jQuery("#presets option").prop("selected", false);
    orphaned_presets.prop("selected", true);
}

function OnUninstallClick(event)
{
    if(!confirm(translator.translate("Are you sure you want to uninstall this module?\n\n(NOTE: all presets and module-related settings will be permanently deleted and the module will be disabled. Module files will not be removed. They must be manually deleted. The module can be reactivated on the 'Control Panel / Modules / Module administration' page.)")))
    {
        event.preventDefault();
        event.stopPropagation();
    }
}

jQuery(function(){
    translator = new I18N();
    translator.load([
        "Orphaned",
        "The tree to which this preset belonged was deleted.",
        "Are you sure you want to uninstall this module?\n\n(NOTE: all presets and module-related settings will be permanently deleted and the module will be disabled. Module files will not be removed. They must be manually deleted. The module can be reactivated on the 'Control Panel / Modules / Module administration' page.)",
        "Delete selected presets?",
        "No preset selected.",
        "No orphaned presets found."
        ]);
    
    jQuery("#tree_id").change(OnTreeIdChange).trigger("change");
    jQuery("#delete_presets,#copy_presets").click(OnConfigCustomActionSubmit);
    jQuery("#presets").change(OnPresetSelected);
    jQuery("#delete_presets").click(OnPresetDelete);
    jQuery("#select_orphaned").click(OnSelectOrphaned);
    jQuery("#uninstall").click(OnUninstallClick);
});
