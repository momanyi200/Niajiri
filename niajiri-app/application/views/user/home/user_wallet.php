<?php
$get_details = $this->db->where('id', $this->session->userdata('id'))->get('users')->row_array();

?>
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2>Wallet</h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wallet</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container">
        <div class="row">
            <?php
            if (!empty($_GET['tbs'])) {
                $val = $_GET['tbs'];
            } else {
                $val = 1;
            }
            ?>
            <input type="hidden" name="tab_ctrl" id="tab_ctrl" value="<?php echo  $val; ?>">
            <?php $this->load->view('user/home/user_sidemenu'); ?>

            <div class="col-xl-9 col-md-8">

                <div class="row">
                    <?php
                    $total_cr = 0;
                    $total_dr = 0;
					$user_currency = get_user_currency();
                    $user_currency_code = $user_currency['user_currency_code'];
                    if (!empty($wallet_history)) {
                        foreach ($wallet_history as $key => $value) {
                            
                            if (!empty($value['credit_wallet'])) {
                                $color = 'success';
                                $message = 'Credit';
                            } else {
                                $color = 'danger';
                                $message = 'Debit';
                            }
                            if (!empty($value["fee_amt"]) && $value["fee_amt"] > 1) {
                                $txt_amt = number_format($value["fee_amt"] / 100, 2);
                            } else {
                                $txt_amt = 0;
                            }
                            $total_cr += get_gigs_currency($value['credit_wallet'], $value['currency_code'], $user_currency_code);
                            $total_dr += abs(get_gigs_currency($value['debit_wallet'], $value['currency_code'], $user_currency_code));
                        }
                    }
                    ?>

                    <?php
                    $user_currency_code = '';
                    $userId = $this->session->userdata('id');
                    If (!empty($userId)) {
                        
                        $service_amount1 = $wallet['wallet_amt'];
                        $service_amount2 = $total_cr;
                        $service_amount3 = $total_dr;
                        $get_currency = get_currency();
                        $user_currency = get_user_currency();
                        $user_currency_code = $user_currency['user_currency_code'];
                        $service_amount1 = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], $user_currency_code);
                        
                    } else {
                        $user_currency_code = settings('currency');
                        $service_amount1 = $wallet['wallet_amt'];
                        $service_amount2 = $total_cr;
                        $service_amount3 = $total_dr;
                    }
                    ?>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_wallet'])) ? $user_language[$user_selected]['lg_wallet'] : $default_language['en']['lg_wallet']; ?></h4>

                                <div class="wallet-details">
                                    <span><?php echo (!empty($user_language[$user_selected]['lg_wallet_balance'])) ? $user_language[$user_selected]['lg_wallet_balance'] : $default_language['en']['lg_wallet_balance']; ?></span>
                                    <h3><?php echo currency_conversion($user_currency_code) . $wallet['wallet_amt']; ?></h3>


                                    <div class="d-flex justify-content-between my-4">
                                        <div>
                                            <p class="mb-1"><?php echo (!empty($user_language[$user_selected]['lg_total_credit'])) ? $user_language[$user_selected]['lg_total_credit'] : $default_language['en']['lg_total_credit']; ?></p>
                                            <h4><?php echo currency_conversion($user_currency_code) . number_format($service_amount2, 2); ?></h4>
                                        </div>
                                        <div>
                                            <p class="mb-1"><?php echo (!empty($user_language[$user_selected]['lg_total_debit'])) ? $user_language[$user_selected]['lg_total_debit'] : $default_language['en']['lg_total_debit']; ?></p>
                                            <h4><?php echo currency_conversion($user_currency_code) . number_format($service_amount3, 2); ?></h4>
                                        </div>
                                    </div>

                                    <div class="wallet-progress-chart">
                                         <div class="d-flex justify-content-between">
											<?php
                                            if (!empty($wallet['total_credit'])) {
                                                $wallet['total_credit'] = $total_cr;
												$wallet['total_debit'] = $total_dr;
                                            } else {
                                                $wallet['total_credit'] = 0;
												$wallet['total_debit'] = $total_dr;
                                            }
                                            ?>
                                            <span><?php echo  $wallet['currency'] . '' . abs($wallet['total_debit']); ?></span>
                                            <span><?php echo  $wallet['currency'] . '' . number_format($wallet['total_credit'], 2); ?></span>
                                        </div>

                                        <?php
                                        $total_per = 0;
                                        if (!empty($wallet['total_debit']) && !empty($wallet['total_credit'])) {
                                            $total_per = ($wallet['total_debit'] / $wallet['total_credit']) * 100;
                                        }
                                        ?>

                                        <div class="progress mt-1">
                                            <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="41" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo  round($total_per); ?>%">
                                                <?php echo  number_format(abs($total_per), 2); ?>%
                                            </div>
                                        </div>                                     
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_add_wallet'])) ? $user_language[$user_selected]['lg_add_wallet'] : $default_language['en']['lg_add_wallet']; ?></h4>
                                <form action="<?php echo  base_url() ?>paytab-payment" method="get" id="paytab_payment">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text"><?php echo  currency_conversion($user_currency_code); ?></label>
                                            </div>
											 <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                            <input type="text"  maxlength="10" class="form-control isNumber" name="wallet_amt" id="wallet_amt" placeholder="00.00">
                                            <input type="hidden"  id="currency_val" name="currency_val"  value="<?php echo  $user_currency_code; ?>">
                                            
                                        </div>
                                    </div>
                                </form>
                                <div class="text-center mb-3">
                                    <h5 class="mb-3"><?php echo (!empty($user_language[$user_selected]['lg_or'])) ? $user_language[$user_selected]['lg_or'] : $default_language['en']['lg_or']; ?></h5>


                                    <ul class="list-inline mb-0">
                                        <li class="line-inline-item mb-0 d-inline-block">
                                            <a href="javascript:;" data-amount="50" class="updatebtn add_wallet_value"><?php echo  currency_conversion($user_currency_code); ?>50</a>
                                        </li>
                                        <li class="line-inline-item mb-0 d-inline-block">
                                            <a href="javascript:;" data-amount="100" class="updatebtn add_wallet_value"><?php echo  currency_conversion($user_currency_code); ?>100</a>
                                        </li>
                                        <li class="line-inline-item mb-0 d-inline-block">
                                            <a href="javascript:;" data-amount="150" class="updatebtn add_wallet_value"><?php echo  currency_conversion($user_currency_code); ?>150</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="container">
                                <div class="row">
									<?php if(!empty($paypal_option_status)) { ?>
                                    <div class="col-4">
                                        <input class="form-check-input" type="radio" name="payment_type" id="paypal" value="paypal">
                                        <img src="<?php echo  base_url() . "assets/img/paypal.png"; ?>">
                                    </div>
									<?php } if(!empty($stripe_option_status)) { ?>
                                    <div class="col-4">
                                        <input class="form-check-input" type="radio" name="payment_type" id="stripe"  value="stripe">
                                        <img src="<?php echo  base_url() . "assets/img/stripe.png"; ?>">
                                    </div>
									<?php } if(!empty($razor_option_status)) { ?>
									<div class="col-4 d-flex">
                                        <input class="form-check-input" type="radio" name="payment_type" id="razorpay"  value="razorpay">
                                        <img src="<?php echo  base_url() . "assets/img/razorpay.png"; ?>">
                                    </div>
									<?php } ?>
                                </div>
                                    <br>
                                </div>

                                <input type="hidden" id="paypal_gateway" value="<?php echo  $paypal_gateway; ?>">
                                <input type="hidden" id="braintree_key" value="<?php echo  $braintree_key; ?>">
								
								<input type="hidden" id="razorpay_apikey" value="<?php echo  $razorpay_apikey; ?>">

                                <input type="hidden" id="username" value="<?php echo  $user_details['name']; ?>">
                                <input type="hidden" id="mobileno" value="<?php echo  $user_details['mobileno']; ?>">


                                <input type="hidden" id="state" value="<?php echo  (!empty($state)) ? $state : "IL"; ?>">
                                <input type="hidden" id="country" value="<?php echo  (!empty($country)) ? $country : "US"; ?>">
                                <input type="hidden" id="pincode" value="<?php echo  (!empty($user_details['pincode'])) ? $user_details['pincode'] : "60652"; ?>">
                                <input type="hidden" id="address" value="<?php echo  (!empty($user_details['address'])) ? $user_details['address'] : "1234 Main St."; ?>"><input type="hidden" id="city" value="<?php echo  (!empty($city)) ? $city : "Chicago"; ?>">

                                <a href="javascript:void(0);"id="stripe_wallet" class="btn btn-primary btn-block withdraw-btn"><?php echo (!empty($user_language[$user_selected]['lg_add_to_wallet'])) ? $user_language[$user_selected]['lg_add_to_wallet'] : $default_language['en']['lg_add_to_wallet']; ?></a>
                                <span class="paypal_desc"><?php echo (!empty($user_language[$user_selected]['lg_click_paypal_button_to_pay'])) ? $user_language[$user_selected]['lg_click_paypal_button_to_pay'] : $default_language['en']['lg_click_paypal_button_to_pay']; ?></span>
                                <a id="pays">
                                    <div id="paypal-button"></div>
                                </a>
                            </div>


                        </div>
                    </div>
                </div>

            <form method="POST" action="<?php echo base_url() . "paypal-braintree" ?>" id="myForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                <input type="hidden" name="amount" value="" id="paypal_amount">
                <input type="hidden" name="payload_nonce" value="" id="payload_nonce">  
                <input type="hidden" name="orderID" value="" id="orderID">  
            </form>

            <h4 class="mb-4"><?php echo (!empty($user_language[$user_selected]['lg_wallet_transactions'])) ? $user_language[$user_selected]['lg_wallet_transactions'] : $default_language['en']['lg_wallet_transactions']; ?></h4>
            <div class="card transaction-table mb-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if (!empty($wallet_history)) { ?>
                            <table id="order-summary" class="table table-center mb-0">
                            <?php } else { ?>
                                <table class="table table-center mb-0">
                                <?php } ?>
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo (!empty($user_language[$user_selected]['lg_Date'])) ? $user_language[$user_selected]['lg_Date'] : $default_language['en']['lg_Date']; ?></th>
                                        <th><?php echo (!empty($user_language[$user_selected]['lg_wallet'])) ? $user_language[$user_selected]['lg_wallet'] : $default_language['en']['lg_wallet']; ?></th>
                                        <th><?php echo (!empty($user_language[$user_selected]['lg_credit'])) ? $user_language[$user_selected]['lg_credit'] : $default_language['en']['lg_credit']; ?></th>
                                        <th><?php echo (!empty($user_language[$user_selected]['lg_debit'])) ? $user_language[$user_selected]['lg_debit'] : $default_language['en']['lg_debit']; ?></th>
                                        <th><?php echo (!empty($user_language[$user_selected]['lg_txt_amt'])) ? $user_language[$user_selected]['lg_txt_amt'] : $default_language['en']['lg_txt_amt']; ?></th>
                                        <th><?php echo (!empty($user_language[$user_selected]['lg_available'])) ? $user_language[$user_selected]['lg_available'] : $default_language['en']['lg_available']; ?></th>
                                        <th><?php echo (!empty($user_language[$user_selected]['lg_reason'])) ? $user_language[$user_selected]['lg_reason'] : $default_language['en']['lg_reason']; ?></th>
                                            <th><?php echo (!empty($user_language[$user_selected]['lg_Status'])) ? $user_language[$user_selected]['lg_Status'] : $default_language['en']['lg_Status']; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_cr = 0;
                                    $total_dr = 0;
                                    if (!empty($wallet_history)) {
                                        foreach ($wallet_history as $key => $value) {

                                            if (!empty($value['credit_wallet'])) {
                                                $color = 'success';
                                                $message = 'Credit';
                                            } else {
                                                $color = 'danger';
                                                $message = 'Debit';
                                            }
                                            if (!empty($value["fee_amt"]) && $value["fee_amt"] > 1) {
                                                $txt_amt = number_format($value["fee_amt"] / 100, 2);
                                            } else {
                                                $txt_amt = 0;
                                            }


                                            $user_currency_code = '';
                                            $userId = $this->session->userdata('id');
                                            $user_details = $this->db->where('id', $userId)->get('users')->row_array();
                                            If (!empty($userId)) {
                                                $service_amount1 = $value["current_wallet"];
                                                $service_amount2 = $value["credit_wallet"];
                                                $service_amount3 = $value["debit_wallet"];
                                                $service_amount4 = $txt_amt;
                                                $service_amount5 = $value["avail_wallet"];
                                                $get_currency = get_currency();
                                                $user_currency = get_user_currency();
                                                $user_currency_code = $user_currency['user_currency_code'];

                                                $service_amount1 = get_gigs_currency($value["current_wallet"], $value["currency_code"], $user_details['currency_code']);
                                                $service_amount2 = get_gigs_currency($value["credit_wallet"], $value["currency_code"], $user_details['currency_code']);
                                                $service_amount3 = get_gigs_currency($value["debit_wallet"], $value["currency_code"], $user_details['currency_code']);
                                                $service_amount4 = get_gigs_currency($txt_amt, $value["currency_code"], $user_details['currency_code']);
                                                $service_amount5 = get_gigs_currency($value["avail_wallet"], $value["currency_code"], $user_details['currency_code']);
                                            } else {
                                                $user_currency_code = settings('currency');
                                                $service_amount1 = $value["current_wallet"];
                                                $service_amount2 = $value["credit_wallet"];
                                                $service_amount3 = $value["debit_wallet"];
                                                $service_amount4 = $txt_amt;
                                                $service_amount5 = $value["avail_wallet"];
                                            }
                                            echo '<tr>
									<td>' . ($key + 1) . '</td>
									<td>' . date("d M Y H:i:s", strtotime($value["created_at"])) . '</td>
									<td>' . currency_conversion($user_currency_code) . '' . $service_amount1 . '</td>
									<td>' . currency_conversion($user_currency_code) . '' . $service_amount2 . '</td>
									<td>' . currency_conversion($user_currency_code) . '' . $service_amount3 . '</td>
									<td>' . currency_conversion($user_currency_code) . '' . $service_amount4 . '</td>
									<td>' . currency_conversion($user_currency_code) . '' . $service_amount5 . '</td>
									<td><lable>' . $value["reason"] . '</lable></td>
									<td><span class="badge bg-' . $color . '-light">' . $message . '</span></td> 
									</tr>';
                                        }
                                    } else {
                                        $norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
										echo '<tr> <td colspan="8"> <div class="text-center text-muted">'.$norecord.'</div></td> </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
</div>


<footer class="footer">
    <?php
    $query = $this->db->query("select * from system_settings WHERE status = 1");
    $result = $query->result_array();
    $stripe_option = '1';
    $publishable_key = '';
    $live_publishable_key = '';
    $logo_front = '';
    foreach ($result as $res) {
        if ($res['key'] == 'stripe_option') {
            $stripe_option = $res['value'];
        }
        if ($res['key'] == 'publishable_key') {
            $publishable_key = $res['value'];
        }
        if ($res['key'] == 'live_publishable_key') {
            $live_publishable_key = $res['value'];
        }

        if ($res['key'] == 'logo_front') {
            $logo_front = $res['value'];
        }
    }

    if ($stripe_option == 1) {
        $stripe_key = $publishable_key;
    } else {
        $stripe_key = $live_publishable_key;
    }

    if (!empty($logo_front)) {
        $web_log = base_url() . $logo_front;
    } else {
        $web_log = base_url() . 'assets/img/logo.png';
    }
    ?>

    <input type="hidden" id="stripe_key" value="<?php echo  $stripe_key; ?>">
    <input type="hidden" id="logo_front" value="<?php echo  $web_log; ?>">
    <input type="hidden" id="tokens" value="<?php echo  $this->session->userdata('chat_token'); ?>">
</footer>


<button id="stripe_booking" ><?php echo (!empty($user_language[$user_selected]['lg_purchase_book'])) ? $user_language[$user_selected]['lg_purchase_book'] : $default_language['en']['lg_purchase_book']; ?></button>


<span id="success_message"><?php echo $this->session->flashdata('success_message'); ?></span>
<span id="error_message"><?php echo $this->session->flashdata('error_message'); ?></span>
<?php if (!empty($this->session->flashdata('success_message'))) { ?>
    <script src="<?php echo base_url(); ?>assets/js/success_toaster.js"></script>
<?php } ?>

<?php if (!empty($this->session->flashdata('error_message'))) { ?>
    <script src="<?php echo base_url(); ?>assets/js/error_toaster.js"></script>
    <?php
}
$this->session->unset_userdata('error_message');
$this->session->unset_userdata('success_message');
?>

    <script>
        $('#stripe_wallet').hide();
        $('#stripe').click(function(){
            $stripe= $(this).val();
           
            if($stripe == 'stripe'){
                $('#stripe_wallet').show();
                $('#stripe_wallet').css('background','#6772E5');
                $('#stripe_wallet').css('border','1px solid #6772E5');
                
            }else{
                $('#stripe_wallet').hide();
            }
        });
        $('#paypal').click(function(){
            $paypal= $(this).val();
           
            if($paypal == 'paypal'){
                $('#stripe_wallet').show();
                $('#stripe_wallet').css('background','#143b85');
                $('#stripe_wallet').css('border','1px solid #143b85');
            }else{
                $('#stripe_wallet').hide();
            }
        });
		
		$('#razorpay').click(function(){
            $razorpay= $(this).val();
           
            if($razorpay == 'razorpay'){
                $('#stripe_wallet').show();
                $('#stripe_wallet').css('background','rgb(18 18 19)');
                $('#stripe_wallet').css('border','1px solid #143b85');
            }else{
                $('#stripe_wallet').hide();
            }
        });
		
		$('#paytabs').click(function(){
            $paytabs= $(this).val();
           
            if($paytabs == 'paytabs'){
                $('#stripe_wallet').show();
                $('#stripe_wallet').css('background','rgb(89 89 252)');
                $('#stripe_wallet').css('border','1px solid #143b85');
            }else{
                $('#stripe_wallet').hide();
            }
        });
		
		
        </script>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>


