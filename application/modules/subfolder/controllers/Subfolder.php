<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subfolder extends MY_Controller {

	/**
     * Class constructor
     *
     * Loads required models, loads the hook class and add a hook point
     *
     * @return  void
     */
	public function __construct()
	{
		parent::__construct();
		$model_list = [
			'sites/Sites_model' => 'MSites',
			'sites/Pages_model' => 'MPages',
			'shared/Revision_model' => 'MRevisions',
			'user/Users_model' => 'MUsers',
		];
		$this->load->model($model_list);

		$this->hooks = load_class('Hooks', 'core');
        $this->data = [];

		/** Hook point */
		$this->hooks->call_hook('subfolder_construct');
	}

	/**
	 * Generate the site
	 *
	 * @param  string 	$site
	 * @param  string 	$page
	 * @return void
	 */
	public function index($site, $page = NULL)
	{
		/** Hook point */
		$this->hooks->call_hook('subfolder_index_pre');

		// Force trialing "/"
		if ( $page === NULL ) { // Only when we're loading the homepage, without specifying "index.html"
			if ( substr($_SERVER['REQUEST_URI'], -1) !== '/' )
			{
				if ( substr(site_url($site), -1) !== '/' )
			    {
			        redirect( site_url($site) . '/' );
			    }
			    else
			    {
			        redirect( site_url($site) );   
			    }
			}
		}

		// force .html
		if ( $page != NULL && substr_compare($page, ".html", strlen($page)-strlen(".html"), strlen(".html")) !== 0 && strpos($page, '.') === false )
		{
			redirect( $this->config->item('base_url') . $site . '/' . $page.'.html' );
		}

		// if $page resembles anything other then something.html, show 404
		if ( $page != NULL && preg_match('/^[A-Za-z0-9_-]*\.(html)$/i', $page, $matches, PREG_OFFSET_CAPTURE) === 0 )
		{
			show_404();
		}

		$site_content = $this->MSites->get_by_field_value('sub_folder', $site);
		// die(print_r($site_content));
		if (count($site_content) > 0)
		{
			/** If there is no page value then its home page */
			if ( ! $page)
			{
				$page = 'index';
			}
			else
			{
				$page_arr = explode(".", $page);
				$page = $page_arr[0];
			}

			$page = $this->MPages->getSinglePage($site_content[0]['sites_id'], $page);
			if (!$page)
			{
				show_404();
			}
			//get the user_id which has to be passed with the API to get user data
			$user_id = $this->MSites->get_user_id($page->sites_id);
			//Call API to get the required values for the variables
			$cSession = curl_init(); 
			//step2
			curl_setopt($cSession,CURLOPT_URL,"http://localhost/FirstApp/firstRestAPI.php?id=".$user_id);
			curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($cSession,CURLOPT_HEADER, false); 
			//step3
			$result=curl_exec($cSession);
			//step4
			curl_close($cSession);
			//step5
			$page_content = $this->MPages->load_page($page->pages_id);
			$render_page = $page_content;

			/** Add meta info */
			$meta = '';
			$meta .= '<title>' . $page->pages_title . '</title>' . "\n";
			$meta .= '<meta name="keywords" content="' . $page->pages_meta_keywords . '">' . "\n";
			$meta .= '<meta name="description" content="' . $page->pages_meta_description . '">' . "\n";
			$render_page = str_replace('<!--pageMeta-->', $meta, $render_page);
			/** Add other header */
			$header = '';
			$header .= $page->pages_header_includes . "\n";

			/** Deal with global CSS and page CSS **/
			$custom_css = "";

			if ($site_content[0]['global_css'] != '')
			{
				$custom_css .= $site_content[0]['global_css']."\n";
			}

			if ($page->pages_css != '')
			{
				$custom_css .= $page->pages_css;
			}

			if ( $custom_css !== '' )
			{
				$render_page = str_replace("</head>", "\n<style>\n" . $custom_css . "\n</style>\n</head>", $render_page);
			}


			// Google fonts
			if ( $page->google_fonts !== '' && $page->google_fonts !== '[]' ) {
			
				$googleFonts = json_decode($page->google_fonts);
				$apiString = $this->config->item('google_font_api');

				foreach( $googleFonts as $font ) {

					$apiString .= $font->api_entry;
					$apiString .= '|';

				}

				$apiString = '<link href="' . $apiString . '" rel="stylesheet" type="text/css">';

				$render_page = str_replace("</head>", $apiString . "\n</head>", $render_page);

			}
			

			$render_page = str_replace('<!--headerIncludes-->', $header, $render_page);

			/** Load html with Simple HTML DOM */
			$this->load->library('Simple_html_dom');
			$raw = str_get_html($render_page, true, true, DEFAULT_TARGET_CHARSET, false);
			if (empty($raw))
			{
				show_404();
			}
			/** Fix the menu link */
			foreach ($raw->find('a') as $element)
			{
				if ( substr($element->href, 0, 1) !== '#' && !$element->hasAttribute('data-toggle') && !strpos($element->href, '//') && strpos($element->href, 'mailto:') === false && strpos($element->href, 'tel:') )
				{
					$element->href = '../' . $site . '/' . $element->href;
				}
			}

			/** Strip out video overlays */
			foreach ($raw->find('.frameCover') as $element)
			{
				$element->outertext = "";
			}

			/** Custom header to deal with XSS protection */
			header("X-XSS-Protection: 0");

			/** Hook point */
			$this->hooks->call_hook('subfolder_index_post');
			//Whenever a new variable is created, it should be added to the below array
			$from = array('#ProspectFirstName','#ProspectLastName','#ProspectEmail','#ProspectPhone'
				,'#MyId','#MyFirstName','#MyLastName','#MyPhone','#MyEmail','#MyAddress','#MyCity','#MyState'
				,'#MyZip','#MyCompanyName','#MyPicture','#dateNow');
			$result = (json_decode($result,true));

			$to = array($result['data']['prospectFirstName'],$result['data']['prospectLastName'],$result['data']['ProspectEmail'],$result['data']['ProspectPhone'],$result['data']['MyID'],$result['data']['MyFirstName'],$result['data']['MyLastName'],$result['data']['MyPhone'],$result['data']['MyEmailLink'],$result['data']['MyAddress'],$result['data']['MyCity'],$result['data']['MyState'],$result['data']['MyZip'],$result['data']['MyCompanyName'],$result['data']['MyPicture'],$result['data']['dateNow']);

			echo str_replace($from, $to, $raw);
		}
		else
		{
			/** Hook point */
			$this->hooks->call_hook('subfolder_index_error');

			show_404();
		}
	}

	/**
     * Controller desctruct method for custom hook point
     *
     * @return void
     */
	public function __destruct()
	{
		/** Hook point */
		$this->hooks->call_hook('subfolder_destruct');
	}

}