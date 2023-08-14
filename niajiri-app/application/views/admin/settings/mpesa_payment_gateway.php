<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Payment Settings</h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <ul class="nav nav-tabs menu-tabs">
            <li class="nav-item"  style="display: none">
                <a class="nav-link" href="<?php echo base_url() . 'admin/moyaser-payment-gateway'; ?>">Moyasar</a>
            </li>
            <li class="nav-item " style="display: none">
                <a class="nav-link" href="<?php echo base_url() . 'admin/stripe-payment-gateway'; ?>">Stripe</a>
            </li>
            <li class="nav-item " style="display: none">
                <a class="nav-link" href="<?php echo base_url() . 'admin/paypal-payment-gateway'; ?>">PayPal</a>
            </li>
            <li class="nav-item" style="display: none">
                <a class="nav-link" href="<?php echo base_url() . 'admin/offlinepayment'; ?>">Bank Transfer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/cod-payment-gateway'; ?>">COD</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url() . 'admin/mpesa-payment-gateway'; ?>">M-PESA(Safari)</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo base_url() . 'admin/settings/mpesa_edit/' . $list['id']; ?>" method="post">
                            <h4 class="text-primary">MPESA</h4>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<div class="outerDivFull" >
							<div class="switchToggle">
								<input name="mpesa_show" type="checkbox"  value="1" id="switch" <?php if($list['status']== 1) { ?>checked <?php } ?>>
								<label for="switch">Toggle</label>
							</div>
							</div>

                            <div class="form-group">
                                <label>MPESA Option</label>
                                <div>
                                    <div class="form-check form-radio form-check-inline">
                                        <input class="form-check-input stripe_payment" id="sandbox" name="gateway_type" value="sandbox" type="radio" <?php echo  ($list['gateway_type'] == "sandbox") ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="sandbox">Sandbox</label>
                                    </div>
                                    <div class="form-check form-radio form-check-inline">
                                        <input class="form-check-input stripe_payment" id="livepaypal" name="gateway_type" value="live" type="radio"  <?php echo  ($list['gateway_type'] == "live") ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="livepaypal">Live</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Consumer Key</label>
                                <input  type="text" id="consumer_key" name="consumer_key"  value="<?php if (!empty($list['consumer_key'])) {
    echo $list['consumer_key'];
} ?>" required class="form-control" >
                            </div>
                            <div class="form-group">
                                <label>Consumer Secret</label>
                                <input type="text" id="consumer_secret" name="consumer_secret" value="<?php if (!empty($list['consumer_secret'])) {
    echo $list['consumer_secret'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>Token Link</label>
                                <input type="text" id="token_link" name="token_link" value="<?php if (!empty($list['token_link'])) {
    echo $list['token_link'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>Checkout Process Link</label>
                                <input type="text" id="checkout_process_link" name="checkout_process_link" value="<?php if (!empty($list['checkout_processlink'])) {
    echo $list['checkout_processlink'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>Checkout Short Code</label>
                                <input type="text" id="checkout_shortcode" name="checkout_shortcode" value="<?php if (!empty($list['checkout_shortcode'])) {
    echo $list['checkout_shortcode'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>Checkout Pass Key</label>
                                <input type="text" id="checkout_passkey" name="checkout_passkey" value="<?php if (!empty($list['checkout_passkey'])) {
    echo $list['checkout_passkey'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>Checkout Callback URL</label>
                                <input type="text" id="checkout_rcallbackurl" name="checkout_rcallbackurl" value="<?php if (!empty($list['checkout_rcallbackurl'])) {
    echo $list['checkout_rcallbackurl'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>Checkout Query Link</label>
                                <input type="text" id="checkout_querylink" name="checkout_querylink" value="<?php if (!empty($list['checkout_querylink'])) {
    echo $list['checkout_querylink'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>C2B Register URL</label>
                                <input type="text" id="c2b_registerUrl" name="c2b_registerUrl" value="<?php if (!empty($list['c2b_registerUrl'])) {
    echo $list['c2b_registerUrl'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>C2B Short Code</label>
                                <input type="text" id="c2b_shortcode" name="c2b_shortcode" value="<?php if (!empty($list['c2b_shortcode'])) {
    echo $list['c2b_shortcode'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>C2B Confirmation URL</label>
                                <input type="text" id="c2b_confirmationUrl" name="c2b_confirmationUrl" value="<?php if (!empty($list['c2b_confirmationUrl'])) {
    echo $list['c2b_confirmationUrl'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>C2B Validation URL</label>
                                <input type="text" id="c2b_validationUrl" name="c2b_validationUrl" value="<?php if (!empty($list['c2b_validationUrl'])) {
    echo $list['c2b_validationUrl'];  
} ?>" required class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>C2B Transaction URL</label>
                                <input type="text" id="c2b_transactionUrl" name="c2b_transactionUrl" value="<?php if (!empty($list['c2b_transactionUrl'])) {
    echo $list['c2b_transactionUrl'];  
} ?>" required class="form-control" >
                            </div>
                            
                            <div class="mt-4">
<?php if ($user_role == 1) { ?>
                                    <button class="btn btn-primary" name="form_submit" value="submit" type="submit">Submit</button>
<?php } ?>

                                <a href="<?php echo base_url() . 'admin/stripe_payment_gateway' ?>" class="btn btn-danger m-l-5">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
