<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parsers_model extends CI_Model {

	private function mainDirectory(){
                return "/home/sisdata/SISData365Client8/messages/Masters/";
		//return "./../sis/messages/Masters/";SISData365Client8
	}

	public function parse()
	{ 
	 
		//exit;
             $parseOutputDirectory = "/home/sisdata/parsed";
		$directories = $this->getDirectories();
		//var_dump($directories);
		foreach($directories as $directoryDateKey => $directoryDate){
            //var_dump(count($directoryDate));

            if(count($directoryDate) == 0){
                //var_dump($this->mainDirectory() . $directoryDateKey );
                rmdir ($this->mainDirectory() . $directoryDateKey );
            }
            foreach($directoryDate as $directorySportKey => $directorySport){
				//var_dump(count($directorySport));
				if(count($directorySport) == 0){
					//var_dump($this->mainDirectory() . $directoryDateKey . "/" . $directorySportKey);
					
					//echo 'Remove - '.$this->mainDirectory() . $directoryDateKey . "/" . $directorySportKey. PHP_EOL;
					
					rmdir ($this->mainDirectory() . $directoryDateKey . "/" . $directorySportKey);
				}
				foreach($directorySport as $xmlFileKey => $xmlFile){
                    //var_dump('1');
                    //var_dump($directoryDateKey);
                    //var_dump('2');
                    //var_dump($directorySportKey);
                    //var_dump('3');
                    //var_dump($xmlFile);
                    //var_dump('============');

                    if(!is_array($xmlFile)){

                    $full_path = $this->mainDirectory() . $directoryDateKey . "/" . $directorySportKey . "/" . $xmlFile;
					if (strpos($xmlFile, '.xml') !== FALSE && strpos($xmlFile, '.xmls')  === FALSE ){
						// found xml
						if (strpos($xmlFile, 'VRDG') !== FALSE){
							//echo $directoryDateKey.' - found - VGR file'. PHP_EOL;
							//echo 'Found it';
                            //$start_date = new DateTime();
                            $this->parseVirtualDogRace( $this->mainDirectory() . $directoryDateKey . "/" . $directorySportKey . "/" . $xmlFile );
                            //$since_start = $start_date->diff(new DateTime());
                            //echo $since_start->days.' days total<br>';
                            //echo $since_start->y.' years<br>';
                            //echo $since_start->m.' months<br>';
                            //echo $since_start->d.' days<br>';
                            //echo $since_start->h.' hours<br>';
                            //echo $since_start->i.' minutes<br>';
                            //echo $since_start->s.' seconds<br>';
						} else if (strpos($xmlFile, 'VRHR') !== FALSE){
							//echo $directoryDateKey.' - found - VHR file'. PHP_EOL;
							// found "virdual horse race"
							//echo 'Found it';
                            //$start_date = new DateTime();
                            $this->parseVirtualHorseRace( $this->mainDirectory() . $directoryDateKey . "/" . $directorySportKey . "/" . $xmlFile );
                            //$since_start = $start_date->diff(new DateTime());
                            //echo '<br>' . $since_start->days.' days total<br>';
                            //echo $since_start->y.' years<br>';
                            //echo $since_start->m.' months<br>';
                            //echo $since_start->d.' days<br>';
                            //echo $since_start->h.' hours<br>';
                            //echo $since_start->i.' minutes<br>';
                            //echo $since_start->s.' seconds<br>';
						} else if (strpos($xmlFile, 'UK49') !== FALSE){
							//echo $directoryDateKey.' - found - 49 file'. PHP_EOL;
							// found "uk 49"
							//echo 'Found it';
                            //$start_date = new DateTime();
                            $this->parseUK49( $this->mainDirectory() . $directoryDateKey . "/" . $directorySportKey . "/" . $xmlFile );
                
				
		
//Twitter changes end	
				 
		            //$since_start = $start_date->diff(new DateTime());
                            //echo '<br>' . $since_start->days.' days total<br>';
                            //echo $since_start->y.' years<br>';
                            //echo $since_start->m.' months<br>';
                            //echo $since_start->d.' days<br>';
                            //echo $since_start->h.' hours<br>';
                            //echo $since_start->i.' minutes<br>';
                            //echo $since_start->s.' seconds<br>';
						} else if (strpos($xmlFile, 'IEILNG') !== FALSE){
							//echo $directoryDateKey.' - found - ILB file'. PHP_EOL;
							// found "luckynumber"
							//echo 'Found it';
							$this->parseLuckyNumber( $this->mainDirectory() . $directoryDateKey . "/" . $directorySportKey . "/" . $xmlFile );
							
							 
				
							
						} else if (strpos($xmlFile, 'UKRANG') !== FALSE){
							//echo $directoryDateKey.' - found - RAPIDO file'. PHP_EOL;
							// found "rapido"
							//echo 'Found it';
							$this->parseRapido( $this->mainDirectory() . $directoryDateKey . "/" . $directorySportKey . "/" . $xmlFile );
						} else {
							// not xml
							var_dump($xmlFile);
						}
						//var_dump($xmlFile);

						try {
							if (!file_exists($parseOutputDirectory . "/" . $directoryDateKey . "/" . $directorySportKey . "/")) {
								$www = mkdir($parseOutputDirectory . "/" . $directoryDateKey . "/" . $directorySportKey . "/" , 0777, true);
							}
						} catch (Exception $e) {
							echo 'Caught exception creating directory: ',  $e->getMessage(), "\n";
						}

                        try {
                            $date_dir = new DateTime($directoryDateKey) ;
                            $date_now = new DateTime('-2 days');
                            $interval = $date_now->diff($date_dir);
                            //echo "interval " . $interval->format('%R');
                            if($date_now > $date_dir  ){
                            //if( $interval->format('%R') == '-' ){
                            $newFilePath = $parseOutputDirectory . "/" . $directoryDateKey . "/" . $directorySportKey . "/" . $xmlFile;
                            //echo "DEBUG: Moving $full_path to $newFilePath.\n";
                                $move_resultt = rename( $full_path, $newFilePath );
                                if(!$move_resultt){
                                    echo "Could not move file from $full_path to $newFilePath.\n";
                                    //echo (str_replace("sis/messages", "parsed", $full_path));
                                    //die;
                                }
                            }

                        } catch (Exception $e) {
                            echo 'Caught exception moving file: ',  $e->getMessage(), "\n";
                        }

					} else {
						// not xml

					}
                    }

                }
				//var_dump($directoryDateKey . '   ' . $directorySportKey );
			}
		}
	}

	private function getDirectories(){
		$this->load->helper('directory');

		$map = directory_map( $this->mainDirectory() );
		//var_dump($map);
		return $map;
	}

	private function parseVirtualDogRace($filename){
		$this->load->model('Virtual_dog_race_model');

		$this->Virtual_dog_race_model->parse($filename);
	}

	private function parseVirtualHorseRace($filename){
		$this->load->model('Virtual_horse_race_model');

		$this->Virtual_horse_race_model->parse($filename);
	}

	private function parseUK49($filename){
		$this->load->model('UK_49_model');

		$this->UK_49_model->parse($filename);
	}

	private function parseLuckyNumber($filename){
		$this->load->model('Lucky_number_model');

		$this->Lucky_number_model->parse($filename);
	}

	private function parseRapido($filename){
		$this->load->model('Rapido_model');

		$this->Rapido_model->parse($filename);
	}

public function tweet49s(){
	
	//Twitter Changes Database
$date_to_use=date("Y-m-d");
//$date_to_use='2016-05-25';
$day_results = $this->get_game_result_by_date('49',$date_to_use); 
//$day_results = $this->get_game_result_by_date('49','2016-06-18');
    		$game_results = array();

			foreach($day_results as $result){

				$game_results[$result->num]['draw_time'] = $result->time;
				$game_results[$result->num]['event_id'] = $result->id_id;


				// 0 - do not show results , 1 - show
				if($result->status=="M" || $result->status=="P" || $result->status=="V" || $result->status=="C"){
					$game_results[$result->num]['status'] = 0;
				}else{
					$game_results[$result->num]['status'] = 1;
				}

				$game_results[$result->num]['date'] = $result->date;

				if(true){
					if ($result->num==1){
						$game_results[$result->num]['draw_name'] = "Lunchtime Draw";
					} elseif($result->num==2) {
						$game_results[$result->num]['draw_name'] = "Teatime Draw";
					}elseif($result->num==3){
						$game_results[$result->num]['draw_name'] = "3rd draw";
					}else{
						$game_results[$result->num]['draw_name'] = $result->num."th draw";
					}
				}
				
			

				if($result->bonusnumber!='Y'){
					$game_results[$result->num]['results']['numbers'][] = $result->number;
				}
				if($result->bonusnumber=='Y'){
					$game_results[$result->num]['results']['booster'] = $result->number;
				}
				$game_results[$result->num]['results']['actual_draw_time'] = $result->offtime;

			}

			//flatten objects array
			$draws = array();
			foreach($game_results as $draw){
				$draws[]=$draw;
			}
		//print_r($draws);
		//exit;
		
//$this->codebird->setConsumerKey('HksbA8ulLGMQpRgknARZHLwGm','1QCJVxQTHtD2lO33tdDU8t8BsTIIwFiux8Dfr3utNC0v0sm3Y9');
     //  $this->codebird->setToken('743368700233940992-48uUtClQGZ1wFNY4kzbRgEPsRi9r87k','6o4ESnW4hokTwhd84cmHTTKxct1PO7arXJmOIwZvpqoYR');
	 
	 //Live Credentials
	 
	 $this->codebird->setConsumerKey('seYmXnJoZGh6lfN18of7TKrFc','iSaSCcz6ZfEumXsQ8botelgujrxvhWmtdnLZjVfPGjhhO8OZV1');
$this->codebird->setToken('750021588-9mJxzeZYM0Tp866COqW2t4uWyMLIHQT2yDQdoP4C','avxr6flcQK0pqkIxbdeX58UOiSwd9Hr93SPr66cTST6QH');
		$tweet_text='49\'s Results:';
		for($i=0 ;$i<count($draws);$i++){
			$tweet_text=$tweet_text.'
			'.$draws[$i]['draw_name'].": ";
			//print_r($draws[$i]['results']['numbers']);
			sort($draws[$i]['results']['numbers']);
			//print_r($draws[$i]['results']['numbers']);exit;
			$nybres=implode(',', $draws[$i]['results']['numbers']);
			$tweet_text=$tweet_text.$nybres.' ';
			$tweet_text=$tweet_text.'Booster: '.$draws[$i]['results']['booster'];
			
			
			}
	//	print_r($draws);	
		//print_r($tweet_text);
		
		
	$tweet_text=$tweet_text.'
	http://49s.co.uk/49s';
		$params = array(
  'status' => $tweet_text
);
if(!(empty($draws))){
	
if(file_exists("/home/sisdata/tweets/49s_".$date_to_use.".txt")){
	
$myfile = fopen("/home/sisdata/tweets/49s_".$date_to_use.".txt", "r") or die("Unable to open file!");
$file_text=fread($myfile,filesize("/home/sisdata/tweets/49s_".$date_to_use.".txt"));

fclose($myfile);
if($file_text!=$tweet_text){
	$response = $this->codebird->statuses_update($params);
	$handle = fopen("/home/sisdata/tweets/49s_".$date_to_use.".txt", "w+") or die("Unable to open file!");
fclose($handle);
	$myfile = fopen("/home/sisdata/tweets/49s_".$date_to_use.".txt", "w") or die("Unable to open file!");
fwrite($myfile, $tweet_text);
fclose($myfile);
	}
//	else{echo "same text";}
	
}else{
	
$myfile = fopen("/home/sisdata/tweets/49s_".$date_to_use.".txt", "w") or die("Unable to open file!");
fwrite($myfile, $tweet_text);
fclose($myfile);	
$response = $this->codebird->statuses_update($params);
}
		
}
	}


public function tweetILB(){//Twitter Changes Database

$date_to_use=date("Y-m-d");
//$date_to_use='2016-05-25';

$day_results = $this->get_game_result_by_date('IL',$date_to_use);
//$day_results = $this->get_game_result_by_date('IL',date("Y-m-d"));

    		$game_results = array();

			foreach($day_results as $result){

				$game_results[$result->num]['draw_time'] = $result->time;
				$game_results[$result->num]['event_id'] = $result->id_id;


				// 0 - do not show results , 1 - show
				if($result->status=="M" || $result->status=="P" || $result->status=="V" || $result->status=="C"){
					$game_results[$result->num]['status'] = 0;
				}else{
					$game_results[$result->num]['status'] = 1;
				}

				$game_results[$result->num]['date'] = $result->date;

				if(true){
					if ($result->num == 1){
						$game_results[$result->num]['draw_name'] = "Main Draw";
					} else if($result->num == 2) {
						$game_results[$result->num]['draw_name'] = "2nd";
					} else if($result->num == 3) {
						$game_results[$result->num]['draw_name'] = "3rd";
					}
				}
				
			

				if($result->bonusnumber!='Y'){
					$game_results[$result->num]['results']['numbers'][] = $result->number;
				}
				if($result->bonusnumber=='Y'){
					$game_results[$result->num]['results']['booster'] = $result->number;
				}
				$game_results[$result->num]['results']['actual_draw_time'] = $result->offtime;

			}

			//flatten objects array
			$draws = array();
			foreach($game_results as $draw){
				$draws[]=$draw;
			}
		//print_r($draws);
		//exit;
		
//$this->codebird->setConsumerKey('HksbA8ulLGMQpRgknARZHLwGm','1QCJVxQTHtD2lO33tdDU8t8BsTIIwFiux8Dfr3utNC0v0sm3Y9');
  //      $this->codebird->setToken('743368700233940992-48uUtClQGZ1wFNY4kzbRgEPsRi9r87k','6o4ESnW4hokTwhd84cmHTTKxct1PO7arXJmOIwZvpqoYR');
  
  //Live Credentials
  $this->codebird->setConsumerKey('N9WwfFVSCeGcn2INlasQ','ohiedLfBaIw0N6LHyhf6JZ8VawBWl3LK1fzRHji1cQ');
$this->codebird->setToken('519289065-otVgrbJNOx1HS0oQ0AQFK286JsiZ9ZYawfTEsHrY','78tOYR3O3LBTH16Brhc6nLD0uW8yu9JdNg73O7BOBedUh');
  
		$tweet_text='ILB Results:';
		for($i=0 ;$i<count($draws);$i++){
			$tweet_text=$tweet_text.'
			'.$draws[$i]['draw_name'].":";
			sort($draws[$i]['results']['numbers']);
			$nybres=implode(',', $draws[$i]['results']['numbers']);
			$tweet_text=$tweet_text.$nybres.' ';
			$tweet_text=$tweet_text.'Bonus:'.$draws[$i]['results']['booster'];
			
			
			}
	//	print_r($draws);	
//print_r($tweet_text);
	$tweet_text=$tweet_text.'
	http://49s.co.uk/irishlottobet';
	
	
		$params = array(
  'status' => $tweet_text
);
if(!(empty($draws))){
	
	if(file_exists("/home/sisdata/tweets/ILB_".$date_to_use.".txt")){
	
$myfile = fopen("/home/sisdata/tweets/ILB_".$date_to_use.".txt", "r") or die("Unable to open file!");
$file_text=fread($myfile,filesize("/home/sisdata/tweets/ILB_".$date_to_use.".txt"));

fclose($myfile);
if($file_text!=$tweet_text){
	$response = $this->codebird->statuses_update($params);
	$handle = fopen("/home/sisdata/tweets/49s_".$date_to_use.".txt", "w+") or die("Unable to open file!");
fclose($handle);
	$myfile = fopen("/home/sisdata/tweets/49s_".$date_to_use.".txt", "w") or die("Unable to open file!");
fwrite($myfile, $tweet_text);
fclose($myfile);
	}
	//else{echo "same text";}
	
}else{
	
$myfile = fopen("/home/sisdata/tweets/ILB_".$date_to_use.".txt", "w") or die("Unable to open file!");
fwrite($myfile, $tweet_text);
fclose($myfile);	
$response = $this->codebird->statuses_update($params);
}
	
	
	
		//$response = $this->codebird->statuses_update($params);
}
		//echo date("Y-m-d");
//Twitter changes end	
}


  public function get_game_result_by_date($game_type,$date){
       // $memcache_key = "{$game_type}_result_{$date}";
       // $result = $this->cache->get($memcache_key);
       // if( !$result || empty($result) )
//{


    	$this->db->select('t_event_type.date');
    	$this->db->select('t_numbers.code');
    	$this->db->select('t_number_event.num');
    	$this->db->select('t_number_event.time');
    	$this->db->select('t_number_event.offtime');
    	$this->db->select('t_number_event.status');
    	$this->db->select('t_drawn.number');
    	$this->db->select('t_drawn.order');
    	$this->db->select('t_drawn.bonusnumber');
    	$this->db->select('t_number_event.id_id');

    	$this->db->from('t_event_type');

    	$this->db->join('t_numbers','t_numbers.event_id = t_event_type.id');
    	$this->db->join('t_number_event','t_number_event.number_id = t_numbers.id');
        $this->db->join('t_drawn','t_drawn.number_event_id = t_number_event.id');
    	/*
         * DISABLED - THE LEFT JOIN CAUSES BLANK / EXTRANEOUS ROWS OCCASIONALLY.
         * NOT SURE WHY YOU'D NEED TO DO A LEFT JOIN ANYWAY ....
        if( strtotime($date) < strtotime(date("Y-m-d"))){
    		$this->db->join('t_drawn','t_drawn.number_event_id = t_number_event.id', 'left');
    	}else{
    		$this->db->join('t_drawn','t_drawn.number_event_id = t_number_event.id');
    	}*/
    	$this->db->where('t_event_type.date',$date);
    	$this->db->where('t_event_type.category','NB');
    	$this->db->where('t_numbers.code',$game_type);

    	$this->db->order_by('t_number_event.num,t_drawn.order','asc');

    	//do not cache last day
    //	if( strtotime($date) < strtotime(date("Y-m-d", strtotime('-3 days')))){
    //		$this->db->cache_on();
    //	}else{
    	//	$this->db->cache_off();
    	//}
    	$query = $this->db->get();
    	$result = $query->result();
      //  $this->cache->set($memcache_key, $result, 300);
       // }

    	return $result;
    }
}
