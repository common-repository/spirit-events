<?php

/*
* Spirit Event class 
*/
class Spirit_Event {

    public $place;
    public $live_stream;
    public $latitude;
    public $longitude;
    public $start_time;
    public $start_date;
    public $end_time;
    public $end_date;
	public $google_start_date;
	public $google_start_time;
	public $google_end_date;
	public $google_end_time;

    function __construct($post_id) {
        global $wpdb;
        global $tssev_table_name;
        
        $results = $wpdb->get_results( 'SELECT start_time,end_time,place,live_stream,latitude,longitude FROM ' . $wpdb->prefix . $tssev_table_name . ' WHERE post_id=' . $post_id);	

        if (isset($results[0]))
        {
            $this->place = $results[0]->place;
            $this->live_stream = $results[0]->live_stream;
            $this->latitude=$results[0]->latitude;
            $this->longitude=$results[0]->longitude;
            $this->start_date=date('d.m.Y',$results[0]->start_time);
            $this->start_time=date('H:i',$results[0]->start_time);
            $this->end_date=date('d.m.Y',$results[0]->end_time);	
            $this->end_time=date('H:i',$results[0]->end_time);	
			$this->google_start_date=date('Ymd',$results[0]->start_time);
			$this->google_start_time=date('Hi',$results[0]->start_time);
            $this->google_end_date=date('Ymd',$results[0]->end_time);
            $this->google_end_time=date('Hi',$results[0]->end_time);
        }
        else 
        {
            $this->place="";
            $this->live_stream="";
            $this->latitude="";
            $this->longitude="";
            $this->start_date="";
            $this->start_time="";
            $this->end_date="";	
            $this->end_time="";
            $this->google_start_date="";
            $this->google_start_time="";
            $this->google_end_date="";
            $this->google_end_time="";			
        }
    }
}
?>