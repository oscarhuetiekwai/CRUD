<ul class="breadcrumb">
<li>
<a href="<?php echo site_url('dashboard/index'); ?>"><i class="icon-home icon-white"></i></a> <span class="divider">|</span>
</li>
<li class="active">
<a href="#" style="color:white;">Profile Setting</a>
</li>
</ul>

<div class="row-fluid">
	<div class="span12">
		<h3 class="heading">User Profile</h3>
		<div class="row-fluid">
			<div class="span12">
				<form class="form-horizontal" method="POST" action="<?php echo site_url('profile_setting/update_profile'); ?>">
				<fieldset>
				<?php $this->load->view('template/show_error'); ?>
				<div class="control-group formSep">
				<label class="control-label">Username</label>
				<div class="controls text_line">
				<strong><input type="text" id="username" class="input-xlarge" name="username" value="<?php echo set_value('username',$admin_records->username); ?>" readonly="readonly" /></strong>
				</div>
				</div>

				<div class="control-group formSep">
				<label for="u_email" class="control-label">Email</label>
				<div class="controls">
				<input type="text" id="email" class="input-xlarge" name="email_address" value="<?php echo set_value('email_address',$admin_records->email_address); ?>" />
				</div>
				</div>
				<div class="control-group formSep">
				<label for="password" class="control-label">Password</label>
				<div class="controls">
				<div class="sepH_b">
				<input type="password" id="password" class="input-xlarge" value="password" name="password" />
				<span class="help-block">Enter your password</span>
				</div>
				<input type="password" id="confirm_password" class="input-xlarge" name="confirm_password"/>
				<span class="help-block">Repeat password</span>
				</div>
				</div>

				<div class="control-group">
				<div class="controls">
				<input type="hidden" name="module_index" id="module_index" value="<?php echo base_url('dashboard/index/'); ?>" />
				<input type="submit" class="btn-primary btn" value="Save Change">&nbsp;<button type="reset" class="btn" id="cancel">Cancel</button>
				</div>
				</div>
				</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>