<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Paapuu Admin</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <?php echo css('bootstrap.min.css'); ?>
  </head>

  <body>
  </head>
  <body>
    <div class="container">
<section id="forms">
  <br/>
  <br/>
  <br/>
  <div class="page-header">
 <br/>
  <br/>
  <br/>
  <br/>
  <h2 style="margin-left:180px;">Forgot your password?</h2>
  </div>
  <div class="row">
    <div class="span16">
      <?php echo form_open('forgot_pass/checking_emailaddress'); ?>
        <fieldset id="login-form" style="padding-left:180px;width:540px;">

<?php $this->load->view('template/show_error'); ?>
	<div class="clearfix">
		  This can be only your email address on paapuu, or it may be another email address you associated with the account.
		  </div><!-- /clearfix -->
			<div class="clearfix">
            <label for="xlInput">Email Address: </label>
            <div class="input">

              <input class="xlarge disabled" id="email_address" name="email_address" size="30" type="text" rel="twipsy" title="Your Paapuu Email Address" value="<?php echo set_value('email_address'); ?>" />

            </div>
          </div><!-- /clearfix -->
          <div class="actions">
            <input type="submit" class="btn-primary" value="Submit">
			 <a href="<?php echo site_url('login/index');?>" style="margin-top:-9px;" class="btn">Back</a>
          </div>
        </fieldset>
      <?php echo form_close(); ?>
    </div>
  </div><!-- /row -->
  <br />
</section>
    </div><!-- /container -->
    <footer class="footer">
      <div class="container">
		<p class="pull-right">&copy; Paapuu 2012.</p>
		</p>
      </div>

    </footer>

  </body>
</html>
<?php
echo js('jquery-min.js');
echo js('bootstrap-alerts.js');

?>
<script type="text/javascript">
$(document).ready(function(){
	$(".alert-message").alert();
});
</script>
