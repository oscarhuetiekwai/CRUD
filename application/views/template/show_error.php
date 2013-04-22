<!--## All validation error under here ## -->

		<?php
		if($this->session->flashdata('success'))
		{
		?>

		<div class="alert alert-success">
		<a class="close" href="#" data-dismiss="alert">&times;</a>
		<p><?php echo $this->session->flashdata('success'); ?></p>
		</div>

		<?php }
		else if($this->session->flashdata('error'))
		{
		?>

		<div class="alert alert-error">
		<a class="close" href="#" data-dismiss="alert">&times;</a>
		<p><?php echo $this->session->flashdata('error'); ?></p>
		</div>

		<?php }
		else if($this->session->flashdata('error_login'))
		{
		?>

		<div class="alert alert-error alert-login">
		<a class="close" href="#" data-dismiss="alert">&times;</a>
		<p><?php echo $this->session->flashdata('error_login'); ?></p>
		</div>


		<?php }
		else if ($this->session->flashdata('not_available'))
		{
		?>

		<div class="alert alert-error">
		<a class="close" href="#" data-dismiss="alert">&times;</a>
		<p><?php echo $this->session->flashdata('not_available');?></p>
		</div>

		<?php }
		else if($this->session->flashdata('error_login')){
			echo "<div class='alert  alert-error alert-login'><a class='close' data-dismiss='alert' href='#'>&times;</a>" . $this->session->flashdata('error_login') . "</div>";
		}
		else if($this->session->flashdata('success_login')){
			echo "<div class='alert  alert-success alert-login'><a class='close' data-dismiss='alert' href='#'>&times;</a>" . $this->session->flashdata('success_login') . "</div>";
		}
		if(validation_errors())
		{
		?>
		<div class="alert alert-error">
		<a class="close" href="#" data-dismiss="alert">&times;</a>
		<ul>
		<?php echo validation_errors('<li>', '</li>'); ?>
		</ul>
		</div>
		<?php } ?>