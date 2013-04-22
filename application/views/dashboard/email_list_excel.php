<?php

$time = date('YmdHis');
$filename = 'email_list_' .$time. '.xls';

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=".$filename);
header("Content-Transfer-Encoding: binary ");

?>
<html>
<head>
<style type="text/css">
body
{
font-family:verdana;
margin-left:50px;
}
table
{
width:700px;
border-collapse:collapse;
}
table,th, td
{
border: 1px solid #DDDDDD;
}
th
{
background-color:#E3E4FA;
}
td
{
text-align:center;
padding:5px;
}
</style>
</head>
<body>

	 		<h2>Title Here</h2>

	 		<table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>#</th>
				  <th>Name</th>
				  <th>Description</th>
				  <th>Active</th>
				  <th>Date Created</th>    
                </tr>
              </thead>
              <tbody>
                <?php $num = 0; if($this->uri->segment(3)){ $num = $num + $this->uri->segment(3);} foreach ($email_list as $row ) {
                  
                  $num++;                       

                ?>
                <tr>
				  <td><?php echo $num; ?></td>
				  <td><h5><?php echo $row->name; ?></h5></td>
				  <td><?php echo $row->description; ?></td>
				  <td><?php if($row->activity == 1){echo 'Active';}else{echo 'Not Active';} ?></td>
				  <td><?php echo $row->date_created; ?></td>
                </tr>
                <?php }?>                
              </tbody>
            </table>  


            </body>
</html>	

