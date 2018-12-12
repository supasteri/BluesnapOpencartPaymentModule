<?php
class ControllerExtensionPaymentBluesnap extends Controller {
	private $error = array();
	
	public function __construct($registry) {
		 parent::__construct($registry);
	        $this->registry = $registry;
		require_once(DIR_SYSTEM . "library/payment/bluesnap.php");
		$this->bluesnap = new Bluesnap($registry);
        }

	public function install() {
                $this->load->model('extension/payment/bluesnap');
                $this->model_extension_payment_bluesnap->install();
        }

	public function verify_settings() {
		$mode = $this->request->post['mode'];
		$sandbox_mode_enabled = $this->request->post['mode'] == 'sandbox' ? 1 : 0;
		$username = $this->request->post['username'];
		$password = $this->request->post['password'];
		$this->language->load('extension/payment/bluesnap');	
		try { 
			$this->bluesnap->set_sandbox_mode_enabled($sandbox_mode_enabled);
			$this->bluesnap->set_username($username);
			$this->bluesnap->set_password($password);
			$this->bluesnap->set_debug_enabled(1);
			$token = $this->bluesnap->get_payment_field_token();
			$token_expiry	= $token['EXPIRY_DATE_TIME'];
			$token_key	= $token['TOKEN'];
			$result_msg = sprintf($this->language->get('text_verification_success'), $mode, $token_key, $token_expiry);
			$result_code = 0;
		} catch (Exception $e) { 
			$token = null;
			$result_code = 1;
			$result_msg = sprintf($this->language->get('text_verification_failure'), $mode, $e->getMessage());
		}
                $json = array('result_code' => $result_code, 'result_msg' => $result_msg, 'user_token' => $token);
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));

	}

	public function index() {
		$this->load->language('extension/payment/bluesnap');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		$settingdata= $this->model_setting_setting->getSetting('payment_bluesnap');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(!empty($settingdata)){
				if($this->request->post['payment_bluesnap_mode']=='production'){
						$this->request->post['payment_bluesnap_production_username']=$this->request->post['payment_bluesnap_username'];
						$this->request->post['payment_bluesnap_production_password']=$this->request->post['payment_bluesnap_password'];
						$this->request->post['payment_bluesnap_username']=$settingdata['payment_bluesnap_username'];
						$this->request->post['payment_bluesnap_password']=$settingdata['payment_bluesnap_password'];
				}else{
						$this->request->post['payment_bluesnap_production_username']=$settingdata['payment_bluesnap_production_username'];
						$this->request->post['payment_bluesnap_production_password']=$settingdata['payment_bluesnap_production_password'];
				}
			}
			$this->model_setting_setting->editSetting('payment_bluesnap', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		$data['settingdata']			= $settingdata;
		$data['user_token']				= $this->session->data['user_token'];
		$data['heading_title'] 			= $this->language->get('heading_title');
		$data['text_welcome']			= $this->language->get('text_welcome');
		$data['button_verify_settings']		= $this->language->get('button_verify_settings');
		$data['text_yes']			= $this->language->get('text_yes');
	        $data['text_no']                       	= $this->language->get('text_no');
		$data['text_edit'] 			= $this->language->get('text_edit');
		$data['text_enabled'] 			= $this->language->get('text_enabled');
		$data['text_disabled'] 			= $this->language->get('text_disabled');
		$data['text_all_zones']			= $this->language->get('text_all_zones');
		$data['entry_mode']			= $this->language->get('entry_mode');
		$data['entry_server_ip']		= $this->language->get('entry_server_ip');
                $data['help_server_ip']                = $this->language->get('help_server_ip');

		$data['entry_username']			= $this->language->get('entry_username');
		$data['help_username']			= $this->language->get('help_username');

		$data['entry_description_prefix']       = $this->language->get('entry_description_prefix');
                $data['help_description_prefix']        = sprintf($this->language->get('help_description_prefix'), $this->config->get('config_name'));

		
		$data['entry_password']    		= $this->language->get('entry_password');
		$data['help_password']                  = $this->language->get('help_password');
		$data['entry_debug_enabled']		= $this->language->get('entry_debug_enabled');
		$data['entry_total'] 			= $this->language->get('entry_total');
		$data['entry_order_status'] 		= $this->language->get('entry_order_status');
		$data['entry_geo_zone'] 		= $this->language->get('entry_geo_zone');
		$data['entry_status'] 			= $this->language->get('entry_status');
		$data['entry_sort_order']		= $this->language->get('entry_sort_order');

		$data['help_callback'] 			= $this->language->get('help_callback');
		$data['help_total'] 			= $this->language->get('help_total');

		$data['button_save'] 			= $this->language->get('button_save');
		$data['button_cancel'] 			= $this->language->get('button_cancel');

		$data['text_mode_production']		= $this->language->get('text_mode_production');
		$data['text_mode_sandbox']		= $this->language->get('text_mode_sandbox');
		$data['button_show_error_log']          = $this->language->get('button_show_error_log');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['error_warning'])) { 
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
	
		if (isset($this->error['description_prefix'])) {
                        $data['error_description_prefix'] = $this->error['description_prefix'];
                } else {
                        $data['error_description_prefix'] = '';
                }

		if (isset($this->error['username'])) {
                        $data['error_username'] = $this->error['username'];
                } else {
                        $data['error_username'] = '';
                }

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['debug_enabled'])) {
                        $data['error_debug_enabled'] = $this->error['debug_enabled'];
                } else {
                        $data['error_debug_enabled'] = '';
                }


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['action'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL');

		if (isset($this->request->post['payment_bluesnap_username'])) {
			$data['payment_bluesnap_username'] = $this->request->post['payment_bluesnap_username'];
		} else {
			if($this->config->get('payment_bluesnap_mode')=="production"){
        			$data['payment_bluesnap_username'] = $this->config->get('payment_bluesnap_production_username');
			}else{
        			$data['payment_bluesnap_username'] = $this->config->get('payment_bluesnap_username');
			}
			//$data['payment_bluesnap_username'] = $this->config->get('payment_bluesnap_username');
		}
		
		// print_r($_SERVER);

		$hostname = $_SERVER['SERVER_NAME'];	
		$public_ip = gethostbyname($_SERVER['SERVER_NAME']);
		$internal_ip = $_SERVER['SERVER_ADDR'];
	
		$data['payment_bluesnap_server_ip'] =  "[Hostname:$hostname] [Public IP:$public_ip] [Internal IP: $internal_ip]";

		if (isset($this->request->post['payment_bluesnap_password'])) {
				$data['payment_bluesnap_password'] = $this->request->post['payment_bluesnap_password'];
		} else {
			if($this->config->get('payment_bluesnap_mode')=="production"){
        			$data['payment_bluesnap_password'] = $this->config->get('payment_bluesnap_production_password');
			}else{
        			$data['payment_bluesnap_password'] = $this->config->get('payment_bluesnap_password');
			}
			//$data['payment_bluesnap_password'] = $this->config->get('payment_bluesnap_password');
		}
	

		if (isset($this->request->post['payment_bluesnap_description_prefix'])) {
				$data['payment_bluesnap_description_prefix'] = $this->request->post['payment_bluesnap_description_prefix'];
		} else if (strlen($this->config->get('payment_bluesnap_description_prefix')) > 0) {
				$data['payment_bluesnap_description_prefix'] = $this->config->get('payment_bluesnap_description_prefix');
		} else {
			$data['payment_bluesnap_description_prefix'] = $this->config->get('config_name');
		}

		if (isset($this->request->post['payment_bluesnap_debug_enabled'])) {
				$data['payment_bluesnap_debug_enabled'] = $this->request->post['payment_bluesnap_debug_enabled'];
		} else {
				$data['payment_bluesnap_debug_enabled'] = $this->config->get('payment_bluesnap_debug_enabled');
		}

		if (isset($this->request->post['payment_bluesnap_mode'])) {
				$data['payment_bluesnap_mode'] = $this->request->post['payment_bluesnap_mode'];
		} else {
				$data['payment_bluesnap_mode'] = $this->config->get('payment_bluesnap_mode');
		}

		
		if (isset($this->request->post['payment_bluesnap_total'])) {
			$data['payment_bluesnap_total'] = $this->request->post['payment_bluesnap_total'];
		} else {
			$data['payment_bluesnap_total'] = $this->config->get('payment_bluesnap_total');
		}

		if (isset($this->request->post['payment_bluesnap_order_status_id'])) {
			$data['payment_bluesnap_order_status_id'] = $this->request->post['payment_bluesnap_order_status_id'];
		} else {
			$data['payment_bluesnap_order_status_id'] = $this->config->get('payment_bluesnap_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_bluesnap_geo_zone_id'])) {
			$data['payment_bluesnap_geo_zone_id'] = $this->request->post['payment_bluesnap_geo_zone_id'];
		} else {
			$data['payment_bluesnap_geo_zone_id'] = $this->config->get('payment_bluesnap_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_bluesnap_status'])) {
			$data['payment_bluesnap_status'] = $this->request->post['payment_bluesnap_status'];
		} else {
			$data['payment_bluesnap_status'] = $this->config->get('payment_bluesnap_status');
		}

		if (isset($this->request->post['payment_bluesnap_sort_order'])) {
			$data['payment_bluesnap_sort_order'] = $this->request->post['payment_bluesnap_sort_order'];
		} else {
			$data['payment_bluesnap_sort_order'] = $this->config->get('payment_bluesnap_sort_order');
		}

		$this->getList($data);

		$this->getAuditTrail($data);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/bluesnap', $data));
	}

	public function get_audit_entry() {
		$this->load->model('extension/payment/bluesnap');
		$result_code = 0;
		$result_msg = '';
		$bluesnap_audit_record = null;
		if (!isset($this->request->post['bluesnap_audit_id'])) {
			$result_code = 1;
			$result_msg = $this->language->get('error_bluesnap_audit_id_required');
		} else {
			$bluesnap_audit_id = $this->request->post['bluesnap_audit_id'];
			$bluesnap_audit = $this->model_extension_payment_bluesnap->get_entry($bluesnap_audit_id);
			if (!isset($bluesnap_audit['bluesnap_audit_id'])) {
				$result_code = 1;
				$result_msg = $this->language->get('error_bluesnap_audit_id_not_found');
			} else {
				$bluesnap_audit_record = $bluesnap_audit;
			}
		}
		
                $json = array('result_code' => $result_code, 'result_msg' => $result_msg);
		if ($bluesnap_audit_record != null)
			$json['bluesnap_audit_record'] = $bluesnap_audit_record;
		$this->response->setOutput(json_encode($json));

                $this->response->addHeader('Content-Type: application/json');
	}

	public function orderAction() {
		$this->language->load('extension/payment/bluesnap');
		$data['text_payment_info'] = $this->language->get('text_payment_info');
		$data['text_audit_entry_title'] = $this->language->get('text_audit_entry_title');
		$this->load->model('extension/payment/bluesnap');
		$this->load->language('extension/payment/bluesnap_order');

		$criteria =  array (
			'filter_result_code' => '',
			'sort'=>'date_added', 'order' => 'DESC', 'filter_order_id' => $this->request->get['order_id']
		); 
		
		$bluesnap_audit_entries = $this->model_extension_payment_bluesnap->get_entries($criteria);
		if (sizeof($bluesnap_audit_entries) > 0) {
			$data['bluesnap_audit_entries'] = $bluesnap_audit_entries;
			
			return $this->load->view('extension/payment/bluesnap_order', $data);
		}
	}

	protected function getAuditTrail(&$data) {
		$audit=array();
		$audit['button_modal_popup_close'] = $this->language->get('button_modal_popup_close');
                $audit['modal_hosted_popup_title'] = $this->language->get('modal_hosted_popup_title');

                $audit['button_view'] = $this->language->get('button_view');
                if (isset($this->request->get['filter_order_id'])) {
                        $filter_order_id = $this->request->get['filter_order_id'];
                } else {
                        $filter_order_id = null;
                }

                //if (isset($this->request->get['filter_total'])) {
                        //$filter_total = $this->request->get['filter_total'];
                //} else {
                        //$filter_total = null;
                //}

                if (isset($this->request->get['filter_date_added'])) {
                        $filter_date_added = $this->request->get['filter_date_added'];
                } else {
                        $filter_date_added = null;
                }
		
		if (isset($this->request->get['filter_result_code'])) {
                        $filter_result_code = $this->request->get['filter_result_code'];
                } else {
                        $filter_result_code = null;
                }

                if (isset($this->request->get['filter_remote_ip'])) {
                        $filter_remote_ip = $this->request->get['filter_remote_ip'];
                } else {
                        $filter_remote_ip = null;
                }


                if (isset($this->request->get['sort'])) {
                        $sort = $this->request->get['sort'];
                } else {
                        $sort = 'order_id';
                }

                if (isset($this->request->get['hostedorder'])) {
                        $order = $this->request->get['hostedorder'];
                } else {
                        $order = 'DESC';
                }

                if (isset($this->request->get['hostedpage'])) {
                        $page = $this->request->get['hostedpage'];
                } else {
                        $page = 1;
                }

		 $url = '';

                if (isset($this->request->get['filter_order_id'])) {
                        $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
                }

                //if (isset($this->request->get['filter_total'])) {
                        //$url .= '&filter_total=' . $this->request->get['filter_total'];
                //}

                if (isset($this->request->get['filter_date_added'])) {
                        $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
                }

                if (isset($this->request->get['filter_result_code'])) {
                        $url .= '&filter_result_code=' . $this->request->get['filter_result_code'];
                }

                if (isset($this->request->get['filter_remote_ip'])) {
                        $url .= '&filter_remote_ip=' . $this->request->get['filter_remote_ip'];
                }

                if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['hostedorder'])) {
                        $url .= '&hostedorder=' . $this->request->get['hostedorder'];
                }

                if (isset($this->request->get['hostedpage'])) {
                        $url .= '&hostedpage=' . $this->request->get['hostedpage'];
                }

                $this->load->model('extension/payment/bluesnap');
                $audit['entries'] = array();

		$filter_data = array(
                        'filter_order_id'       => $filter_order_id,
                        //'filter_total'          => $filter_total,
                        'filter_date_added'     => $filter_date_added,
                        'filter_result_code'    => $filter_result_code,
                        'filter_remote_ip'      => $filter_remote_ip,
                        'sort'                  => $sort,
                        'order'                 => $order,
                        'start'                 => ($page - 1) * $this->config->get('config_limit_admin'),
                        'limit'                 => $this->config->get('config_limit_admin')
                );

                $entries_total = $this->model_extension_payment_bluesnap->get_audit_total_entries($filter_data);//echo '11<pre>';print_r($entries_total);exit;

                $results = $this->model_extension_payment_bluesnap->get_audit_entries($filter_data);//echo '11<pre>';print_r($results);exit;
                $audit['entries'] = array();
                foreach ($results as $result) {
                        $audit['entries'][] = array(
                                'bluesnap_audit_id'     => $result['bluesnap_hosted_fields_audit_id'],
                                'order_id'              => $result['order_id'],
                                'status'                => $result['result_code'] == 0 ? "SUCCESS" : "FAILURE",
                                //'total'                 => $result['currency'] . ' ' . $result['amount'],
                                'date_added'            => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                                'remote_ip'             => $result['remote_ip'],
                                'view'                  => $this->url->link('extension/payment/bluesnap/get_hosted_audit_entry', 'user_token=' . $this->session->data['user_token'] . '&bluesnap_hosted_fields_audit_id=' . $result['bluesnap_hosted_fields_audit_id'], 'SSL'),
                        );
                }
		$audit['column_order_id']        = $this->language->get('column_order_id');
		$audit['column_trace_id']        = $this->language->get('column_trace_id');
                $audit['column_outcome']         = $this->language->get('column_outcome');
                $audit['column_total']           = $this->language->get('column_total');
                $audit['column_date_added']      = $this->language->get('column_date_added');
                $audit['column_remote_ip']       = $this->language->get('column_remote_ip');
                $audit['column_action']          = $this->language->get('column_action');

                $audit['entry_order_id'] = $this->language->get('entry_order_id');
		 $audit['entry_trace_id'] = $this->language->get('entry_trace_id');
                $audit['entry_outcome'] = $this->language->get('entry_outcome');
                $audit['entry_total'] = $this->language->get('entry_total');
                $audit['entry_date_added'] = $this->language->get('entry_date_added');
                $audit['entry_remote_ip'] = $this->language->get('entry_remote_ip');
                $audit['text_result_code_success'] = $this->language->get('text_result_code_success');
                $audit['text_result_code_failure'] = $this->language->get('text_result_code_failure');
                $audit['button_filter']  = $this->language->get('button_filter');
                $audit['user_token'] = $this->session->data['user_token'];

                $url = '';

                if (isset($this->request->get['filter_order_id'])) {
                       $url .= "&filter_order_id=" . $this->request->get['filter_order_id'];
                }

                //if (isset($this->request->get['filter_total'])) {
                        //$url .= "&filter_total=" . $this->request->get['filter_total'];
                //}

                if (isset($this->request->get['filter_date_added'])) {
                        $url .= "&filter_date_added=" . $this->request->get['filter_date_added'];
                }

                if (isset($this->request->get['filter_result_code'])) {
                        $url .= "&filter_result_code=" . $this->request->get['filter_result_code'];
                }

                if (isset($this->request->get['filter_remote_ip'])) {
                        $url .= "&filter_remote_ip=".$this->request->get['filter_remote_ip'];
                }
		
		if ($order == 'ASC') {
                        $url .= '&hostedorder=DESC';
                } else {
                        $url .= '&hostedorder=ASC';
                }

                if (isset($this->request->get['hostedpage'])) {
                        $url .= '&hostedpage=' . $this->request->get['hostedpage'];
                }

                $audit['text_no_results'] = $this->language->get('text_no_results');
                $audit['sort_order'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . '&sort=order_id' . $url, 'SSL');
                $audit['sort_outcome'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . '&sort=result_code' . $url, 'SSL');
                //$audit['sort_total'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . '&sort=amount' . $url, 'SSL');
                $audit['sort_date_added'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url, 'SSL');
                $audit['sort_remote_ip'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . '&sort=remote_ip' . $url, 'SSL');


                $audit['sort'] = $sort;
                $audit['order'] = $order;
                $audit['entries_total'] = $entries_total;

                $pagination = new Pagination();
                $pagination->total = $entries_total;
                $pagination->page = $page;
                $pagination->limit = $this->config->get('config_limit_admin');
                $pagination->url = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . $url . '&hostedpage={page}', 'SSL');

                $audit['pagination'] = $pagination->render();

                $audit['results'] = sprintf($this->language->get('text_pagination'), ($entries_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($entries_total - $this->config->get('config_limit_admin'))) ? $entries_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $entries_total, ceil($entries_total / $this->config->get('config_limit_admin')));

		$audit['filter_order_id'] = $filter_order_id;
                $audit['filter_result_code'] = $filter_result_code;
                //$audit['filter_total'] = $filter_total;
                $audit['filter_date_added'] = $filter_date_added;
                $audit['filter_remote_ip'] = $filter_remote_ip;
		$data['auditdata']=$audit;
	}

	public function get_hosted_audit_entry() {
                $this->load->model('extension/payment/bluesnap');
                $result_code = 0;
                $result_msg = '';
                $bluesnap_audit_record = null;
                if (!isset($this->request->post['bluesnap_hosted_fields_audit_id'])) {
                        $result_code = 1;
                        $result_msg = $this->language->get('error_bluesnap_audit_id_required');
                } else {
                        $bluesnap_hosted_fields_audit_id = $this->request->post['bluesnap_hosted_fields_audit_id'];
                        $bluesnap_audit = $this->model_extension_payment_bluesnap->get_audit_entry($bluesnap_hosted_fields_audit_id);
                        if (!isset($bluesnap_audit['bluesnap_hosted_fields_audit_id'])) {
                                $result_code = 1;
                                $result_msg = $this->language->get('error_bluesnap_audit_id_not_found');
                        } else {
                                $bluesnap_audit_record = $bluesnap_audit;
                        }
                }

                $json = array('result_code' => $result_code, 'result_msg' => $result_msg);
                if ($bluesnap_audit_record != null)
                        $json['bluesnap_audit_record'] = $bluesnap_audit_record;
                $this->response->setOutput(json_encode($json));

                $this->response->addHeader('Content-Type: application/json');
        }

	protected function getList(&$data) {
		$data['button_modal_popup_close'] = $this->language->get('button_modal_popup_close');
		$data['modal_popup_title'] = $this->language->get('modal_popup_title');

		$data['button_view'] = $this->language->get('button_view');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
		
	        if (isset($this->request->get['filter_result_code'])) {
                        $filter_result_code = $this->request->get['filter_result_code'];
                } else {
                        $filter_result_code = null;
                }

		if (isset($this->request->get['filter_remote_ip'])) {
                        $filter_remote_ip = $this->request->get['filter_remote_ip'];
                } else {
                        $filter_remote_ip = null;
                }


		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_result_code'])) {
			$url .= '&filter_result_code=' . $this->request->get['filter_result_code']; 
		}

		if (isset($this->request->get['filter_remote_ip'])) {
			$url .= '&filter_remote_ip=' . $this->request->get['filter_remote_ip'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('extension/payment/bluesnap');
		$data['entries'] = array();
		
		$filter_data = array(
			'filter_order_id'      	=> $filter_order_id,
			'filter_total'        	=> $filter_total,
			'filter_date_added'    	=> $filter_date_added,
			'filter_result_code'	=> $filter_result_code,
			'filter_remote_ip'	=> $filter_remote_ip,
			'sort'                 	=> $sort,
			'order'                	=> $order,
			'start'                	=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                	=> $this->config->get('config_limit_admin')
		);

		$entries_total = $this->model_extension_payment_bluesnap->get_total_entries($filter_data);

		$results = $this->model_extension_payment_bluesnap->get_entries($filter_data);
		$data['entries'] = array();
		foreach ($results as $result) {
			$data['entries'][] = array(
				'bluesnap_audit_id'	=> $result['bluesnap_audit_id'],
				'order_id'      	=> $result['order_id'],
				'status'   		=> $result['result_code'] == 0 ? "SUCCESS" : "FAILURE",
				'total'         	=> $result['currency'] . ' ' . $result['amount'],
				'date_added'    	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'remote_ip' 		=> $result['remote_ip'],
				'view'          	=> $this->url->link('extension/payment/bluesnap/get_audit_entry', 'user_token=' . $this->session->data['user_token'] . '&bluesnap_audit_id=' . $result['bluesnap_audit_id'], 'SSL'),
			);
		}
		$data['column_order_id'] 	= $this->language->get('column_order_id');
		$data['column_outcome'] 	= $this->language->get('column_outcome');
		$data['column_total'] 		= $this->language->get('column_total');
		$data['column_date_added'] 	= $this->language->get('column_date_added');
		$data['column_remote_ip']	= $this->language->get('column_remote_ip');
		$data['column_action'] 		= $this->language->get('column_action');

		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_outcome'] = $this->language->get('entry_outcome');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_remote_ip'] = $this->language->get('entry_remote_ip');
		$data['text_result_code_success'] = $this->language->get('text_result_code_success');
		$data['text_result_code_failure'] = $this->language->get('text_result_code_failure');
		$data['button_filter']	= $this->language->get('button_filter');
		$data['user_token'] = $this->session->data['user_token'];

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
                       $url .= "&filter_order_id=" . $this->request->get['filter_order_id'];
                }

                if (isset($this->request->get['filter_total'])) {
                        $url .= "&filter_total=" . $this->request->get['filter_total'];
                }

                if (isset($this->request->get['filter_date_added'])) {
                        $url .= "&filter_date_added=" . $this->request->get['filter_date_added'];
                }

                if (isset($this->request->get['filter_result_code'])) {
			$url .= "&filter_result_code=" . $this->request->get['filter_result_code'];
                }

                if (isset($this->request->get['filter_remote_ip'])) {
			$url .= "&filter_remote_ip=".$this->request->get['filter_remote_ip'];
                }

			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['sort_order'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . '&sort=order_id' . $url, 'SSL');
		$data['sort_outcome'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . '&sort=result_code' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . '&sort=amount' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url, 'SSL');
		$data['sort_remote_ip'] = $this->url->link('extension/payment/bluesnap', 'user_token=' . $this->session->data['user_token'] . '&sort=remote_ip' . $url, 'SSL');


		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['entries_total'] = $entries_total;

		$pagination = new Pagination();
		$pagination->total = $entries_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($entries_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($entries_total - $this->config->get('config_limit_admin'))) ? $entries_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $entries_total, ceil($entries_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_result_code'] = $filter_result_code;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_remote_ip'] = $filter_remote_ip;//echo '<pre>';print_r($data);exit;
	}


	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/bluesnap')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->post['payment_bluesnap_username']) || strlen(trim($this->request->post['payment_bluesnap_username'])) == 0) {
			$this->error['username'] = $this->language->get('error_username');
		}
	
	    if (!isset($this->request->post['payment_bluesnap_description_prefix']) || strlen(trim($this->request->post['payment_bluesnap_description_prefix'])) < 1 ||  strlen(trim($this->request->post['payment_bluesnap_description_prefix'])) > 20) {
                        $this->error['description_prefix'] = $this->language->get('error_description_prefix');
                }

		if (!isset($this->request->post['payment_bluesnap_password']) || strlen(trim($this->request->post['payment_bluesnap_password'])) == 0) {
                        $this->error['password'] = $this->language->get('error_password');
                }

                if (!isset($this->request->post['payment_bluesnap_debug_enabled']) || ($this->request->post['payment_bluesnap_debug_enabled'] != 0 && $this->request->post['payment_bluesnap_debug_enabled'] != 1)) {
                        $this->error['debug_enabled'] = $this->language->get('error_debug_enabled');
                }

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}
}
