<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>EVO</title>

		  <?php
			   ##Bootstrap framework -->
                echo css('bootstrap.css');
                echo css('bootstrap-responsive.css');
                //main styles
                //echo css('style.css');
				//echo css('jquery-ui-1.8.24.custom.css');

            ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/lib/datepicker/datepicker.css" />
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
	<script>
			//* hide all elements & show preloader
			document.documentElement.className += 'js';
			var config = {
               'base_url': '<?php echo base_url(); ?>'
            };
	</script>
    </head>
    <body>
	<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
		  <?php $this->load->view('template/header'); ?>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
			<?php $this->load->view('template/sidebar'); ?>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
			<?php if(isset($main)){ $this->load->view($main); } else { echo 'Main content'; } ?>
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer class="pull-right">
        <p>&copy; Company 2013</p>
      </footer>

    </div><!--/.fluid-container-->
	</body>
</html>
<?php
echo js('jquery.min.js');

// main bootstrap js
echo js('bootstrap.min.js');
echo js('bootstrap-datepicker.js');
//echo js('jquery.dataTables.sorting.js');

echo js('lib/datatables/jquery.dataTables.min.js');

echo js('ckeditor/ckeditor.js');

echo js('ckeditor/_samples/sample.js');


if(isset($js_list))
{
    foreach($js_list as $js_row)
    {
      $js_file = $js_row.'.js';
      echo js($js_file);
    }
}
?>
<?php
  //load the js function
  if(isset($js_function))
  {
    foreach($js_function as $js)
    {

      $js = $js.'.js';
      ?>
      <script src="<?php echo base_url(); ?>assets/js/application/<?php echo $js; ?>"></script>
      <?php
    }
  }
?>
 <script type="text/javascript">

$(document).ready(function() {

  $('#button').button();

  $('#button').click(function() {
    $(this).button('loading');
  });

	// cancel button
	$("#cancel").click(function()
	{
	var index = $("#module_index").val();
	window.location = index;
	});

		$('#start_date').datepicker({
				format: 'dd-mm-yyyy'
		});
		$('#end_date').datepicker({
				format: 'dd-mm-yyyy'
		});


        $("#excel").click(function() {
          var form_action = "<?php echo site_url('dashboard/search_email_excel');?>";
          $("#myForm").attr("action", form_action);
          $("#myForm").submit();
        });

      $("#search").click(function() {
          var form_action = "<?php echo site_url('dashboard/search_dashboard');?>";
          $("#myForm").attr("action", form_action);
          $("#myForm").submit();
        });
/*
	$(".chzn_z").chosen({
		allow_single_deselect: true
	});
 */

	$('#dt_d').dataTable({
            "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sSearch": "Search all columns:"
            }
    });
	// navigation drop down
	jQuery('ul.nav li.dropdown').hover(function() {
	  jQuery(this).find('.dropdown-menu').stop(true, true).delay(50).fadeIn();
	}, function() {
	  jQuery(this).find('.dropdown-menu').stop(true, true).delay(50).fadeOut();
	});

});

</script>