<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sent extends MY_Controller {

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
		'sent/Sentapi_model' => 'MSentAPI',
		'user/Users_model' => 'MUsers'
		];
		$this->load->model($model_list);

		$this->hooks = load_class('Hooks', 'core');
		$this->data = [];

        /** Hook point */
        $this->hooks->call_hook('sent_construct');
	}

	/**
	 * Blank Index
	 */
	public function index()
	{

	}

	/**
	 * Main function to send the email
	 *
	 * @param  	string 	$to
	 * @return  void
	 */
	public function api($to = '', $domain = false)
	{	
		/** Hook point */
        $this->hooks->call_hook('sent_api_pre');

		if ($this->input->post())
		{
			/** No email address or ID? */
			if ($to == '')
			{
				$temp = array();
				$temp['header'] = $this->lang->line('no_email_error_header_error');
				$temp['content'] = $this->lang->line('no_email_error_content');

				if (isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='".$_SERVER['HTTP_REFERER']."' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				$temp['alert_type'] = "error";

				die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
			}

			/** Check if all field is empty or not */
			if ($this->MSentAPI->all_empty($_REQUEST))
			{
				$temp = array();
				$temp['header'] = $this->lang->line('empty_fields_error_header');
				$temp['content'] = $this->lang->line('empty_fields_error_content');

				if (isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				$temp['alert_type'] = "error";

				die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
			}

			/** SPAM honey pot check */
			if (isset($_REQUEST['_honey']) && $_REQUEST['_honey'] != '')
			{
				/** This is not right */
				$temp = array();
				$temp['header'] = $this->lang->line('honey_error_header');
				$temp['content'] = $this->lang->line('honey_error_content');

				if (isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				$temp['alert_type'] = "error";

				die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
			}

			/** Apply xss_clean filter */
			foreach ($_REQUEST as $key=>$value)
			{
				if (substr($key, 0, 1) != "_" && $key != "ci_session" && strpos($key,'wp-') === false)
				{
					// echo $value." => ".$this->security->xss_clean($value)."<br>";
					$_REQUEST[$key] = $this->security->xss_clean($value);
					/** somehow, this is the only way xss filtering works */
					if (isset($_REQUEST['_valid'][$key]))
					{
						$this->form_validation->set_rules($key, $key, "xss_clean|".$_REQUEST['_valid'][$key]);
					}
					else
					{
						$this->form_validation->set_rules($key, $key, "xss_clean");
					}
				}
			}

			if ($this->form_validation->run() === FALSE)
			{
				/** Validation fail */
				$temp = array();
				$temp['header'] = $this->lang->line('validation_error_header');
				$temp['content'] = $this->lang->line('validation_error_content') . validation_errors();

				if (isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				$temp['alert_type'] = "error";

				die( $this->load->view('shared/alert', array('data'=>$temp), TRUE) );
			}

			/** Do we have a file upload to take care off? */
			if (isset($_FILES['file']) && $_FILES['file']['name'] != '')
			{
				if ( ! $this->upload->do_upload("file"))
				{
					$temp = array();
					$temp['header'] = $this->lang->line('file_error_header');
					$temp['content'] = $this->lang->line('file_error_content') . $this->upload->display_errors();

					if (isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] != '')
					{
						$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
					}

					$temp['alert_type'] = "error";

					die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
				}
				else
				{
					$fileData = $this->upload->data();
					/** Setup the attachment */
					$this->email->attach( $fileData['full_path'] );
					/** Delete or save attachement */
					$removeAttachment = $fileData['full_path'];
				}
			}

			/** Have any cc? */
			if (isset($_REQUEST['cc']))
			{
				// print_r( $_REQUEST['cc'] );
				$this->email->cc($_REQUEST['cc']);
			}

			/** Have any bcc? */
			if (isset($_REQUEST['bcc']))
			{
				// print_r( $_REQUEST['bcc'] );
				$this->email->bcc($_REQUEST['bcc']);
			}



			/** Set email from address and name */
			if ( $this->input->post('hiddenInputSiteID') != '' )
			{ // Use the email and name belonging to the site owner
				$user = $this->MUsers->get_by_site_id($this->input->post('hiddenInputSiteID'));
				$this->email->from($user['email'], $user['first_name'] . " " . $user['last_name']);
			}
			else
			{ //  Use the default email and name
				$this->email->from($this->config->item('sent_email_from_address'), $this->config->item('sent_email_from_name'));
			}

			/** Set email send to address */
			$this->email->to($to);

			/** Set email subject */
			if (isset($_REQUEST['_subject']) && $_REQUEST['_subject'] != '')
			{
				$this->email->subject($_REQUEST['_subject']);
			}
			else
			{
				$this->email->subject($this->config->item('sent_email_default_subject'));
			}

			/** Set reply to email */
			if (isset($_REQUEST['_replyto']) && $_REQUEST['_replyto'] != '')
			{
				if (substr($_REQUEST['_replyto'], 0, 1) == "%")
				{
					$replyTo = ltrim($_REQUEST['_replyto'],'%');
					if (isset($_REQUEST[$replyTo]) && $_REQUEST[$replyTo] != '')
					{
						$this->email->reply_to($_REQUEST[$replyTo]);
					}
					else
					{
						$this->email->reply_to($_REQUEST['_replyto']);
					}
				}
				else
				{
					$this->email->reply_to($_REQUEST['_replyto']);
				}
			}

			/** Set email body by mail type */
			if( $this->email->mailtype == 'html' )
			{
				$this->email->message($this->load->view('sent/email_default', $_REQUEST, TRUE));
			}
			else
			{
				$this->email->message($this->load->view('sent/email_text', $_REQUEST, TRUE));
			}

			/** Send email */
			$this->email->send();

			/** Remove attachment if there is any */
			if (isset($removeAttachment))
			{
				unlink($removeAttachment);
			}

			/** Redirect after sending email */
			if (isset($_REQUEST['_after']) && $_REQUEST['_after'] != '')
			{
				if (filter_var($_REQUEST['_after'], FILTER_VALIDATE_URL))
				{
					redirect($_REQUEST['_after'], 'location');
				}
				else
				{
					/** Fail */
					$temp = array();
					$temp['header'] = $this->lang->line('after_error_header');
					$temp['content'] = $this->lang->line('after_error_content') . $_REQUEST['_after'];

					$temp['alert_type'] = "error";

					if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '')
					{
						$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
					}

					die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
				}
			}
			else
			{
				/** No redirection given, display confirmation message */
				$temp = array();
				$temp['header'] = $this->lang->line('success_header');
				if (isset($_REQUEST['_confirmation']) && $_REQUEST['_confirmation'] != '')
				{
					$temp['content'] = "<small>" . $_REQUEST['_confirmation'] . "</small>";
				}
				else
				{
					/** Confirmation message (commented as we dont implemented database yet) */
					// $temp['error_message'] = "<small>" . $this->config->item('email_confirmation_message') . "</small>";
					$temp['content'] = $this->lang->line('success_content');
				}
				if (isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] != '')
				{
					$temp['content'] .= "<br><a href='" . $_SERVER['HTTP_REFERER'] . "' class='btn btn-primary btn-block'><span class='fui-arrow-left'></span> ".$this->lang->line('error_button_go_back')."</a>";
				}

				$temp['alert_type'] = "success";
				$this->session->set_flashdata('message', $temp);

				/** Hook point */
        		$this->hooks->call_hook('sent_api_post');

        		$this->load->helper('general');

        		if ( $domain )
        		{
        			redirect("//" . $domain . "/sent/msg");
        		}
        		else
        		{
        			redirect(site_url("sent/msg"));
        		}
				
			}
		}
	}

	/**
	 * Show message
	 *
	 * @return void
	 */
	public function msg()
	{
		/** Hook point */
        $this->hooks->call_hook('sent_msg_pre');

		if ($this->session->flashdata('message') != '')
		{
			echo $this->load->view('shared/alert', array('data'=>$this->session->flashdata('message')), TRUE);
		}
		else
		{
			$temp = array();
			$temp['header'] = $this->lang->line('error_header');
			$temp['content'] = $this->lang->line('error_content');

			$temp['alert_type'] = "error";

			/** Hook point */
        	$this->hooks->call_hook('sent_msg_post');

			die($this->load->view('shared/alert', array('data'=>$temp), TRUE));
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
        $this->hooks->call_hook('sent_destruct');
    }

}