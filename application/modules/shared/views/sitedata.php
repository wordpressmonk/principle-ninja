<form class="form-horizontal" role="form" id="siteSettingsForm">

	<input type="hidden" name="siteID" id="siteID" value="<?php echo $data['site']->sites_id; ?>">

	<div id="siteSettingsWrapper" class="siteSettingsWrapper">

		<div class="optionPane">

			<h6><?php echo $this->lang->line('sitedata_sitedetails'); ?></h6>

			<div class="form-group">
				<label for="sites_name" class="col-sm-3 control-label"><?php echo $this->lang->line('sitedata_label_name'); ?></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="sites_name" name="sites_name" placeholder="<?php echo $this->lang->line('sitedata_label_name'); ?>" value="<?php echo $data['site']->sites_name; ?>">
				</div>
			</div>

			<div class="form-group">
				<label for="global_css" class="col-sm-3 control-label"><?php echo $this->lang->line('sitedata_label_globalcss'); ?></label>
				<div class="col-sm-9">
					<textarea class="form-control" id="global_css" name="global_css" placeholder="<?php echo $this->lang->line('sitedata_label_globalcss'); ?>" rows="6"><?php echo $data['site']->global_css; ?></textarea>
				</div>
			</div>

		</div><!-- /.optionPane for sites name -->

		<?php if ($this->session->userdata('user_type') == "Admin") : ?>
			<div class="optionPane">

				<h6 style="margin-bottom: 20px"><?php echo $this->lang->line('sitedata_homepage_details'); ?></h6>

				<p class="small">
					<?php printf($this->lang->line('sitedata_homepage_explanation'), $this->config->item('base_url'), $this->config->item('base_url'));?>
				</p>

				<div class="form-group">
					<div class="col-sm-12">
						<input type="hidden" value="0" name="home_page" >
						<label class="checkbox" for="home_page">
							<input type="checkbox" value="1" <?php if ($data['site']->home_page == 1) : ?>checked<?php endif; ?> name="home_page" data-toggle="switch" id="home_page">
							<?php echo $this->lang->line('sitedata_label_home_page'); ?>
						</label>
					</div>
				</div>

			</div><!-- /.optionPane for home page -->
		<?php endif;?>

		<?php if ($this->session->userdata('user_type') == "Admin" || (isset($data['hosting_option']) && $data['hosting_option'] != '')) : ?>
			<div class="optionPane" id="siteSettingsPublishing">

				<h6><?php echo $this->lang->line('sitedata_hostingdetails'); ?></h6>

				<?php if ($data['site']->custom_domain == '' && $data['site']->sub_domain == '' && $data['site']->sub_folder == '') : ?>
					<div class="alert alert-warning">
						<button class="close fui-cross" data-dismiss="alert"></button>
						<h4><?php echo $this->lang->line('sitedata_hosting_not_published_heading'); ?></h4>
						<p>
							<?php echo $this->lang->line('sitedata_hosting_not_published_message'); ?>
						</p>
					</div>
				<?php else:?>
					<div class="alert alert-success">
						<button class="close fui-cross" data-dismiss="alert"></button>
						<h4><?php echo $this->lang->line('sitedata_hosting_published_heading'); ?></h4>
						<p>
							<?php echo $this->lang->line('sitedata_hosting_published_message'); ?>
						</p>
						<ul>
							<?php if ($data['site']->custom_domain != '') : ?>
								<li><b><?php echo $this->lang->line('sitedata_hosting_dropdown_customdomain'); ?></b>: <a href="http://<?php echo $data['site']->custom_domain; ?>" target="_blank"><?php echo $data['site']->custom_domain; ?></a></li>
							<?php endif; ?>
							<?php if ($data['site']->sub_domain != '') : ?>
								<?php $sub = get_domaininfo($this->config->item('base_url')); ?>
								<li><b><?php echo $this->lang->line('sitedata_hosting_dropdown_subdomain'); ?></b>: <a href="<?php echo $sub['protocol'] . '://' . $data['site']->sub_domain . '.' . $sub['domain']; ?>/" target="_blank"><?php echo $sub['protocol'] . '://' . $data['site']->sub_domain . '.' . $sub['domain']; ?>/</a></li>
							<?php endif; ?>
							<?php if ($data['site']->sub_folder != '') : ?>
								<li><b><?php echo $this->lang->line('sitedata_hosting_dropdown_subfolder'); ?></b>: <a href="<?php echo $this->config->item('base_url') . $data['site']->sub_folder; ?>/" target="_blank"><?php echo $this->config->item('base_url'); ?><?php echo $data['site']->sub_folder; ?>/</a></li>
							<?php endif; ?>
						</ul>
					</div>
				<?php endif; ?>

				<div class="row">

					<div class="col-md-4">
						<select class="form-control select select-primary select-block mbl" id="select_hostingOptions">
							<option value="" selected=""><?php echo $this->lang->line('sitedata_hosting_dropdown_choose'); ?></option>
							<?php if ($this->session->userdata('user_type') != "Admin") : ?>
								<?php if (in_array("Sub-Folder", $data['hosting_option'])) : ?>
									<option value="Sub Folder"><?php echo $this->lang->line('sitedata_hosting_dropdown_subfolder'); ?></option>
								<?php endif; ?>
								<?php if (in_array("Sub-Domain", $data['hosting_option'])) : ?>
									<option value="Sub Domain"><?php echo $this->lang->line('sitedata_hosting_dropdown_subdomain'); ?></option>
								<?php endif; ?>
								<?php if (in_array("Custom Domain", $data['hosting_option'])) : ?>
									<option value="Custom Domain"><?php echo $this->lang->line('sitedata_hosting_dropdown_customdomain'); ?></option>
								<?php endif; ?>
							<?php else : ?>
								<option value="Sub Folder"><?php echo $this->lang->line('sitedata_hosting_dropdown_subfolder'); ?></option>
								<option value="Sub Domain"><?php echo $this->lang->line('sitedata_hosting_dropdown_subdomain'); ?></option>
								<option value="Custom Domain"><?php echo $this->lang->line('sitedata_hosting_dropdown_customdomain'); ?></option>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-md-8">

						<section id="section_subfolder" class="hosting_option">
							<div class="input-group">
								<span class="input-group-addon"><?php echo $this->config->item('base_url'); ?></span>
								<input type="text" name="sub_folder" class="form-control" placeholder="<?php echo $this->lang->line('sitedata_hosting_placeholder_sub_folder');?>" value="<?php echo $data['site']->sub_folder; ?>">
							</div>

							<div>
								<?php echo $this->lang->line('sitedata_hosting_info_subfolder'); ?>
							</div>
						</section>

						<section id="section_subdomain" class="hosting_option">
							<?php $sub = get_domaininfo($this->config->item('base_url')) ?>
							<div class="input-group">
								<input type="text" name="sub_domain" class="form-control" placeholder="<?php echo $this->lang->line('sitedata_hosting_placeholder_sub_domain');?>" value="<?php echo $data['site']->sub_domain; ?>">
								<span class="input-group-addon">.<?php echo $sub['domain']; ?>/</span>
							</div>

							<div>
								<?php echo $this->lang->line('sitedata_hosting_info_subdomain'); ?>
							</div>
						</section>

						<section id="section_customdomain" class="hosting_option">
							<div class="form-group">
								<input type="text" name="custom_domain" class="form-control" placeholder="<?php echo $this->lang->line('sitedata_hosting_placeholder_custom_domain');?>" value="<?php echo $data['site']->custom_domain; ?>">
								<span class="form-control-feedback fui-check"></span>
							</div>

							<div>
								<?php

									$serverIP = ( $_SERVER['SERVER_ADDR'] != '::1')? $_SERVER['SERVER_ADDR']: $this->lang->line('sitedata_hosting_info_customdomain_server');
									echo sprintf($this->lang->line('sitedata_hosting_info_customdomain'), $serverIP);
								?>
							</div>
						</section>

					</div>

				</div><!-- /.row -->

			</div><!-- ./optionPane for hosting option -->
		<?php endif; ?>

		<?php if ($this->session->userdata('user_type') == "Admin" || $data['ftp_publish'] == 'yes') : ?>
			<div class="optionPane" id="siteSettingsFTPPublishing">

				<h6><?php echo $this->lang->line('sitedata_publishingdetails'); ?></h6>

				<div class="form-group">
					<label for="ftp_type" class="col-sm-3 control-label"><?php echo $this->lang->line('sitedata_label_ftp_sftp'); ?></label>
					<div class="col-sm-9">
						<select class="form-control select select-default select-block mbl" id="select_ftp_type" name="ftp_type">
							<option value="ftp" <?php if ($data['site']->ftp_type == 'ftp') : ?>selected="selected"<?php endif; ?>><?php echo $this->lang->line('sitedata_ftp'); ?></option>
							<?php if (isset($data['ssh2']) && $data['ssh2'] == TRUE) : ?>
								<option value="sftp" <?php if ($data['site']->ftp_type == 'sftp') : ?>selected="selected"<?php endif; ?>><?php echo $this->lang->line('sitedata_sftp'); ?></option>
							<?php endif; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="remote_url" class="col-sm-3 control-label"><?php echo $this->lang->line('sitedata_label_remote_url'); ?></label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="remote_url" name="remote_url" placeholder="<?php echo $this->lang->line('sitedata_label_remote_url_placeholder'); ?>" value="<?php echo $data['site']->remote_url; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="ftp_server" class="col-sm-3 control-label"><?php echo $this->lang->line('sitedata_label_ftp_server'); ?></label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="ftp_server" name="ftp_server" placeholder="<?php echo $this->lang->line('sitedata_label_ftp_server'); ?>" value="<?php echo $data['site']->ftp_server; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="ftp_user" class="col-sm-3 control-label"><?php echo $this->lang->line('sitedata_label_ftp_user'); ?></label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="ftp_user" name="ftp_user" placeholder="<?php echo $this->lang->line('sitedata_label_ftp_user'); ?>" value="<?php echo $data['site']->ftp_user; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="ftp_password" class="col-sm-3 control-label"><?php echo $this->lang->line('sitedata_label_ftp_password'); ?></label>
					<div class="col-sm-9">
						<input type="password" class="form-control" id="ftp_password" name="ftp_password" placeholder="<?php echo $this->lang->line('sitedata_label_ftp_password'); ?>" value="<?php echo $data['site']->ftp_password; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="ftp_port" class="col-sm-3 control-label"><?php echo $this->lang->line('sitedata_label_ftp_port'); ?></label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="ftp_port" name="ftp_port" placeholder="<?php echo $this->lang->line('sitedata_label_ftp_port_placeholder'); ?>" value="<?php if ($data['site']->ftp_port != 0) { echo $data['site']->ftp_port; } else { echo "21"; } ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="path" class="col-sm-3 control-label"><?php echo $this->lang->line('sitedata_label_ftp_path'); ?></label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="ftp_path" name="ftp_path" placeholder="<?php echo $this->lang->line('sitedata_label_ftp_path'); ?>" value="<?php if ($data['site']->ftp_path != '') { echo $data['site']->ftp_path; } else { echo "/"; } ?>">
					</div>
					<div class="col-sm-3">
						<button type="button" class="btn btn-info btn-embossed btn-block " id="siteSettingsBrowseFTPButton"><span class="fui-search"></span> <?php echo $this->lang->line('sitedata_button_browse_server'); ?></button>
					</div>
				</div>
				<div class="form-group ftpBrowse" id="ftpBrowse">
					<div class="col-sm-6 col-sm-offset-3">

						<div class="ftpList" id="ftpList">

							<div class="loaderFtp">
								<img src="<?php echo base_url(); ?>img/loading.gif" alt="Loading...">
								<?php echo $this->lang->line('sitedata_connecting_to_ftp'); ?>
							</div>

							<div id="ftpAlerts"></div>

							<div id="ftpListItems"></div>

						</div><!-- /.ftpList -->

					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button type="button" class="btn btn-inverse btn-embossed btn-wide" id="siteSettingsTestFTP"><span class="fui-power"></span> <?php echo $this->lang->line('sitedata_button_test_ftp_connection'); ?></button>
						<span class="FTP_Connecting" style="display: none;"><?php echo $this->lang->line('sitedata_testing_ftp_connection'); ?></span>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9" id="ftpTestAlerts">

					</div>
				</div>

			</div><!-- ./optionPane for FTP details -->
		<?php endif; ?>

	</div><!-- /.siteSettingsWrapper -->

</form>