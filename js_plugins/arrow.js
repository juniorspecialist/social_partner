/**
 * Created with JetBrains PhpStorm.
 * User: artem
 * Date: 10.06.13
 * Time: 10:45
 * To change this template use File | Settings | File Templates.
 */
jQuery(document).ready(function() {
    if (jQuery(window).scrollTop()>='250'){
        jQuery('#ToTop').fadeIn('slow');
    }
    jQuery(window).scroll(function(){
        if (jQuery(window).scrollTop()<='250'){
            jQuery('#ToTop').fadeOut('slow');
        }else{
            jQuery('#ToTop').fadeIn('slow')
        }
    });

    if (jQuery(window).scrollTop()<=jQuery(document).height()-'999') jQuery('#OnBottom').fadeIn('slow');
    jQuery(window).scroll(function(){
        if (jQuery(window).scrollTop()>=jQuery(document).height()-'999') jQuery('#OnBottom').fadeOut('slow');
        else jQuery('#OnBottom').fadeIn('slow');
    });

    jQuery('#ToTop').click(function(){jQuery('html,body').animate({scrollTop:0},'slow')});
    jQuery('#OnBottom').click(function(){
        jQuery('html,body').animate({scrollTop:jQuery(document).height()},'slow');
        return
    });
});