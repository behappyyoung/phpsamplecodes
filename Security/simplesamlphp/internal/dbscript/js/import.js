// JavaScript Document
jQuery(document).ready(function(){
	/*jQuery.ajax({
            url: '/Ajax-call.php.php?page='+pg+'&startDt='+stDt+'&endDt='+endDt+'&uid='+uid,
            success: function(data) {                
                    if(data!='')
                    { 
                        jQuery('#search-rpt').html();
                        jQuery('#search-rpt').html(data);
                    }
                    else
                    {
                        jQuery('#search-rpt').html('No records found!');
                    }
             }
       });
	*/
	
});
function loadtables(obj, servType)
{
	var dbname=jQuery.trim(obj.value);
	if(dbname=='')
	{
		alert('Please select the DB');
	}
	else
	{
		$.ajax({
            url: 'Ajax-call.php?dbname='+dbname+'&process=showtables&servType='+servType,
            success: function(data) {                
                    if(data!='')
                    { 
                        $('#table'+servType).html();
                        $('#table'+servType).html(data);
						$('#table'+servType).show();	
						$(".multiselect").multiselect({
							selectedList: 10
						});
						
						$(".singleselect").multiselect({
							multiple: false,
							header: "Select an option",
							noneSelectedText: "Select an Option",
							selectedList: 1
						});
                    }
                    else
                    {
                        jQuery('#table'+servType).html('No tables found!');
						jQuery('#table'+servType).show();	
                    }
             }
       });
	}
	
	
}

function chktables(direction)
{
	var devobj=jQuery('#tablelist_dev').val();
	var livobj=jQuery('#tablelist_liv').val();
	var devdbname=jQuery('#dblist_dev').val();
	var livdbname=jQuery('#dblist_liv').val();
	
	if(direction=='left' && jQuery.trim(devobj)=='')
	{
		alert('Please select the development tables.');
	}
	else if(direction=='right' && jQuery.trim(livobj)=='')
	{
		alert('Please select the live tables.');
	}
	else
	{
		$.ajax({
            url: 'Ajax-call.php?dbname='+devdbname+'&livdbname='+livdbname+'&devTbl='+devobj+'&livTbl='+livobj+'&process=chktables&direction='+direction,
            success: function(data) {                
			
                    if(data!='')
                    { 
                        $('#resultMsg').html(data);
						$('#resultMsg').show();
                    }
					else
					{
						$('#resultMsg').html();
						$('#resultMsg').hide();
					}
             }
       });
	}
	
	
}