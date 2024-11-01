(function( $ ) {
    'use strict';

    //Init color picker on plugin settings page
    jQuery('.tssev-color-picker').wpColorPicker();

    //Init date pair control in single page administration
    jQuery('#datePair .time').timepicker({ 
            'timeFormat': 'h:i', 
            'showDuration': true
    }); 
    
    jQuery('#datePair .date').datepicker({ 
            'format': 'dd.mm.yyyy',
            'autoclose': true
    });    
    
    jQuery('#datePair').datepair();

})( jQuery );
