
/**
 * Translator object.
 * @type I18N
 */
var translator = null;


/**
 * Creates a popup window using POST method. Base "Mercenary"'s solution at http://stackoverflow.com/questions/3951768/window-open-and-pass-parameters-by-post-method. 
 * @param {string} script The php script file.
 * @param {array} post_data The post data.
 * @param {array} window_specs Window specification.
 * @returns {undefined}
 */
function postWindow(script,post_data,window_specs)
{
    var window_name = "PostWindow"
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", script);

    form.setAttribute("target", window_name);

    for(var key in post_data){
    var hiddenField = document.createElement("input"); 
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", key);
    hiddenField.setAttribute("value", post_data[key]);
    form.appendChild(hiddenField);
    
    }
    document.body.appendChild(form);
    window.open('', window_name,window_specs);

    form.submit();
}


function branchExport_OnAddCutoffPointClick()
{
    
    var num_cutoff_points = jQuery("#branchexp .branch-cutoff-row").length;
    var last_cutoff_point = jQuery(jQuery("#branchexp .branch-cutoff-row")[num_cutoff_points-1]);
    
    var new_cutoff_point = last_cutoff_point.clone();
    new_cutoff_point.insertAfter(last_cutoff_point);
    new_cutoff_point.find("input").val("").on("change autocompleteclose", branchExport_OnCutoffOrPivotChanged);
    
    autocomplete(new_cutoff_point.find("input"));
    
    branchExport_UpdateCutoffPointLabelsAndIDs();
    
}
function branchExport_UpdateCutoffPointLabelsAndIDs()
{
    var index = 1;
    
    jQuery.each(jQuery("#branchexp .branch-cutoff-row"),function (){
        var cutoff_point_id = "branch_cutoff_"+index;
        var cutoff_point_label = sprintf(translator.translate("Cutoff point #%d:"),index);
        jQuery(this).find("input").attr("id",cutoff_point_id);
        jQuery(this).find("label").attr("for",cutoff_point_id).text(cutoff_point_label);
        var find_indi_link = jQuery(this).find(".icon-button_indi");
        find_indi_link.attr("onclick",find_indi_link.attr("onclick").replace(/branch_cutoff_[0-9]+/i,cutoff_point_id));
        
        var find_fam_link = jQuery(this).find(".icon-button_family");
        find_fam_link.attr("onclick",find_fam_link.attr("onclick").replace(/branch_cutoff_[0-9]+/i,cutoff_point_id));
        index++;
    });
}
function branchExport_RemoveCutoffPoint(event)
{
    event.preventDefault();
    
    if(jQuery("#branchexp .branch-cutoff-row").length === 1)
    {
        //alert("The last cutoff point input row cannot be removed. Leave it blank, if you do not want to use any cutoff point.");
        
        jQuery(event.target).parents("tr").find("input").val("");
    }
    
    else 
    {
        jQuery(event.target).parents("tr").remove();
        branchExport_UpdateCutoffPointLabelsAndIDs();
        branchExport_OnCutoffOrPivotChanged();
    }
}

function branchExport_CollectCutoffPoints()
{
    var cutoff_points = [];
    //collecting branch cutoff points
    jQuery.each(jQuery("input[name='branch_cutoff[]']"),function(){
        var cutoff = jQuery(this).val();
        if(cutoff.length > 0)
        {
            cutoff_points.push(jQuery(this).val());
        }
    });
    
    return cutoff_points;
}

function branchExport_ShowPreview()
{
    var data = {
        "pivot" :  jQuery("#branch_pivot").val(),
        "cutoff"   :  branchExport_CollectCutoffPoints(),
        "clippingCartContent" : []
    };
    
    //collecting current clipping cart content
    jQuery.each(jQuery("#mycart tbody tr"),function(){
        data["clippingCartContent"].push(jQuery(this).data("id"));
    });
    
    postWindow("branchexportpreview.php",data,find_window_specs);
}

function branchExport_OnPresetSelected(event)
{
    var id = $(event.target).val(), name = $(event.target).find(":selected").text();
    
    if(id === "NULL") 
        return;
    
    var pivot = $(event.target).find(":selected").data("pivot"), cutoff = $(event.target).find(":selected").data("cutoff");
    
    jQuery("#branch_pivot").val(pivot);
    
    jQuery("#branch_preset_name").val(name);
    
    var cutoff_array = cutoff.split(",");
    
    jQuery(".branch-cutoff-row").not(":first").remove();
    
    for(var i= 0; i<cutoff_array.length ; i++)
    {
        var cutoff_element_id = "branch_cutoff_"+(i+1);
        var cutoff_element = jQuery("#"+cutoff_element_id);

        while(cutoff_element.length === 0)
        {
            branchExport_OnAddCutoffPointClick();
            branchExport_UpdateCutoffPointLabelsAndIDs();
            cutoff_element = jQuery("#"+cutoff_element_id);
        }
        
        cutoff_element.val(cutoff_array[i]);
    }
    
}

function branchExport_UpdatePresetList(presets, selected)
{
    jQuery("#saved_branch_presets").find("option").not(":first").remove();
    if(presets) for(var preset_idx in presets)
    {
        var preset = presets[preset_idx];

        jQuery("#saved_branch_presets").append("<option value='"+preset["name"]+"' data-pivot='"+preset["pivot"]+"' data-cutoff='"+preset["cutoff"]+"' "+(preset["name"] === selected ? "selected" : "")+">"+preset["name"]+"</option>");
    }
}

function branchExport_SaveDelete_AjaxSuccessFunction(responsedata)
{
    if("error" in responsedata)
    {
        alert(responsedata["error"]["message"]);
    }
    else
    {
            branchExport_UpdatePresetList(responsedata["presets"],responsedata["selected"]);
    }
}

function branchExport_ToggleDisable(true_disabled_false_enabled)
{
    var elements_to_disable = jQuery("#branchexp").find("input,select,button,textarea");
    elements_to_disable.prop("disabled",true_disabled_false_enabled);
}

function branchExport_OnDeletePreset(moduledir)
{
    
    
    var data = {
        "name" : $("#branch_preset_name").val(),
        "selected" : $("#saved_branch_presets").val()
    };
    if(!confirm(sprintf(translator.translate("Are you sure you want to delete this preset: %s?"),data["name"])))
    {
        return;
    }
    
    branchExport_ToggleDisable(true);
    
    jQuery.post(moduledir+"/deletebranchexportpreset.php", data , branchExport_SaveDelete_AjaxSuccessFunction ,"json").always(function(){
        branchExport_ToggleDisable(false);
        
    });
}
function branchExport_OnSavePreset(moduledir, rename)
{
    var data = {
        "name" : $("#branch_preset_name").val(),
        "pivot" : $("#branch_pivot").val()
    };
    
    if(rename){
        data["rename"] = 1;
        data["preset_to_rename"] = $("#saved_branch_presets").val();
        
        if(data["preset_to_rename"] === "NULL")
        {
            alert(translator.translate("Please select a preset from the dropdown."));
            return;
        }
        else if(data["preset_to_rename"] === data["name"])
        {
            alert(sprintf(translator.translate("%s is the current name of the selected preset. Please choose a different name, or click 'Save' instead.") ,data["preset_to_rename"]));
            return;
        }
       
    }
    
    if(data["name"].length === 0)
    {
        alert(translator.translate("A preset name is required."));
        return;
    }
    
    var cutoff_array = [];
    
    var current_cutoff_index = 1;
    
    do{
        var current_cutoff_element = jQuery("#branch_cutoff_"+current_cutoff_index);
        if(current_cutoff_element.length > 0)
        {
            cutoff_array.push(current_cutoff_element.val());
        }
        current_cutoff_index++;
    }
    while(current_cutoff_element.length > 0 );
    
    data["cutoff"] = cutoff_array.join(",");
    
    branchExport_ToggleDisable(true);
    
    jQuery.post(moduledir+"/savebranchexportpreset.php", data , branchExport_SaveDelete_AjaxSuccessFunction ,"json").always(function(){
        branchExport_ToggleDisable(false);
    });
}

function branchExport_OnCutoffOrPivotChanged()
{
    jQuery("#saved_branch_presets").val("NULL");
}

jQuery(function(){
    
    jQuery("head").append('<link rel="stylesheet" href="modules_v3/branch_export/assets/branch_export.css" type="text/css" />');
    
    translator = new I18N();
    translator.load([
        "Cutoff point #%d:",
        "%s is the current name of the selected preset. Please choose a different name, or click 'Save' instead.",
        "A preset name is required.",
        "Please select a preset from the dropdown.",
        "Are you sure you want to delete this preset: %s?"]);
    
    jQuery("#saved_branch_presets").trigger("change");
    jQuery("#branch_pivot, .branch-cutoff-row input").on("change autocompleteclose",branchExport_OnCutoffOrPivotChanged);
    
    jQuery("#branchexport_help").accordion({collapsible: true, active: false});
    
    
});