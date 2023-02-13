/*-----------------------------
* Build Your Plugin JS / jQuery
-----------------------------*/
/*
Jquery Ready!
*/

jQuery(document).ready(function($){
    "use strict";
    /*
    Add basic front-end page scripts here
    */
    /*
    *   Simple jQuery Click
    *
    *   Add id="mySpecialButton" to any element and when 
    *   clicked the same element will get the class "active".
    *
    */
    $('#mySpecialButton').click(function(){
        $(this).addClass('active');    
    });
    // End basic front-end scripts here
    // payment box code
    jQuery('.aspire-payment link').attr("href", "#");
    jQuery('.aspire-payment .row .psp_div[data-id="54"]').addClass('psp_box1');
    jQuery('.aspire-payment .row .psp_div[data-id="55"]').addClass('psp_box2');
    jQuery('.aspire-payment .row .psp_div[data-id="56"]').addClass('psp_box3');
    // add pay button 
});
