jQuery(document).ready(function() {
    
    jQuery('.button_save_addthis_options').click(function() {
        var data = {
            action: "app_addthis_save_settings",
            values: jQuery("[name=addthis_form]").serialize()
        };
         jQuery.post(ajaxurl, data, function(response) {
                show_info_popup(response);
            });
    });
    
    jQuery('.addthis_buttons_type .option_checkbox').click(function(){
        jQuery('.addthis_buttons_type .option_checkbox').each(function(){
            jQuery(this).prev("input[type=hidden]").val(0);
            jQuery(this).val(0);           
            jQuery(this).removeAttr('checked');
        });
        jQuery(this).prev("input[type=hidden]").val(1);
        jQuery(this).val(1);
        jQuery(this).prop('checked',true);
    });
    
});