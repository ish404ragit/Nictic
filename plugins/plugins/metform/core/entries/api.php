<?php

namespace MetForm\Core\Entries;

use MetForm\Core\Integrations\Get_Response;
use MetForm\Core\Integrations\Mail_Chimp;

defined('ABSPATH') || exit;

class Api extends \MetForm\Base\Api
{

    public function config()
    {
        $this->prefix = 'entries';
        $this->param = "/(?P<id>\w+)";
    }

    public function post_insert()
    {
        $url = wp_get_referer();
        $post_id = url_to_postid($url);
        $post_id;

        $id = $this->request['id'];

        $form_data = $this->request->get_params();

        $file_data = $this->request->get_file_params();

        return Action::instance()->submit($id, $form_data, $file_data,$post_id);
    }

    public function get_export()
    {
        if(!current_user_can('manage_options')) {
			return;
		}

        $id = $this->request['id'];

        return Export::instance()->export_data($id);
    }

    public function get_get_response_list_id()
    {
        if(!current_user_can('manage_options')) {
			return;
		}

        $post_id = $this->request['id'];
        return get_option('wpmet_get_response_list_' . $post_id);
    }

    public function get_paypal()
    {

        $args = [
            'method' => (isset($this->request['action']) ? $this->request['action'] : ''),
            'action' => (isset($this->request['id']) ? $this->request['id'] : ''),
            'entry_id' => (isset($this->request['entry_id']) ? $this->request['entry_id'] : ''),
        ];

        if (class_exists('\MetForm_Pro\Core\Integrations\Payment\Paypal')) {
            return \MetForm_Pro\Core\Integrations\Payment\Paypal::instance()->init($args, $this->request);
        }
        return 'Pro needed';
    }

    public function get_stripe()
    {
        $args = [
            'method' => (isset($this->request['action']) ? $this->request['action'] : ''),
            'action' => (isset($this->request['id']) ? $this->request['id'] : ''),
            'entry_id' => (isset($this->request['entry_id']) ? $this->request['entry_id'] : ''),
            'token' => (isset($this->request['token']) ? $this->request['token'] : ''),
        ];
        if (class_exists('\MetForm_Pro\Core\Integrations\Payment\Stripe')) {
            return \MetForm_Pro\Core\Integrations\Payment\Stripe::instance()->init($args);
        }
        return 'Pro needed';
    }

    public function get_views()
    {
        return $this->request->get_params();
    }

    public function get_get_response_list()
    {
        if(!current_user_can('manage_options')) {
			return;
		}

        $post_id = $this->request['id'];
        return get_option('wpmet_get_response_list_' . $post_id);
    }

    public function get_store_get_response_list()
    {
        if(!current_user_can('manage_options')) {
			return;
		}

        if (class_exists('\MetForm_Pro\Core\Integrations\Email\Getresponse\Get_Response')) {

            $post_id = $this->request['id'];
            $data = \MetForm\Core\Forms\Action::instance()->get_all_data($post_id);
            $api_key = isset($data['mf_get_reponse_api_key']) ? $data['mf_get_reponse_api_key'] : null;

            $get_response_list = \MetForm_Pro\Core\Integrations\Email\Getresponse\Get_Response::get_list($api_key);

            delete_option('wpmet_get_response_list_' . $post_id, $get_response_list);
            update_option('wpmet_get_response_list_' . $post_id, $get_response_list);

            return get_option('wpmet_get_response_list_' . $post_id);
        }

        return 'error';
    }

    public function get_get_mailchimp_list()
    {
        if(!current_user_can('manage_options')) {
			return;
		}
        $post_id = $this->request['id'];
        return get_option('wpmet_get_mailchimp_list_' . $post_id);
    }

    public function get_store_mailchimp_list()
    {
        $nonce = $this->request->get_header('X-WP-Nonce');

        if(!current_user_can('manage_options')) {
			return;
		}


        if(!wp_verify_nonce($nonce, 'wp_rest')) {
            return [
				'status'    => 'fail',
				'message'   => [  __( 'Nonce mismatch.', 'metform' ) ],
			];
        }

        $post_id = $this->request['id'];
        $data = \MetForm\Core\Forms\Action::instance()->get_all_data($post_id);
        $api_key = $data['mf_mailchimp_api_key'];
        
        if (!preg_match('/^[a-z0-9]{32}-[a-z0-9]{3,4}$/', $api_key)) {
            return [
				'status'    => 'fail',
				'message'   => [  __( 'Invalid_api_key.', 'metform' ) ],
			];
        }

        $mailChimp_list = json_decode(Mail_Chimp::get_list($api_key)['body']);

        delete_option('wpmet_get_mailchimp_list_' . $post_id, $mailChimp_list);
        update_option('wpmet_get_mailchimp_list_' . $post_id, $mailChimp_list);

        return get_option('wpmet_get_mailchimp_list_' . $post_id, $mailChimp_list);
    }

    public function get_google_spreadsheet_list()
    {
        if(!current_user_can('manage_options')) {
			return;
		}

        if (!class_exists('\MetForm_Pro\Core\Integrations\Google_Sheet\WF_Google_Sheet')) {
            
            return 'Pro needed';
        }

        $google      = new \MetForm_Pro\Core\Integrations\Google_Sheet\WF_Google_Sheet;
        $response = $google->get_all_spreadsheets();
        return $response ;
    }

    public function get_google_sheet_list()
    {
        if(!current_user_can('manage_options')) {
			return;
		}

        if (!class_exists('\MetForm_Pro\Core\Integrations\Google_Sheet\WF_Google_Sheet')) {
            
            return 'Pro needed';
        }

        // $spreadsheetID = $this->request['spreadsheetID'];
        $sheetID = $this->request['sheetID'];


        $google      = new \MetForm_Pro\Core\Integrations\Google_Sheet\WF_Google_Sheet;
        $response = $google->get_sheets_details_from_spreadsheet($sheetID);
        return $response ;
    }
		


}
