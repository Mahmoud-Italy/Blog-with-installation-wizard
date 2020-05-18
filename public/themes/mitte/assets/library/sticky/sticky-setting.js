/*
 * Settings of the sticky menu
 */

jQuery(document).ready(function(){
   var wpAdminBar = jQuery('#wpadminbar');
   if (wpAdminBar.length) {
      jQuery("#nv-menu-wrap").sticky({topSpacing:wpAdminBar.height()});
   } else {
      jQuery("#nv-menu-wrap").sticky({topSpacing:0});
   }
});