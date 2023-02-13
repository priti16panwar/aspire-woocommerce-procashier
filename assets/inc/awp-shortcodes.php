<?php 
/*
*
*	***** Aspire Woocommerce Procashier  *****
*
*	Shortcodes
*	
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if
/*
*
*  Build The Custom Plugin Form
*
*  Display Anywhere Using Shortcode: [awp_custom_plugin_form]
*
*/


function getProcashierSucessData()
{
        echo the_content(); // add sucess message in success page and get content from there
        
         

}
add_shortcode('get_sucess_data','getProcashierSucessData');


function getProcashierNotificationData(){  
	 $transation_id= $_POST['Transac_id'];
     $PSP= $_POST['Transac_id'];
     $status=$_POST['status'];
	 
	 $transOrder = substr($transation_id, strpos($transation_id, "-orderid") + 1); 
	 echo "order id: ".$order_id= str_replace("orderid","",$transOrder); 
	 
	 if(!empty($status)){
		 echo '<div>
			<ul style="list-style-type: none;">
				<li style="display: flex;padding-bottom: 12px;
"><p style="margin: 0px; margin-right: 5px;">Your Transation ID: </p><strong style="padding-left: 10px;">'.$transation_id.'</strong></li>
				<li style="display: flex;padding-bottom: 12px;
"><p style="margin: 0px; margin-right: 5px;">Your PSP ID: </p><strong style="padding-left: 10px;">'.$PSP.'</strong></li>
				<li style="display: flex;padding-bottom: 12px;
"><p style="margin: 0px; margin-right: 5px;">Your Payment Status: </p><strong style="padding-left: 10px;">'.$status.'</strong></li>
			</ul>
          </div>';
	 }

 	  $response=$_POST['response_json'];
 	  $string=stripslashes($response);
	   $result = array_values(json_decode($string, true));
	    if($status== 'SUCCESS'){
			foreach($result as $row) {
				$transaction_id= $row['payment']['transaction_id'];
				$payment_url= $row['payment']['payment_url'];
				$order = new WC_Order( $order_id );
				update_post_meta( $order->get_id(), '_transaction_id', $transaction_id, TRUE );
				update_post_meta( $order->get_id(), '_payment_url', $payment_url, TRUE );

			}
		}

	
        switch ($status) {
          case "SUCCESS": //change this
            $woocomerceStatus= "wc-completed";
            $statusVal='Completed';
            break;
          case "FAILED": //change this
            $woocomerceStatus= "wc-pending";
            $statusVal='Pending';
            break;
          case "PENDING": //change this
            $woocomerceStatus= "wc-pending";
            $statusVal='Pending';
            break;
          default:
           $woocomerceStatus= "wc-pending";
           $statusVal='Pending';

           return $woocomerceStatus;
        }

   $orderDetail = new WC_Order( $order_id );
   $orderDetail->update_status($woocomerceStatus, $statusVal, TRUE);


}
add_shortcode('get_procashier_notification_data','getProcashierNotificationData');
