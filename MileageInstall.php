<?php
/**
 * @author Donald Fellow <dfellow@yahoo.com> - Modded from Tasks by Arkadiusz Bisaga <abisaga@telaxus.com>
 * @copyright Copyright &copy; 2008, Telaxus LLC
 * @license MIT
 * @version 0.1
 * @package epesi-crm
 * @subpackage mileage
 */

defined("_VALID_ACCESS") || die('Direct access forbidden');

class Custom_MileageInstall extends ModuleInstall {

	public function install() {
		Base_ThemeCommon::install_default_theme('Custom/Mileage');
		$fields = array(
			array('name' => _M('Miles'), 				'type'=>'integer', 'required'=>true, 'extra'=>false, 'visible'=>true, 'display_callback'=>array('Custom_MileageCommon','display_miles')),

			array('name' => _M('Vehicle'), 		'type'=>'commondata', 'required'=>true, 'param'=>array('order_by_key'=>true,'Vehicle'), 'extra'=>false),

			array('name' => _M('Employees'), 			'type'=>'crm_contact', 'param'=>array('field_type'=>'multiselect', 'crits'=>array('Custom_MileageCommon','employees_crits'), 'format'=>array('CRM_ContactsCommon','contact_format_no_company')), 'display_callback'=>array('Custom_MileageCommon','display_employees'), 'required'=>true, 'extra'=>false, 'visible'=>true, 'filter'=>true),
			array('name' => _M('Customers'), 			'type'=>'crm_company_contact', 'param'=>array('field_type'=>'multiselect', 'crits'=>array('Custom_MileageCommon','customers_crits')), 'extra'=>false, 'visible'=>true),
			array('name' => _M('Permission'), 		'type'=>'commondata', 'required'=>true, 'param'=>array('order_by_key'=>true,'CRM/Access'), 'extra'=>false),

			array('name' => _M('Fuel'),			'type'=>'checkbox', 'extra'=>false, 'visible'=>true),
			
			array('name' => _M('Receipt'),			'type'=>'checkbox', 'extra'=>false, 'visible'=>true)
		);
		Utils_RecordBrowserCommon::install_new_recordset('mileage', $fields);
		Utils_RecordBrowserCommon::register_processing_callback('mileage', array('Custom_MileageCommon', 'submit_mileage'));
		Utils_RecordBrowserCommon::set_icon('mileage', Base_ThemeCommon::get_template_filename('Custom/Mileage', 'icon.png'));
		Utils_RecordBrowserCommon::set_recent('mileage', 5);
		Utils_RecordBrowserCommon::set_caption('mileage', _M('Mileage'));
		//Utils_RecordBrowserCommon::enable_watchdog('mileage', array('Custom_MileageCommon','watchdog_label'));
// ************ addons ************** //
		//Utils_AttachmentCommon::new_addon('mileage');
		//Utils_RecordBrowserCommon::new_addon('mileage', 'CRM/Mileage', 'messanger_addon', _M('Alerts'));
// ************ other ************** //
		//CRM_CalendarCommon::new_event_handler(_M('Mileage'), array('Custom_MileageCommon', 'crm_calendar_handler'));
		//Utils_BBCodeCommon::new_bbcode('mileage', 'Custom_MileageCommon', 'mileage_bbcode');
        	//CRM_RoundcubeCommon::new_addon('mileage');

		//if (ModuleManager::is_installed('Premium_SalesOpportunity')>=0)
		//	Utils_RecordBrowserCommon::new_record_field('mileage', _M('Opportunity'), 'select', true, false, 'premium_salesopportunity::Opportunity Name;Premium_SalesOpportunityCommon::crm_opportunity_reference_crits', '', false);

		Utils_RecordBrowserCommon::add_access('mileage', 'view', 'ACCESS:employee', array('(!permission'=>2, '|employees'=>'USER'));
		Utils_RecordBrowserCommon::add_access('mileage', 'add', 'ACCESS:employee');
		Utils_RecordBrowserCommon::add_access('mileage', 'edit', 'ACCESS:employee', array('(permission'=>0, '|employees'=>'USER', '|customers'=>'USER'));
		Utils_RecordBrowserCommon::add_access('mileage', 'delete', 'ACCESS:employee', array(':Created_by'=>'USER_ID'));
		Utils_RecordBrowserCommon::add_access('mileage', 'delete', array('ACCESS:employee','ACCESS:manager'));

		return true;
	}

	public function uninstall() {
		//CRM_CalendarCommon::delete_event_handler('Mileage');
        	//CRM_RoundcubeCommon::delete_addon('mileage');
		//Utils_AttachmentCommon::delete_addon('mileage');
		Base_ThemeCommon::uninstall_default_theme('Custom/Mileage');
		Utils_RecordBrowserCommon::unregister_processing_callback('mileage', array('Custom_MileageCommon', 'submit_mileage'));
		Utils_RecordBrowserCommon::uninstall_recordset('mileage');
		return true;
	}

	public function version() {
		return array("0.1");
	}

	public function requires($v) {
		return array(
			array('name'=>'Utils/RecordBrowser', 'version'=>0),
			array('name'=>'Utils/Attachment', 'version'=>0),
			array('name'=>'CRM/Common', 'version'=>0),
			array('name'=>'CRM/Roundcube', 'version'=>0),
			array('name'=>'CRM/Contacts', 'version'=>0),
			array('name'=>'CRM/Calendar', 'version'=>0),
			array('name'=>'Base/Lang', 'version'=>0),
			array('name'=>'Base/Acl', 'version'=>0),
			array('name'=>'Utils/ChainedSelect', 'version'=>0),
			array('name'=>'Data/Countries', 'version'=>0),
			array('name'=>'CRM/Filters','version'=>0),
			array('name'=>'Libs/QuickForm','version'=>0),
			array('name'=>'Base/Theme','version'=>0));
	}

	public static function info() {
		return array('Author'=>'<a href="mailto:dfellow@yahoo.com">Donald Fellow</a>', 'License'=>'TL', 'Description'=>'Module for organizing mileage records.');
	}

	public static function simple_setup() {
		return 'Custom';
	}
}

?>