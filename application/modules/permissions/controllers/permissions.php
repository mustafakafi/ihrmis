<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Integrated Human Resource Management Information System 3.0dev
 *
 * An Open Source Application Software use by Government agencies for  
 * management of employees Attendance, Leave Administration, Payroll, 
 * Personnel Training, Service Records, Performance, Recruitment,
 * Personnel Schedule(Plantilla) and more...
 *
 * @package		iHRMIS
 * @author		Manny Isles
 * @copyright	Copyright (c) 2008 - 2014, Isles Technologies
 * @license		http://charliesoft.net/ihrmis/license
 * @link		http://charliesoft.net
 * @github	    http://github.com/mannysoft/ihrmis
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * iHRMIS Conversion Table Class
 *
 * This class use for converting number of minutes late
 * to the corresponding equivalent to leave credits.
 *
 * @package		iHRMIS
 * @subpackage	Models
 * @category	Models
 * @author		Manny Isles
 * @link		http://charliesoft.net
 * @github	    http://github.com/mannysoft/ihrmis/hrmis/user_guide/models/conversion_table.html
 */
class Permissions extends MX_Controller {

	protected $group;
	
	// --------------------------------------------------------------------
	
	function __construct()
    {
        parent::__construct();
		
		$this->load->model('options');
		
		$this->group = new GroupEloquent;
		
		//$this->output->enable_profiler(TRUE);
    }  
	
	// --------------------------------------------------------------------
	
	function group($id = '', $back_from = '')
	{
		$data['page_name'] 	= '<b>Permissions</b>';
		$data['legend'] 	= '<b>Edit Permissions for Group</b>';
		$data['focus_field'] 	= '';
		
		$data['msg'] = '';
					
		$data['row'] = $g = $this->group->find($id);
				
		$data['legend'] .= ' "'.$g->name.'"';
		
		$this->load->config('permissions');
				
		$data['permissions'] = $this->config->item('permissions');
		
		$data['group_id'] = $id;
		
		if ($back_from == 'groups')
		{
			$data['back_from'] = 'groups';
		}
		
		//print_r(array_combine($hours = range(0, 23), $hours));
		
		if ( Input::get('op'))
		{
			if ( Input::get('modules') )
			{
				foreach (Input::get('main_modules') as $module)
				{
					$p = new Permission_m();
					$p->where('group_id', $id);
					$p->where('module', $module)->get();
					$p->group_id 	= $id;
					$p->module 		= $module;
					$p->roles 		= (Input::get($module)) ? json_encode(Input::get($module)) : NULL;
					$p->save();
					
					// If no methods selected
					// Remove the module from permissions table
					if ($p->roles == NULL)
					{
						$p = new Permission_m();
						$p->where('group_id', $id);
						$p->where('module', $module)->get();
						$p->delete();
						
					}
				}
				
				$data['msg'] = 'Permissions has been saved!';
			}			
		}
	
		$data['main_content'] = 'group';
		
		return View::make('includes/template', $data);	
	}
	
	// --------------------------------------------------------------------
}	

/* End of file office_manage.php */
/* Location: ./system/application/modules/office_manage/controllers/office_manage.php */