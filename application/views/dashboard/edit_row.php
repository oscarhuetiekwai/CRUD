<ul class="breadcrumb">
  <li><a href="<?php echo site_url('dashboard/index'); ?>"><i class="icon-home"></i></a> <span class="divider">/</span></li>
  <li><a href="#">Edit Row</a></li>
</ul>

		<div class="row-fluid">
		<div class="span12">
		<h3 class="heading">Edit Row</h3>
		  <?php echo form_open_multipart('dashboard/edit_row'); ?>
			<fieldset>
			  <?php $this->load->view('template/show_error'); ?>
			 <div class="form-horizontal">
			<div class="product_well" id="product1">
			<fieldset>

			<div class="control-group">
			<div class="control">
			<label for="control-label" class="control-label"></label>
			<code>Note :</code> All field mark with <code>*</code> are required.
			</div>
			</div><!-- /control-group -->

			<div class="control-group formSep template">
			<label for="input01" class="control-label">Apps Title*: <a href="#" class="ttip_t" title="Your Apps Title" style="text-decoration:none;color:grey;">[?]</a></label>
			<div class="controls">
			<input id="title" name="name" size="30" type="text"   class="span8" value="<?php echo set_value('name',$data_records->name); ?>" placeholder="Apps Title"  title="Eg: Your Apps Title"  />
			</div>
			</div>

	
			<div class=" control-group formSep">
			<label for="input01" class="control-label">Apps Content*: <a href="#" class="ttip_t" title="Your Apps Content" style="text-decoration:none;color:grey;">[?]</a></label>
			<div class="controls">
			<textarea class="ckeditor" name="description"><?php echo set_value('description',$data_records->description); ?></textarea>
			<input type="hidden" name="apps_id" id="apps_id"  value="<?php echo set_value('apps_id',$data_records->apps_id); 	?>" >
			</div>
			</div>

		<!--end product-->
		<!-- /single_form -->
		<div class="control-group template">
		<div class="controls">
		<input type="hidden" name="module_index" id="module_index" value="<?php echo base_url('dashboard/index/'); ?>" />
		<input type="submit" class="btn-primary btn" value="Save Change">&nbsp;<button type="reset" class="btn" id="cancel">Cancel</button>
		</div>
		</div>
		</div>
		</fieldset>
		</form>
		</div>