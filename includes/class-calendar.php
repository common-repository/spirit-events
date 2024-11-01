<?php
/*
*   Spirit Calendar class
*   
*   Co-author:
*   Xu Ding
*   thedilab@gmail.com
*   https://www.StarTutorial.com
*/
class Spirit_Calendar {  
     
    public function __construct(){     
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }
     
    private $currentYear=0;
    private $currentMonth=0;
    private $currentDay=0;
    private $currentDate=null;
    private $daysInMonth=0;
    private $naviHref= null;


    /*
    * Generate calendar HTML
    */
    public function show() {
        $year  = null;
        $month = null;
         
        if(isset($_GET['calyear'])){
 
            $year = absint($_GET['calyear']);
         
        }else if(null==$year){
 
            $year = date("Y",time());  
         
        }          
         
        if(null==$month&&isset($_GET['month'])){
 
            $month = absint($_GET['month']);
         
        }else if(null==$month){
 
            $month = date("m",time());
         
        }                  
        $this->currentYear=$year;
        $this->currentMonth=$month;
         
        $this->daysInMonth=$this->_daysInMonth($month,$year);  
         
        $content='<div id="calendar">'.
                        '<div class="box">'.
                        $this->_createNavi().
                        '</div>'.
                        '<div class="box-content">'.
                                '<ul class="label">'.$this->_createLabels().'</ul>';   
                                $content.='<div class="clear"></div>';     
                                $content.='<ul class="dates">';    
                                 
                                $weeksInMonth = $this->_weeksInMonth($month,$year);
                                // Create weeks in a month
                                for( $i=0; $i<$weeksInMonth; $i++ ){
                                     
                                    //Create days in a week
                                    for($j=1;$j<=7;$j++){
                                        $content.=$this->_showDay($i*7+$j);
                                    }
                                }
                                 
                                $content.='</ul>';
                                 
                                $content.='<div class="clear"></div>';     
             
                        $content.='</div>';
                 
        $content.='</div>';
        return $content;   
    }
     
    /*
    * Create LI element within the calenar
    */
    private function _showDay($cellNumber){

        $event_item = "";
         
        if($this->currentDay==0){
             
            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
                     
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                 
                $this->currentDay=1;
                 
            }
        }
         
        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
             
            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.$this->currentDay));
             
            $cellContent = $this->currentDay;

            global $wpdb;
            global $tssev_table_name;

            //Load events from db
            $events = $wpdb->get_results("SELECT se.post_id,se.start_time,se.end_time, se.place FROM " . $wpdb->prefix . $tssev_table_name . " se
            JOIN " . $wpdb->prefix . "posts p ON p.ID = se.post_id 
            WHERE DATE_FORMAT(FROM_UNIXTIME(se.start_time), '%Y-%m-%d')  = '" . date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.$this->currentDay))  . "' 
            AND (p.post_status = 'publish' OR p.post_status = 'inherit') ORDER BY se.start_time");


            for ($i=0; $i<count($events); $i++) {
                if (isset($events[$i]))
                {
                    $place=$events[$i]->place;
                    $start_time=date('H:i',$events[$i]->start_time);
                    $event_post = get_post($events[$i]->post_id);
                    $event_link = get_post_permalink($events[$i]->post_id);  	
                    $event_title = $event_post->post_title;

                    if (strlen($event_title) > 19) $event_title = mb_substr($event_title, 0, 16, 'UTF-8'). "...";
                   
                    $event_item .= "<a href=\"" . $event_link . "\">"
                                    . "<div class=\"event-item\">"
                                        . "<span class=\"event-item-time\">" . $start_time . "</span>"
                                        . "<span class=\"event-item-name\">" . $event_title . "</span>"
                                    . "</div>"
                                  . "</a>";                     
                }                   
            }
             
            $this->currentDay++;   
             
        }else{
             
            $this->currentDate =null;
            $cellContent=null;
        }
        
        return '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).
                (!isset($cellContent)?'mask':'').'"><span class="day_number">'.$cellContent.'</span>' . $event_item . '</li>';
    }
     
    /*
    * Create navigation
    */
    private function _createNavi(){
         
        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;

        $monthLabels = array( __( "January",'spirit-events')
            ,__( "February",'spirit-events')
            ,__( "March",'spirit-events')
            ,__( "April",'spirit-events')
            ,__( "May",'spirit-events')
            ,__( "June",'spirit-events')
            ,__( "July",'spirit-events')
            ,__( "August",'spirit-events')
            ,__( "September",'spirit-events')
            ,__( "October",'spirit-events')
            ,__( "November",'spirit-events')
            ,__( "December",'spirit-events'));        
         
        return
            '<div class="header">'.
                '<a class="prev btn" href="'.$this->naviHref.'?post_type=spirit-events&month='.sprintf('%02d',$preMonth).'&calyear='.$preYear.'">' . $monthLabels[intval($preMonth)-1].'</a>'.
                    '<div class="title"><span class="year">'.  $this->currentYear .'</span><span class="month">'. $monthLabels[intval($this->currentMonth)-1]. '</span></div>'.
                '<a class="next btn" href="'.$this->naviHref.'?post_type=spirit-events&month='.sprintf("%02d", $nextMonth).'&calyear='.$nextYear.'">' . $monthLabels[intval($nextMonth)-1].'</a>'.
            '</div>';
    }
         
    /**
    * Create calendar week labels
    */
    private function _createLabels(){  
                 
        $content='';
        $dayLabels = array( __( "Mon.",'spirit-events')
                            ,__( "Tue.",'spirit-events')
                            ,__( "Wed.",'spirit-events')
                            ,__( "Thu.",'spirit-events')
                            ,__( "Fri.",'spirit-events')
                            ,__( "Sat.",'spirit-events')
                            ,__( "Sun.",'spirit-events'));
         
        foreach($dayLabels as $index=>$label){
             
            $content.='<li class="'.($index==6?'end title':'start title').' title">'.$label.'</li>';
 
        }
         
        return $content;
    }
     
     
     
    /*
    * Calculate number of weeks in a particular month
    */
    private function _weeksInMonth($month=null,$year=null){
         
        if( null==($year) ) {
            $year =  date("Y",time()); 
        }
         
        if(null==($month)) {
            $month = date("m",time());
        }
         
        // Find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);       
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
         
        if($monthEndingDay<$monthStartDay){
            $numOfweeks++;     
        }
         
        return $numOfweeks;
    }
 
    /*
    * Calculate number of days in a particular month
    */
    private function _daysInMonth($month=null,$year=null){
         
        if(null==($year))
            $year =  date("Y",time()); 
 
        if(null==($month))
            $month = date("m",time());
             
        return date('t',strtotime($year.'-'.$month.'-01'));
    }     
}