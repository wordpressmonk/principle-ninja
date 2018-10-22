<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

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

        /** Hook point */
        $this->hooks->call_hook('home_construct');

        //$this->output->enable_profiler(TRUE);
    }

    /**
     * Generate the site
     *
     * @param  string   $page
     * @return void
     */
    public function index($page = NULL)
    {
        /** Hook point */
        $this->hooks->call_hook('home_index_pre');

        $home = $this->MSites->get_by_field_value('home_page', 1);
        if (count($home) > 0)
        {
            $server_scheme = server_scheme();
            $req_host = $_SERVER['HTTP_HOST'];
            $base_url = parse_url(base_url());
            $app_host = $base_url['host'];
            $sub = explode('.', $req_host);

            $site_content = $this->MSites->get_by_field_value('home_page', 1);
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
                $page_content = $this->MPages->load_page($page->pages_id);
                /** Fix relative path */
                $base_url = '<base href="' . base_url() . 'elements/">';
                $render_page = str_replace('<!--baseURL-->', $base_url, $page_content);
                /** Add meta info */
                $meta = '';
                $meta .= '<title>' . $page->pages_title . '</title>' . "\n";
                $meta .= '<meta name="keywords" content="' . $page->pages_meta_keywords . '">' . "\n";
                $meta .= '<meta name="description" content="' . $page->pages_meta_description . '">' . "\n";
                $render_page = str_replace('<!--pageMeta-->', $meta, $render_page);
                /** Add other header */
                $header = '';
                $header .= $page->pages_header_includes . "\n";

                if ($site_content[0]['global_css'] != '')
                {
                    $header .= "\n<style>" . $site_content[0]['global_css'] . "</style>\n";
                }
                if ($page->pages_css != '')
                {
                    $header .= "\n<style>" . $page->pages_css . "</style>\n";
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
                    if (substr($element->href, 0, 1) !== '#' && ! $element->hasAttribute('data-toggle') && ! strpos($element->href, '//'))
                    {
                        $element->href = $server_scheme . '://' . $req_host . '/home/' . $element->href;
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
                $this->hooks->call_hook('home_index_post');

                echo $raw;
            }
            else
            {
                /** Hook point */
                $this->hooks->call_hook('home_index_error');

                show_404();
            }
        }
        else
        {
            redirect('auth', 'refresh');
        }
    }

}