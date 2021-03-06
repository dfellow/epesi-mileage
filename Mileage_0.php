<?php
/**
 * @author Donald Fellow <dfellow@yahoo.com> based on Arkadiusz Bisaga <abisaga@telaxus.com>
 * @copyright Copyright &copy; 2008, Telaxus LLC
 * @license MIT
 * @version 1.0
 * @package epesi-crm
 * @subpackage mileage
 */

defined("_VALID_ACCESS") || die('Direct access forbidden');

class Custom_Mileage extends Module {
	private $rb = null;

	public function body() {
		$this->help('Mileage Help','main');

		$this->rb = $this->init_module('Utils/RecordBrowser','mileage','mileage');
		$me = CRM_ContactsCommon::get_my_record();
		CRM_CommonCommon::status_filter($this->rb);
		$this->rb->set_filters_defaults(array('employees'=>$this->rb->crm_perspective_default()));
		//$this->rb->set_custom_filter('longterm',array('type'=>'select','label'=>__('Display tasks marked as'),'args'=>array('__NULL__'=>__('Both'),1=>__('Short-term'),2=>__('Long-term')),'trans'=>array('__NULL__'=>array('!longterm'=>2),1=>array('!longterm'=>1),2=>array('longterm'=>1))));
		$this->rb->set_defaults(array('employees'=>array($me['id']),'vehicle'=>0, 'permission'=>0));
		//$this->rb->set_default_order(array('deadline'=>'ASC', 'longterm'=>'ASC', 'priority'=>'DESC', 'title'=>'ASC'));
		$this->display_module($this->rb);
	}
	
	
	public function applet($conf, & $opts) {
		$opts['go'] = true;
		//$opts['title'] = __('Mileage');
		//.
		//				($conf['related']==0?' - '.__('Todo'):'').
		//				($conf['related']==1?' - '.__('Related'):'').
		//				($conf['term']=='s'?' - '.__('Short-term'):($conf['term']=='l'?' - '.__('Long-term'):''));
		$me = CRM_ContactsCommon::get_my_record();
		if ($me['id']==-1) {
			CRM_ContactsCommon::no_contact_message();
			return;
		}
		//$short = ($conf['term']=='s' || $conf['term']=='b');
		//$long = ($conf['term']=='l' || $conf['term']=='b');
		//$related = $conf['related'];
		$rb = $this->init_module('Utils/RecordBrowser','mileage','mileage');
		//$status = array();
		//for ($i=0;$i<5;$i++)
		//	if (isset($conf['status_'.$i]) && $conf['status_'.$i]) $status[] = $i;
		$crits = array();
		//$crits['status'] = $status;
		//if ($short && !$long) $crits['!longterm'] = 1;
		//if (!$short && $long) $crits['longterm'] = 1;
		$crits['employees'] = array($me['id']);
		
		//if ($related==0) $crits['employees'] = array($me['id']);
		//if ($related==1) $crits['customers'] = array($me['id']);
		//if ($related==2) {
		//	$crits['(employees'] = array($me['id']);
		//	$crits['|customers'] = array($me['id']);
		//}
		$conds = array(
									array(	array('field'=>'miles', 'width'=>10),
										array('field'=>'vehicle', 'width'=>10)
										),
									$crits,
									array('vehicle'=>'ASC'),
									array('Custom_MileageCommon','applet_info_format'),
									15,
									$conf,
									& $opts
				);
		$opts['actions'][] = Utils_RecordBrowserCommon::applet_new_record_button('mileage',array('employees'=>array($me['id'])));
		$this->display_module($rb, $conds, 'mini_view');
	}
	
	/*
	public function messanger_addon($arg) {
		$emp = array();
		$ret = CRM_ContactsCommon::get_contacts(array('id'=>$arg['employees']), array(), array('last_name'=>'ASC', 'first_name'=>'ASC'));
		foreach($ret as $c_id=>$data)
			if(is_numeric($data['login'])) {
				$emp[$data['login']] = CRM_ContactsCommon::contact_format_no_company($data);
			}

		$mes = $this->init_module('Utils/Messenger',array('CRM_Tasks:'.$arg['id'],array('CRM_TasksCommon','get_alarm'),array($arg['id']),strtotime($arg['deadline']),$emp));
//		$mes->set_inline_display();
		$this->display_module($mes);
	}
	*/
	public function caption(){
		if (isset($this->rb)) return $this->rb->caption();
	}
}

?>
