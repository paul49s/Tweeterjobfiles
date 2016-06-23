<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Parser extends MX_Controller
{

	function index()
	{
		echo modules::run('parsers/index');
		$this->updateQueryCasheForNames();
	}
	function tweet()
	{
		echo modules::run('parsers/tweet');
	}
	
	
	public function updateQueryCasheForNames(){
		//update query cashe for names
		$sql = "SELECT distinct t_selection.name from t_selection
		join t_events on t_selection.event_id=t_events.id
		join t_meeting on t_events.meeting_id = t_meeting.id
		join t_event_type on t_meeting.event_type_id=t_event_type.id
		where t_event_type.category='HR'";
		
		$query = $this->db->query($sql);
		
		$sql = "SELECT distinct t_selection.name from t_selection
		join t_events on t_selection.event_id=t_events.id
		join t_meeting on t_events.meeting_id = t_meeting.id
		join t_event_type on t_meeting.event_type_id=t_event_type.id
		where t_event_type.category='DG'";
		
		$query = $this->db->query($sql);
		
	}
	
        public function updateTypeaheadQuery()
        {
            $sql = "CALL flatten_vhr_vgr_names()";
            $this->db->query($sql);
        }
	
}
