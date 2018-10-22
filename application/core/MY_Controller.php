<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller {
    function __construct()
    {
        parent::__construct();

        $this->load->model(array('settings/Apps_settings_model' => 'MApps'));
        $apps = $this->MApps->get_all();

		foreach ($apps as $app)
		{
			$this->config->set_item($app->name, $app->value);
		}
    }

}