<ul class="breadcrumb">
  <li><a href="<?php echo site_url('dashboard/index'); ?>"><i class="icon-home"></i></a> <span class="divider">/</span></li>
  <li><a href="#">Data</a></li>
</ul>
<div class="row-fluid">
	<div class="span12">
	<div class="page-header">
		<h2>Own table <small>Subtext for header</small></h2>
	</div>
	<a class="btn btn-large btn-block btn-info" href="<?php echo base_url('dashboard/add_row');?>"><i class="icon-plus"></i> Add New Row</a>
	<br>
		 	<?php $this->load->view('template/show_error');  ?>

	 		<?php $this->load->helper('search_helper'); ?>

	 		<form class="form-inline" action="<?php echo base_url('dashboard/search_dashboard'); ?>" method="post" id="myForm" >

	            <input type="text" class="input-small" readonly="readonly" name="start_date" id="start_date" placeholder="From Date" value="<?php echo search_date('search_start_date'); ?>" >
	            <input type="text" class="input-small" readonly="readonly" name="end_date" id="end_date" placeholder="To Date" value="<?php echo search_date('search_end_date'); ?>" >
				<input type="text" class="input-medium" name="name"  placeholder="Staff Name" value="<?php echo search_text('search_name'); ?>" >

				<button type="submit" class="btn" id="button" data-loading-text="Loading...">Filter Result</button>
				<button type="button" class="btn btn-primary" id="excel" >Excel</button>

          	</form>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
				  <th>No</th>
				  <th>Name</th>
				  <th>Description</th>
				  <th>Active</th>
				  <th>Date Created</th>
				  <th>Action</th>
				</tr>
			  </thead>
			   <tbody>
			<?php
				$num = 0; if($this->uri->segment(3)){ $num = $num + $this->uri->segment(3);} if(isset($data_records)) :foreach($data_records as $row): $num++;
			?>
				<tr>
				  <td><?php echo $num; ?></td>
				  <td><?php echo $row->name; ?></td>
				  <td><?php echo $row->description; ?></td>
				  <td><?php if($row->activity == 1){echo '<span class="label label-success">Active</span>';}else{echo '<span class="label label-important">Not Active</span>';} ?></td>
				  <td><?php echo $row->date_created; ?></td>
				  <td><a href="<?php $hash = md5($row->apps_id.SECRETTOKEN);  echo site_url('dashboard/edit_row/'.$row->apps_id.'/'.$hash); ?>" title="Edit"><i class="icon-edit"></i></a>
					<a href="#" class="delete_row" data-id="<?php echo $row->apps_id; ?>"  title="Delete"><i class="icon-trash"></i></a></td>
				</tr>
			<?php endforeach; ?>
			<?php else : ?>
				<tr><td colspan="6">No Result Found.</td></tr>
			<?php endif; ?>

			  </tbody>
			</table>
			<?php echo $this->pagination->create_links(); ?>
      </div>
	<?php //echo $this->pagination->create_links(); ?>
 </div>
