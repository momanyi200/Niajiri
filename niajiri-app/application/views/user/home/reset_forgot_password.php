<div class="breadcrumb-bar">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumb-title">
					<h2>Forgot Password</h2>
				</div>
			</div>
			<div class="col-auto float-right ml-auto breadcrumb-menu">
			</div>
		</div>
	</div>
</div>
		
	<section class="about-blk">
	<div class="container">
		<div class="row">
			<div class="col-6">
				<div class="about-blk-content">
					<?php
				if(!empty($chk_data))
				{
				?>
				<form id="resetpwdadmin" action="<?php echo $base_url; ?>/forgot-password" method="POST">
                   <input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>">
                   <input type="hidden" name="user_id" value="<?php echo $euser_id; ?>">
                   <input type="hidden" name="mode" value="<?php echo $emode; ?>">
					<div class="form-group">
						<label class="control-label">New Password</label>
						<input class="form-control" type="password" name="new_password" id="new_password" placeholder="New Password">
					</div>
					
					<div class="form-group">
						<label class="control-label">Confirm Password</label>
						<input class="form-control" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
					</div>
					
					<span id="err_respwd" ></span><br>
					<div class="text-center">
						<button class="btn btn-primary btn-block account-btn" id="resetpwdSubmit" type="submit">Submit</button>
					</div>
				</form>
				
				<?php }else{?>
				<div class="form-group">
						<label class="control-label text-danger">Reset Link mismatch , Check your mail.</label>
					</div>
				<?php } ?>														
				</div>
			</div>
		</div>
	</div>
</section>
