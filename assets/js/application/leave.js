// enchanced select
	$(".chzn_a").chosen({
		allow_single_deselect: true
	});
	$(".chzn_b").chosen();


$(document).ready(function() {

	$("#calculate12").click(function() {
			var start_date = $(this).attr('data-start-date');
			var end_date = $(this).attr('data-end-date');
			//var start_date = $('#start_date').find('input[name="start_date"]').val();
			//var end_date = $('#end_date').find('input[name="end_date"]').val();
			
			alert(start_date);
			//var diff = Math.abs(start_date - end_date);
			$('#pa').val(start_date);
			//hashResponseAjax(credit,user_id,login);
   });

	$('#start_date').datepicker({format: "dd-mm-yyyy"}).on('changeDate', function(ev){
            var dateText = $(this).data('date');
            $('#start_date').datepicker('hide');
	});

	$('#edit_date').datepicker({format: "dd-mm-yyyy"}).on('changeDate', function(ev){
			var dateText = $(this).data('date');
            $('#edit_date').datepicker('hide');
    });


	//check box hide and show
	$("#button_show_hide").hide(100);
    $('.check_me').click(function() {
        if ( $('.check_me:checked').length >= 1) {
            $("#button_show_hide").show(500);
        } else {
            $("#button_show_hide").hide(500);
        }
    });

	//check box hide and show
    $('.select_group').click(function() {
        if ( $('.select_group:checked').length >= 1) {
            $("#button_show_hide").show(500);
        } else {
            $("#button_show_hide").hide(500);
        }
    });

	//check all check box
	$(".select_group").click(function() {
		var table_id12 = $(this).closest('table').attr('id');
		if($(this).is(':checked'))
		{
			$("#"+table_id12+" :checkbox").attr('checked', $(this).attr('checked'));
		}
		else
		{
			 $("#"+table_id12+" :checkbox").attr('checked', false);
		}

	});

	//delete Leave type part
	$(".delete_leave_type").click(function() {
		var leave_type_id = $(this).closest('tr').attr('id');
		var arr = leave_type_id.split('_');
		var leave_type_id = arr[0];
		var answ = confirm('Delete this row?');
		if(answ)
		{
			deletehomeAjax(leave_type_id);
		}
		else
		{
			return false;
		}
	});

	function deletehomeAjax(leave_type_id)
	{
		$.ajax({
			type: "POST",
			async : false,
			url: config.base_url + 'leave/ajax_delete_leave_type',
			dataType: 'json',
			data : {
				leave_type_id : leave_type_id,
			},
			success : function(data) {
				if(data=='success')
				{
					alert('The Leave Type has been deleted');
					location.reload();
				}
				else if(data=='error')
				{
					alert('Error.');
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest + " : " + textStatus + " : " + errorThrown);
			}
		});
	}
	
		//delete Leave type part
	$(".delete_apply_leave").click(function() {
		var leave_application_id = $(this).closest('tr').attr('id');
		var arr = leave_application_id.split('_');
		var leave_application_id = arr[0];
		var answ = confirm('Delete this row?');
		if(answ)
		{
			deleteapplyleaveAjax(leave_application_id);
		}
		else
		{
			return false;
		}
	});

	function deleteapplyleaveAjax(leave_application_id)
	{
		$.ajax({
			type: "POST",
			async : false,
			url: config.base_url + 'leave/ajax_delete_apply_leave',
			dataType: 'json',
			data : {
				leave_application_id : leave_application_id,
			},
			success : function(data) {
				if(data=='success')
				{
					alert('Your Applies leave has been deleted');
					location.reload();
				}
				else if(data=='error')
				{
					alert('Error.');
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest + " : " + textStatus + " : " + errorThrown);
			}
		});
	}



	//delete APPS part
	$(".delete_notification").click(function() {

        var notification_id =  $(this).attr('data-id');

		var answ = confirm('Delete this row?');
		if(answ)
		{
			deletenotificationAjax(notification_id);
		}
		else
		{
			return false;
		}
	});

	function deletenotificationAjax(notification_id)
	{
		$.ajax({
			type: "POST",
			async : false,
			url: config.base_url + 'dashboard/ajax_delete_notification',
			dataType: 'json',
			data : {
				notification_id : notification_id,
			},
			success : function(data) {
				if(data=='success')
				{
					alert('The Announcement has been deleted');
					location.reload();
				}
				else if(data=='error')
				{
					alert('Error.');
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest + " : " + textStatus + " : " + errorThrown);
			}
		});
	}

	$(".button_update_ajax").click(function() {
		var product_id = $(this).closest('tr').attr('id');
		var arr = product_id.split('_');
		var product_name = arr[1];
		var leave_type_id = arr[0];
		var answ = confirm('Update this leave type status row?');
		if(answ)
		{
			updateappsAjax(leave_type_id);
		}
		else
		{
			return false;
		}
	});
	function updateappsAjax(leave_type_id)
	{
		$.ajax({
			type: "POST",
			async : false,
			url: config.base_url + 'leave/ajax_update_leave_type_status',
			dataType: 'json',
			data : {
				leave_type_id : leave_type_id,
			},
			success : function(data){
				if(data=='success')
				{
					alert('Successfully Updated');
					location.reload();
				}
				else if(data=='error')
				{
					alert('Error.');
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest + " : " + textStatus + " : " + errorThrown);
			}
		});
	}

	function createUploader(){
            var uploader = new qq.FileUploader({
                element: document.getElementById('file-uploader'),
                action: config.base_url + 'system/ajax_upload.php',
                debug: true,
                onComplete: function(id, fileName, responseJSON){

                    var upload_filename = responseJSON.filename;
                    ajax_read_contact_file(upload_filename);
                }
            });
    }

	function ajax_read_contact_file(upload_filename)
    {
            $.ajax({
                type: "POST",
                url: config.base_url + 'home/ajax_read_contact_file',
                dataType: 'json',
                data : {
                    contact_filename : upload_filename
                },
                success : function(data) {
                   //console.log(data);
					var get_path = config.base_url;
					var name = data;
					var toRemove = './';
					var gorge = name.replace(toRemove,'');
					var final_data = get_path+gorge;
					//$('#image_value').empty().append(final_data);
					$('#image_value').val(final_data);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest + " : " + textStatus + " : " + errorThrown);
                }
            });
    }

	function select_all()
	{
		var text_val=eval("document.image_value.type");
		text_val.focus();
		text_val.select();
	}


});