<body class="sites">

    <?php $this->load->view("shared/nav.php"); ?>

    <div class="container-fluid">

        <?php if ($this->session->flashdata('success') != '') : ?>
            <div class="row margin-top-20">
                <div class="col-md-12">
                    <div class="alert alert-success margin-bottom-0">
                        <button type="button" class="close fui-cross" data-dismiss="alert"></button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                </div><!-- /.col -->
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error') != '') : ?>
            <div class="row margin-top-20">
                <div class="col-md-12">
                    <div class="alert alert-danger margin-bottom-0">
                        <button type="button" class="close fui-cross" data-dismiss="alert"></button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                </div><!-- /.col -->
            </div>
        <?php endif; ?>

        <?php if (isset($site_limitation)) : ?>
            <div class="row margin-top-20">
                <div class="col-md-12">
                    <div class="alert alert-danger margin-bottom-0">
                        <button type="button" class="close fui-cross" data-dismiss="alert"></button>
                        <?php echo $site_limitation; ?>
                    </div>
                </div><!-- /.col -->
            </div>
        <?php endif; ?>

        <div class="row">

            <div class="col-md-9 col-sm-8">

                <h1><span class="fui-windows"></span> <?php echo $this->lang->line('sites_header'); ?></h1>

            </div><!-- /.col -->

            <div class="col-md-3 col-sm-4 text-right">

                <a <?php if( isset($templates) && count($templates) > 0 ):?>href="#newSiteModal" data-toggle="modal"<?php else:?>href="<?php echo site_url('sites/create');?>"<?php endif;?> class="btn btn-lg btn-primary btn-embossed btn-wide margin-top-40 add"><span class="fui-plus"></span> <?php echo $this->lang->line('sites_createnewsite'); ?></a>

            </div><!-- /.col -->

        </div><!-- /.row -->

        <hr class="dashed">

        <div class="row margin-bottom-30">

            <?php if ($this->session->userdata('user_type') == 'Admin') : ?>
                <div class="col-md-3 col-sm-6 dropdown">

                    <div class="form-group">
                        <select name="userDropDown" id="userDropDown" class="form-control select select-inverse btn-block mbl <?php if ( ! isset($sites) || count($sites) == 0) : ?>disabled<?php endif; ?>">
                            <option value=""><?php echo $this->lang->line('sites_filterbyuser'); ?></option>
                            <option value="All"><?php echo $this->lang->line('sites_filterbyuserall'); ?></option>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?php echo $user['first_name'] . ' ' . $user['last_name']; ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?> (<?php echo $user['email']?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div><!-- /.col -->
            <?php endif;?>

            <div class="col-md-3 col-sm-6 dropdown">

                <div class="form-group">
                    <select name="sortDropDown" id="sortDropDown" class="form-control select select-inverse select-block mbl" <?php if ( ! isset($sites) || count($sites) == 0) : ?>disabled<?php endif; ?>>
                        <option value=""><?php echo $this->lang->line('sites_sortby'); ?></option>
                        <option value="CreationDate"><?php echo $this->lang->line('sites_sortby_creationdate'); ?></option>
                        <option value="LastUpdate"><?php echo $this->lang->line('sites_sortby_lastupdated'); ?></option>
                        <option value="NoOfPages"><?php echo $this->lang->line('sites_sortby_numberofpages'); ?></option>
                    </select>
                </div>

            </div><!-- /.col -->

            <div class="col-md-6">

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('sites_search_placeholder');?>" id="inputSearchSites">
                        <span class="input-group-btn">
                            <button type="submit" class="btn" id="buttonSearchSites"><span class="fui-search"></span></button>
                        </span>
                    </div>
                </div>

            </div><!-- /.col -->

        </div><!-- /.row -->

        <div class="row">

            <?php if (isset($sites) && count($sites) > 0) : ?>

                <div class="col-md-12">

                    <div class="sites" id="sites">

                        <?php foreach ($sites as $site) : ?>

                            <div class="site <?php if ( $site->home_page == 1 ) {echo "homepage";}?>" data-name="<?php echo $site->first_name; ?> <?php echo $site->last_name; ?>" data-pages="<?php echo $site->page_count; ?>" data-created="<?php echo date("Y-m-d", $site->sites_created_on); ?>" data-update="<?php if ($site->sites_lastupdate_on != '') { echo date("Y-m-d", $site->sites_lastupdate_on); } ?>" id="site_<?php echo $site->sites_id; ?>" data-site-name="<?php echo $site->sites_name; ?>">

                                <div class="window">

                                    <div class="top">

                                        <div class="buttons clearfix">
                                            <span class="left red"></span>
                                            <span class="left yellow"></span>
                                            <span class="left green"></span>
                                        </div>

                                        <b><?php echo $site->sites_name; ?></b>

                                    </div><!-- /.top -->

                                    <div class="viewport">

                                        <?php if ($site->sitethumb != '') : ?>
                                        <a href="sites/<?php echo $site->sites_id; ?>" class="placeHolder">
                                            <img data-original="<?php echo base_url() . $site->sitethumb;?>">
                                        </a>
                                        <?php else : ?>
                                        <a href="sites/<?php echo $site->sites_id; ?>" class="placeHolder">
                                            <img src="<?php echo base_url() . "img/nothumb.png";?>">
                                        </a>
                                        <?php endif; ?>

                                    </div><!-- /.viewport -->

                                    <div class="bottom"></div><!-- /.bottom -->

                                </div><!-- /.window -->

                                <div class="siteDetails">

                                    <p>
                                    <?php if( $this->session->userdata('user_type') == 'Admin' ):?>
                                        <select class="form-control select select-default select-sm selectSiteOwner" data-site-id="<?php echo $site->sites_id;?>">
                                            <?php foreach( $users as $user ):?>
                                            <option value="<?php echo $user['id'];?>" <?php if ( $user['id'] == $site->users_id ):?>selected<?php endif;?>><?php echo $user['first_name'];?> <?php echo $user['last_name'];?></option>
                                            <?php endforeach;?>
                                        </select>
                                        <?php echo $site->page_count; ?> <?php echo $this->lang->line('sites_details_pages'); ?><br>
                                        <?php echo $this->lang->line('sites_details_createdon'); ?>: <b><?php echo date("Y-m-d", $site->sites_created_on); ?></b><br>
                                        <?php echo $this->lang->line('sites_details_lasteditedon'); ?>: <b><?php if ($site->sites_lastupdate_on != '') { echo date("Y-m-d", $site->sites_lastupdate_on); } else { echo "NA"; } ?></b>
                                    <?php else:?>
                                        <?php echo $this->lang->line('sites_details_owner'); ?>: <b><?php echo $site->first_name; ?> <?php echo $site->last_name; ?></b>, <?php echo $site->page_count; ?> <?php echo $this->lang->line('sites_details_pages'); ?><br>
                                        <?php echo $this->lang->line('sites_details_createdon'); ?>: <b><?php echo date("Y-m-d", $site->sites_created_on); ?></b><br>
                                        <?php echo $this->lang->line('sites_details_lasteditedon'); ?>: <b><?php if ($site->sites_lastupdate_on != '') { echo date("Y-m-d", $site->sites_lastupdate_on); } else { echo "NA"; } ?></b>
                                    <?php endif;?>
                                    </p>

                                    <hr class="dashed light">

                                    <div class="clearfix">

                                        <a href="<?php echo site_url('sites/' . $site->sites_id);?>" title="<?php echo $this->lang->line('sites_details_tooltip_edit');?>" data-toggle="tooltip" data-delay='{"show": 2000, "hide": 0}' class="btn btn-primary btn-embossed btn-fourth pull-left btn-sm first" data-siteid="<?php echo $site->sites_id; ?>">
                                            <span class="fui-new"></span>
                                            <?php //echo $this->lang->line('sites_button_editthissite'); ?>
                                        </a>

                                        <a href="#" class="btn btn-info btn-embossed btn-fourth pull-left btn-sm siteSettingsModalButton" title="<?php echo $this->lang->line('sites_details_tooltip_settings');?>" data-toggle="tooltip" data-delay='{"show": 2000, "hide": 0}' data-siteid="<?php echo $site->sites_id; ?>">
                                            <span class="fui-gear"></span>
                                            <?php //echo $this->lang->line('sites_button_settings'); ?>
                                        </a>

                                        <a href="<?php echo site_url('sites/clone_site/' . $site->sites_id)?>" title="<?php echo $this->lang->line('sites_details_tooltip_clone');?>" data-toggle="tooltip" data-delay='{"show": 2000, "hide": 0}' class="btn btn-default btn-sm btn-fourth btn-embossed pull-left">
                                            <span class="fui-windows"></span>
                                            <?php //echo $this->lang->line('sites_button_clone');?>
                                        </a>

                                        <a href="#deleteSiteModal" class="btn btn-danger btn-embossed btn-fourth pull-left deleteSiteButton btn-sm last" title="<?php echo $this->lang->line('sites_details_tooltip_delete');?>" data-toggle="tooltip" data-delay='{"show": 2000, "hide": 0}' id="deleteSiteButton" data-siteid="<?php echo $site->sites_id; ?>">
                                            <span class="fui-trash"></span>
                                            <?php //echo $this->lang->line('sites_button_delete'); ?>
                                        </a>

                                    </div>

                                </div><!-- /.siteDetails -->

                            </div><!-- /.site -->

                        <?php endforeach; ?>

                        <div class="site empty"></div>
                        <div class="site empty"></div>
                        <div class="site empty"></div>
                        <div class="site empty"></div>

                    </div><!-- /.masonry -->

                </div><!-- /.col -->

            <?php else : ?>

                <div class="col-md-6 col-md-offset-3">

                    <div class="alert alert-info" style="margin-top: 30px">
                        <button type="button" class="close fui-cross" data-dismiss="alert"></button>
                        <h2><?php echo $this->lang->line('sites_nosites_heading'); ?></h2>
                        <p>
                            <?php echo $this->lang->line('sites_nosites_message'); ?>
                        </p>
                        <br><br>
                        <a href="sites/create" class="btn btn-primary btn-lg btn-wide"><?php echo $this->lang->line('sites_nosites_button_confirm'); ?></a>
                        <a href="#" class="btn btn-default btn-lg btn-wide" data-dismiss="alert"><?php echo $this->lang->line('sites_nosites_button_cancel'); ?></a>
                    </div>

                </div><!-- ./col -->

            <?php endif; ?>

        </div><!-- /.row -->

    </div><!-- /.container -->


    <!-- modals -->

    <?php $this->load->view("shared/modal_sitesettings.php"); ?>

    <?php $this->load->view("shared/modal_account.php"); ?>

    <?php $this->load->view("shared/modal_deletesite.php"); ?>

    <!-- preview popup -->
    <div class="modal fade newSiteModal" id="newSiteModal" tabindex="-1" role="dialog" aria-hidden="TRUE">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="TRUE">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                    <h4 class="modal-title" id="myModalLabel"><span class="fui-window"></span> <?php echo $this->lang->line('sites_modal_newsite_header'); ?></h4>
                </div>

                <div class="modal-body">

                    <ul class="catList" id="ulCatList">
                        <li>
                            <button class="active" data-cat-id="canvas">
                                <?php echo $this->lang->line('sites_modal_newsite_empty');?>
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#bdc3c7"><polygon fill="#bdc3c7" points="4.9,15.7 3.4,14.3 9.7,8 3.4,1.7 4.9,0.3 12.6,8 "></polygon></g></svg>
                            </button>
                        </li>
                        <?php if(isset($templateCategories)):?>
                        <?php foreach( $templateCategories as $category ):?>
                        <li>
                            <button data-cat-id="<?php echo $category['templates_categories_id'];?>">
                                <?php echo $category['category_name'];?>
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#bdc3c7"><polygon fill="#bdc3c7" points="4.9,15.7 3.4,14.3 9.7,8 3.4,1.7 4.9,0.3 12.6,8 "></polygon></g></svg>
                            </button>
                        </li>
                        <?php endforeach;?>
                        <?php endif;?>
                    </ul>

                    <div class="templateWrapper">
                        <div id="divEmptyCanvas" class="divEmptyCanvas">
                            <h2 class="text-center"><?php echo $this->lang->line('sites_modal_newsite_scratch_heading');?></h2>
                            <h3 class="text-center"><?php echo $this->lang->line('sites_modal_newsite_scratch_heading2');?></h3>
                            <img src="<?php echo base_url('img/dnd.png');?>">
                        </div>
                        <ul id="ulTemplateList" class="templateList">
                            <?php if(isset($templates)):?>
                            <?php foreach( $templates as $template ):?>
                            <li data-cat-id="<?php echo ($template->templates_categories_id === NULL)? 0 : $template->templates_categories_id;?>" style="display: none">
                                <a href="" data-template-id="<?php echo $template->pages_id;?>">
                                    <?php if ($template->pagethumb != '') : ?>
                                        <img src="<?php echo $template->pagethumb;?>">
                                    <?php else : ?>
                                        <img src="<?php echo base_url() . "img/nothumb.png";?>">
                                    <?php endif; ?>
                                </a>
                            </li>
                            <?php endforeach;?>
                            <?php endif;?>
                        </ul>
                    </div>

                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal">
                        <span class="fui-cross"></span>
                        <?php echo $this->lang->line('modal_cancelclose'); ?>
                    </button>
                    <a href="<?php echo site_url('sites/create');?>" class="btn btn-primary btn-embossed" id="linkNewSite">
                        <span class="fui-power"></span>
                        <?php echo $this->lang->line('sites_modal_newsite_launch');?>
                    </a>
                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <!-- /modals -->


    <!-- Load JS here for greater good =============================-->
    <?php if (ENVIRONMENT == 'production') : ?>
    <script src="<?php echo base_url('build/sites.bundle.js'); ?>"></script>
    <?php elseif (ENVIRONMENT == 'development') : ?>
    <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/sites.bundle.js"></script>
    <?php endif; ?>

    <!--[if lt IE 10]>
    <script>
    $(function(){
    	var msnry = new Masonry( '#sites', {
	    	// options
	    	itemSelector: '.site',
	    	"gutter": 20
	    });

    })
    </script>
    <![endif]-->
</body>
</html>
