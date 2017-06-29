/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Internationalization. 
 * @returns {I18N}
 */
function I18N()
{
    /**
     * The translated strings.
     */
    this.strings = {};
    
    /**
     * The URL of the transltor PHP file.
     */
    this.translatorUrl = "module.php?mod=branch_export&mod_action=translate";
    
    /**
     * Loads the translation of specific strings from the server.
     * @param {array} An array of strings. If NULL = return all strings.
     * @returns {undefined}
     */
    this.load = function(str){
        var me = this;
        jQuery.post(this.translatorUrl, {"strings":str}, function(data){
            me.strings = data;
        },"json");
    }
    
    /**
     * Returns the translation of a specific strings.
     * @param {string} The string to translate.
     * @returns {string} The translated string. If no translation is available, the original string is returned.
     */
    this.translate = function (str){
        
        //check if we have a translation
        if(this.strings[str])
            return this.strings[str];
       
        
        return str;
    }
}
