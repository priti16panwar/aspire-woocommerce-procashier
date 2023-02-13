<?php 
/*
*
*	***** Aspire Woocommerce Procashier  *****
*
*	Core Functions
*	
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if
/*
*
* Custom Front End Ajax Scripts / Loads In WP Footer
*
*/


// Display Billing birthdate field to checkout and My account addresses
add_filter( 'woocommerce_billing_fields', 'display_birthdate_billing_field', 20, 1 );
function display_birthdate_billing_field($billing_fields) {

    $billing_fields['billing_birthdate'] = array(
        'type'        => 'date',
        'label'       => __('Birthdate'),
        'class'       => array('form-row-wide'),
        'priority'    => 25,
        'required'    => true,
        'clear'       => true,
    );
    return $billing_fields;
}

// Save Billing birthdate field value as user meta data
add_action( 'woocommerce_checkout_update_customer', 'save_account_billing_birthdate_field', 10, 2 );
function save_account_billing_birthdate_field( $customer, $data ){
    if ( isset($_POST['billing_birthdate']) && ! empty($_POST['billing_birthdate']) ) {
         $customer->update_meta_data( 'billing_birthdate', sanitize_text_field($_POST['billing_birthdate']) );
    }
}

// Admin orders Billing birthdate editable field and display
add_filter('woocommerce_admin_billing_fields', 'admin_order_billing_birthdate_editable_field');
function admin_order_billing_birthdate_editable_field( $fields ) {
    $fields['birthdate'] = array( 'label' => __('Birthdate', 'woocommerce') );

    return $fields;
}

// WordPress User: Add Billing birthdate editable field
add_filter('woocommerce_customer_meta_fields', 'wordpress_user_account_billing_birthdate_field');
function wordpress_user_account_billing_birthdate_field( $fields ) {
    $fields['billing']['fields']['billing_birthdate'] = array(
        'label'       => __('Birthdate', 'woocommerce'),
        'description' => __('', 'woocommerce')
    );
    return $fields;
}

/////////////////////////////////////////////////


add_action( 'before_woocommerce_pay', 'bbloomer_order_pay_billing_address' );
 
function bbloomer_order_pay_billing_address() {
    global $wp;
    global $woocommerce;
    $currencyVal=get_woocommerce_currency();
    if ( isset($wp->query_vars['order-pay']) && absint($wp->query_vars['order-pay']) > 0 ) {
        $order_ids = absint($wp->query_vars['order-pay']); // The order ID 
        $order_id = new WC_Order($order_ids);
        $OrderId = $order_id->id;
        $order = new WC_Order($order_id);
        $customer = wc_get_order( $order_id );
        $CurrencyData = $currencyVal?$currencyVal:'USD';
        $Currency_symbol = get_woocommerce_currency_symbol();
        $order_key = $order->order_key;
        $Total_amount = $order->order_total;
        $Amount = round($Total_amount,0,PHP_ROUND_HALF_UP);
        $user_date = $order_id.'_'.date("ymds");
        update_post_meta($order_id,'_post_data',$_POST);
        $First_name   = $customer->get_billing_first_name();
        $Last_name    = $customer->get_billing_last_name();
        $Company      = $customer->get_billing_company();
        $Address_1    = $customer->get_billing_address_1();
        $Address_2    = $customer->get_billing_address_2();
        $City         = $customer->get_billing_city();
        $State        = $customer->get_billing_state();
        $Postcode     = $customer->get_billing_postcode();
        $Country_code = $customer->get_billing_country();
        $Phone        = $customer->get_billing_phone();
        $Email        = $customer->get_billing_email();
        //echo '<pre>',print_r($order) ;
        $countries_obj = new WC_Countries();
        $countries_array = $countries_obj->get_countries();
        $Country_name = $countries_array[$Country_code];
        $Calling_code = WC()->countries->get_country_calling_code( $Country_code );
        $Birthdate = get_post_meta( $OrderId, '_billing_birthdate', true );

        ?>
        <script>
            jQuery(document).ready(function($){
                jQuery('#Aspire_Payment').show();
                jQuery('.psp_box1 .img-responsive').attr("src", "<?php echo plugins_url( 'cardpayz-logo.png' , __FILE__ );?>");
                jQuery('.psp_box2 .img-responsive').attr("src", "<?php echo plugins_url( 'cardeta.png' , __FILE__ );?>");
                jQuery('.psp_box3 .img-responsive').attr("src", "<?php echo plugins_url( 'gateway_logo.png' , __FILE__ );?>");
            });
        </script>
        <div class="pay_btn"></div>
        <div class="aspire-payment" id="Aspire_Payment" style="display:none;">
            <button type="button" class="awp_closes" value="Close"><a href="<?php echo get_site_url();?>/checkout/">Close</a></button>
        <?php
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://dev-procashier.intivion.com/IframeCode/IframeScreen/serverAuth');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $length = 15;    
        $Transation_ID = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'),1,$length);
        $Transation_ID=$Transation_ID.'-orderid'.$OrderId;
        $length_no = 12;    
        $req_01 = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'),1,$length_no);
        $Request_key = $req_01.$order_id;

        $post = "currency=$CurrencyData&firstname=$First_name&lastname=$Last_name&email=$Email&phone_country_code=$Calling_code&phone=$Phone&address=$Address_1&city=$City&zipcode=$Postcode&birth_date=$Birthdate&country=$Country_code&country_name=$Country_name&id=learningpotential&TPAcc=1820748416&Transaction_Id=$Transation_ID&Amount=$Amount&request_key=$order_key";

        echo file_get_contents('https://dev-procashier.intivion.com/IframeCode/IframeScreen/?'.$post);

       ?>
        </div> 
        <style>ul.order_details {display: none;}button.awp_closes {border: 2px solid #fff !important;margin-bottom: 5px !important;color: #fff !important;padding: 5px 15px !important;font-size: 15px !important; background: #000 !IMPORTANT;}
        </style> 
    <?php
       
    }
}