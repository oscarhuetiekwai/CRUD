          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">CRUD</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li <?php if($page == "dashboard"){ ?> class="active" <?php } ?>><a href="<?php echo base_url()."dashboard/index"; ?>">Home</a></li>
              <li <?php if($page == "anothertable"){ ?> class="active" <?php } ?>><a href="<?php echo base_url()."dashboard/another_table/"; ?>">Another table</a></li>
            </ul>
			<ul class="nav pull-right">
                      <li><a href="#">Link</a></li>
                      <li class="divider-vertical"></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <li><a href="<?php echo base_url("profile_setting/index"); ?>">Profile Setting</a></li>
                          <li class="divider"></li>
                          <li><a href="<?php echo base_url()."login/logout/"; ?>">Logout</a></li>
                        </ul>
                      </li>
                    </ul>
          </div><!--/.nav-collapse -->