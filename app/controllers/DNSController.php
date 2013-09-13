<?php

use SoftLayer\SLSoapClient;

class DNSController extends BaseController {

	private $client;
	private $theme;
	private $user;
	private $setting;
	
	public function __construct() {

		$this->setting = Setting::find(1);


		$this->beforeFilter('check');

		$this->user = Sentry::getUser();

		$this->theme = Theme::uses('blacktie')->layout('dashboard');

		$this->theme->setName($this->user['attributes']['first_name'].' '.$this->user['attributes']['last_name']);
		$this->theme->setCompany($this->setting->company_name);
		
		$this->client = SLSoapClient::getClient('SoftLayer_Dns_Domain', null, 'jowy', '013fbe2abacc18f0fe8e74a1520626e3dbbbadbeeeec45a9f40f834cbb5fffc5');
	}

	private function createObject($id) {

		$this->client = SLSoapClient::getClient('SoftLayer_Dns_Domain', $id, 'jowy', '013fbe2abacc18f0fe8e74a1520626e3dbbbadbeeeec45a9f40f834cbb5fffc5');

	}

	private function searchDomain($keyword) {

		try {

			$result = $this->client->getByDomainName($keyword);
			return array('status' => TRUE, 'msg' => 'searchDomain has performed successfully','result' => $result);

		} catch (Exception $e)
		{

			return array('status' => FALSE, 'msg' => $e->getMessage, 'result' => NULL);
		
		}

	}

	private function addDomain($domainName = '', $ip = '', $ttl = 86400)
	{

		try {

			// Make new object for parameter
			$param = new stdClass();

			// Fill name parameter
			$param->name = $domainName;

			// Fill default dns record
			$param->resourceRecords = array();
			$param->resourceRecords[0] = new stdClass();
			$param->resourceRecords[0]->host = '@';
			$param->resourceRecords[0]->data = $ip;
			$param->resourceRecords[0]->type = 'a';
			$param->resourceRecords[0]->ttl = $ttl;

			$param->resourceRecords[1] = new stdClass();
			$param->resourceRecords[1]->host = 'www';
			$param->resourceRecords[1]->data = $domainName;
			$param->resourceRecords[1]->type = 'cname';
			$param->resourceRecords[1]->ttl = $ttl;

			$param->resourceRecords[2] = new stdClass();
			$param->resourceRecords[2]->host = 'ftp';
			$param->resourceRecords[2]->data = $ip;
			$param->resourceRecords[2]->type = 'a';
			$param->resourceRecords[2]->ttl = $ttl;

			$result = $this->client->createObject($param);

			return array('status' => TRUE, 'msg' => 'addDomain has performed successfully', 'result' => $result);

		} catch (Exception $e)
		{

			return array('status' => FALSE, 'msg' => $e->getMessage(), 'result' => NULL);

		}
	}

	private function addRecord($domainId = 0, $type = 'a', $host = '', $data = '', $ttl = 86400, $mxPriority = NULL )
	{
		// Get domain id
		$id = $domainId;

		if($id === FALSE)
		{
			return array('status' => FALSE, 'msg' => 'domainId or domainName cannot be NULL', 'result' => NULL);
		}

		$this->createObject($domainId);

		try
		{
			// Switch dns type record
			switch ($type)
			{
				case 'a':
					$result = $this->client->createARecord($host, $data, $ttl);
					break;
				case 'txt':
					$result = $this->client->createTxtRecord($host, $data, $ttl);
					break;
				case 'cname':
					$result = $this->client->createCnameRecord($host, $data, $ttl);
					break;
				case 'aaaa':
					$result = $this->client->createAaaaRecord($host, $data, $ttl);
					break;
				case 'mx':
					$result = $this->client->createMxRecord($host, $data, $ttl, $mxPriority);
					break;
				case 'ns':
					$result = $this->client->createNsRecord($host, $data, $ttl);
					break;
				case 'ptr':
					$result = $this->client->createPtrRecord($host, $data, $ttl);
					break;
				case 'spf':
					$result = $this->client->createSpfRecord($host, $data, $ttl);
					break;
				default:
					return array('status' => FALSE, 'msg' => 'Invalid type', 'result' => NULL);
					break;
			}
		} catch (Exception $e)
		{
			return array('status' => FALSE, 'msg' => $e->getMessage(), 'result' => NULL);
		}

		return array('status' => TRUE, 'msg' => 'addRecord has performed successfully', 'result' => $result);
	}

	private function removeDomain($domainId = 0)
	{
		
		if($domainId === 0)
		{
			return array('status' => FALSE, 'msg' => 'domainId cannot be NULL', 'result' => NULL);
		}

		try{

			$this->createObject($domainId);
			$result = $this->client->deleteObject();
			return array('status' => TRUE, 'msg' => 'Domain zone has been removed successfully', 'result' => $result);
		
		} catch(Exception $e)
		{
			return array('status' => FALSE, 'msg' => $e->getMessage(), 'result' => NULL);
		}
	}

	public function getAddDomain()
	{

		$this->theme->setPageTitle('Add New Domain Zone');

		return $this->theme->scope('public.user.dashboard-add-domain')->render();
	}

	public function postAddDomain()
	{

		$this->beforeFilter('csrf');

		$input = Input::get();

		$rules = array('domain' => 'required',
				'ip' => 'required|ip',
				'ttl' => 'numeric');

		$validation = Validator::make($input, $rules);

		if ($validation->fails()) {

			return Redirect::to('dashboard/add-domain')->withErrors($validation)->withInput($input);

		} else {

			if(!filter_var(gethostbyname($input['domain']), FILTER_VALIDATE_IP)) {
			    return Redirect::to('dashboard/add-domain')->withErrors(array(
			    	'domain' => 'You must enter valid domain'))->withInput($input);
			}

			$result = $this->searchDomain($input['domain']);

			if (!empty($result['result'])) {

				return Redirect::to('dashboard/add-domain')->withErrors(array(
					'domain' => 'Domain already registered, you have to delete it first'))->withInput($input);

			}

			if (empty($post['ttl'])) {
				$post['ttl'] = 86400;
			}

			$result = $this->addDomain($input['domain'], $input['ip'], $post['ttl']);

			if (!$result['result']) {

				return Redirect::to('dashboard/add-domain')->withErrors(array(
					'domain' => 'Internal error! Add domain failed.'))->withInput($input);

			}

			$dns = new DNS;

			$dns->domain_id = $result['result']->id;
			$dns->domain_name = $input['domain'];
			$dns->user_id = Session::get('uid');

			$dns->save();

			foreach($result['result']->resourceRecords as $row)
			{

				$record = new Record;
				$record->record_id = $row->id;
				$record->domain_id = $row->domainId;
				$record->data = $row->data;
				$record->host = $row->host;
				$record->expire = $row->expire;
				$record->minimum = $row->minimum;
				$record->mx_priority = $row->mxPriority;
				$record->refresh = $row->refresh;
				$record->retry = $row->retry;
				$record->ttl = $row->ttl;
				$record->type = $row->type;

				if(isset($row->responsiblePerson)) {

					$record->responsible_person = $row->responsiblePerson;
				}

				$record->save();

			}

			return Redirect::to('dashboard/add-domain')->with(array(
				'success' => 1));

		}
	}

	public function getRemoveDomain($domainId)
	{

		$result = $this->removeDomain($domainId);

		if ($result['result']) {

			$domain = DNS::find($domainId);

			$domain->delete();
			return Redirect::to('dashboard/list-domain')->with(array('success_delete' => 1));

		} else {

			return Redirect::to('dashboard/list-domain')->withErrors(array('domain' => 'Internal error! Delete domain failed'));

		}
	}

	public function postAddRecord()
	{

		$input = Input::get();

		if ($input['type'] == 'ns') {

			$rules = array('host' => 'required',
				'data' => 'required',
				'ttl' => 'numeric');

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {

				return Redirect::to('dashboard/domain-details/'.$input['domainId'])
				->withErrors($validation)
				->withInput($input)
				->with(array('type' => 'ns'));

			} else {

				if (empty($input['ttl'])) {

					$input['ttl'] = 86400;

				}

				$result = $this->addRecord($input['domainId'], $input['type'], $input['host'], $input['data'], $input['ttl']);

				if ($result['status'] === FALSE) {

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->withErrors(array('ns' => $result['msg']))
					->withInput($input)
					->with(array('type' => 'ns'));

				} else {

					$record = new Record;
					$record->record_id = $result['result']->id;
					$record->domain_id = $input['domainId'];
					$record->data = $input['data'];
					$record->host = $input['host'];
					$record->ttl = $input['ttl'];
					$record->type = strtoupper($input['type']);

					$record->save();

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->with(array('type' => 'ns',
						'success' => 1,
						'msg' => 'NS Record Sucessfully Added'));

				}
			}

		} elseif ($input['type'] == 'a') {

			$rules = array('host' => 'required',
				'data' => 'required',
				'ttl' => 'numeric');

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {

				return Redirect::to('dashboard/domain-details/'.$input['domainId'])
				->withErrors($validation)
				->withInput($input)
				->with(array('type' => 'a'));

			} else {

				if (empty($input['ttl'])) {

					$input['ttl'] = 86400;

				}

				$result = $this->addRecord($input['domainId'], $input['type'], $input['host'], $input['data'], $input['ttl']);

				if ($result['status'] === FALSE) {

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->withErrors(array('ns' => $result['msg']))
					->withInput($input)
					->with(array('type' => 'a'));

				} else {

					$record = new Record;
					$record->record_id = $result['result']->id;
					$record->domain_id = $input['domainId'];
					$record->data = $input['data'];
					$record->host = $input['host'];
					$record->ttl = $input['ttl'];
					$record->type = strtoupper($input['type']);

					$record->save();

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->with(array('type' => 'a',
						'success' => 1,
						'msg' => 'A Record Sucessfully Added'));

				}
			}

		} elseif ($input['type'] == 'aaaa') {

			$rules = array('host' => 'required',
				'data' => 'required',
				'ttl' => 'numeric');

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {

				return Redirect::to('dashboard/domain-details/'.$input['domainId'])
				->withErrors($validation)
				->withInput($input)
				->with(array('type' => 'aaaa'));

			} else {

				if (empty($input['ttl'])) {

					$input['ttl'] = 86400;

				}

				$result = $this->addRecord($input['domainId'], $input['type'], $input['host'], $input['data'], $input['ttl']);

				if ($result['status'] === FALSE) {

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->withErrors(array('ns' => $result['msg']))
					->withInput($input)
					->with(array('type' => 'aaaa'));

				} else {

					$record = new Record;
					$record->record_id = $result['result']->id;
					$record->domain_id = $input['domainId'];
					$record->data = $input['data'];
					$record->host = $input['host'];
					$record->ttl = $input['ttl'];
					$record->type = strtoupper($input['type']);

					$record->save();

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->with(array('type' => 'aaaa',
						'success' => 1,
						'msg' => 'A Record Sucessfully Added'));

				}
			}

		} elseif ($input['type'] == 'cname') {

			$rules = array('host' => 'required',
				'data' => 'required',
				'ttl' => 'numeric');

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {

				return Redirect::to('dashboard/domain-details/'.$input['domainId'])
				->withErrors($validation)
				->withInput($input)
				->with(array('type' => 'cname'));

			} else {

				if (empty($input['ttl'])) {

					$input['ttl'] = 86400;

				}

				$result = $this->addRecord($input['domainId'], $input['type'], $input['host'], $input['data'], $input['ttl']);

				if ($result['status'] === FALSE) {

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->withErrors(array('ns' => $result['msg']))
					->withInput($input)
					->with(array('type' => 'cname'));

				} else {

					$record = new Record;
					$record->record_id = $result['result']->id;
					$record->domain_id = $input['domainId'];
					$record->data = $input['data'];
					$record->host = $input['host'];
					$record->ttl = $input['ttl'];
					$record->type = strtoupper($input['type']);

					$record->save();

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->with(array('type' => 'cname',
						'success' => 1,
						'msg' => 'A Record Sucessfully Added'));

				}
			}

		} elseif ($input['type'] == 'mx') {

			$rules = array('host' => 'required',
				'data' => 'required',
				'priority' => 'numeric',
				'ttl' => 'numeric');

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {

				return Redirect::to('dashboard/domain-details/'.$input['domainId'])
				->withErrors($validation)
				->withInput($input)
				->with(array('type' => 'mx'));

			} else {


				$result = $this->addRecord($input['domainId'], $input['type'], $input['host'], $input['data'], NULL, $input['priority']);

				if ($result['status'] === FALSE) {

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->withErrors(array('ns' => $result['msg']))
					->withInput($input)
					->with(array('type' => 'mx'));

				} else {

					$record = new Record;
					$record->record_id = $result['result']->id;
					$record->domain_id = $input['domainId'];
					$record->data = $input['data'];
					$record->host = $input['host'];
					$record->mx_priority = $input['priority'];
					$record->type = strtoupper($input['type']);

					$record->save();

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->with(array('type' => 'cname',
						'success' => 1,
						'msg' => 'A Record Sucessfully Added'));

				}
			}

		} elseif ($input['type'] == 'txt') {

			$rules = array('host' => 'required',
				'data' => 'required',
				'ttl' => 'numeric');

			$validation = Validator::make($input, $rules);

			if ($validation->fails()) {

				return Redirect::to('dashboard/domain-details/'.$input['domainId'])
				->withErrors($validation)
				->withInput($input)
				->with(array('type' => 'txt'));

			} else {

				if (empty($input['ttl'])) {

					$input['ttl'] = 86400;

				}

				$result = $this->addRecord($input['domainId'], $input['type'], $input['host'], $input['data'], $input['ttl']);

				if ($result['status'] === FALSE) {

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->withErrors(array('ns' => $result['msg']))
					->withInput($input)
					->with(array('type' => 'txt'));

				} else {

					$record = new Record;
					$record->record_id = $result['result']->id;
					$record->domain_id = $input['domainId'];
					$record->data = $input['data'];
					$record->host = $input['host'];
					$record->ttl = $input['ttl'];
					$record->type = strtoupper($input['type']);

					$record->save();

					return Redirect::to('dashboard/domain-details/'.$input['domainId'])
					->with(array('type' => 'txt',
						'success' => 1,
						'msg' => 'A Record Sucessfully Added'));

				}
			}

		} elseif ($input['type'] == 'srv') {

		}

	}

	public function getEditRecord($recordId)
	{

	}

	public function getViewDomainRecord($domainId)
	{

		$records = DNS::find($domainId)->records()->get();

		$cname = array();
		$a = array();
		$mx = array();
		$soa = array();
		$ns = array();
		$aaaa = array();
		$srv = array();
		$txt = array();

		foreach($records as $record) {

			if ($record->type == 'A') {

				$a[] = array('record_id' => $record->record_id,
					'host' => $record->host,
					'data' => $record->data,
					'ttl' => $record->ttl);

			} elseif ($record->type == 'CNAME') {

				$cname[] = array('record_id' => $record->record_id,
					'host' => $record->host,
					'data' => $record->data,
					'ttl' => $record->ttl);

			} elseif ($record->type == 'NS') {

				$ns[] = array('record_id' => $record->record_id,
					'host' => $record->host,
					'data' => $record->data,
					'ttl' => $record->ttl);

			} elseif ($record->type == 'SOA') {

				$soa[] = array('record_id' => $record->record_id,
					'host' => $record->host,
					'data' => $record->data,
					'refresh' => $record->refresh,
					'retry' => $record->retry,
					'expire' => $record->expire,
					'minimum' => $record->minimum,
					'ttl' => $record->ttl);

			} elseif ($record->type == 'AAAA') {

				$aaaa[] = array('record_id' => $record->record_id,
					'host' => $record->host,
					'data' => $record->data,
					'ttl' => $record->ttl);

			} elseif ($record->type == 'TXT') {

				$txt[] = array('record_id' => $record->record_id,
					'host' => $record->host,
					'data' => $record->data,
					'ttl' => $record->ttl);

			} elseif ($record->type == 'SRV') {

				$srv[] = array('record_id' => $record->record_id,
					'host' => $record->host,
					'data' => $record->data,
					'priority' => $record->priority,
					'weight' => $record->weight,
					'service' => $record->service,
					'port' => $record->port,
					'protocol' => $record->protocol,
					'ttl' => $record->ttl);

			} elseif ($record->type == 'MX') {

				$mx[] = array('record_id' => $record->record_id,
					'host' => $record->host,
					'data' => $record->data,
					'mx_priority' => $record->mx_priority,
					'ttl' => $record->ttl);

			}
			
		}

		$this->theme->setPageTitle('Domain Record List');
		$this->theme->setA($a);
		$this->theme->setCname($cname);
		$this->theme->setNs($ns);
		$this->theme->setSoa($soa);
		$this->theme->setTxt($txt);
		$this->theme->setMx($mx);
		$this->theme->setAaaa($aaaa);
		$this->theme->setSrv($srv);
		$this->theme->setId($domainId);

		return $this->theme->scope('public.user.dashboard-list-domain-record')->render();

	}

	public function getListDomain()
	{
		$domains = DNS::where('user_id', '=', Session::get('uid'))->paginate(10);

		 $this->theme->setPageTitle('Domain List');
		 $this->theme->setBody($domains);

		 return $this->theme->scope('public.user.dashboard-list-domain')->render();
	}

	public function getTest()
	{
		$result = $this->searchDomain("google.com");

		if (!empty($result['result'])) {
			return "Not OK";
		} else {
			return "OK";
		}
	}
}