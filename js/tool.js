
$(document).ready(function() {
	"use strict";
	$('.entry-title').hover(function(){//get class value of what is the initializer and whether hover or click
	 var formData = '25';//need to get page id
	 
	 jQuery.ajax({
	        type:		'POST',
	        url: 		'http://localhost/sandbox.com/wp-admin/admin-ajax.php',//use echo to get the theme path
	        data: 		{
	            'action': 		'setPageID',
	            'formData': 	formData,
	        },
	     success: function(data){			        	
	        	var loadUrl = 'http://localhost/sandbox.com/?page_id='+formData;//use echo to get the theme path
					       
	       	jQuery("#ajax").load(loadUrl);
	    }	        
  });
     return false;
	} ,
	  function(){
		jQuery("#ajax").html(" ");
		 
	
	});
	/*
$("#interior").click(function(){
	$("#image").hide();
	 var formData = '957';
	
  jQuery.ajax({
	        type:		'POST',
	        url: 		'http://www.carlynco.com/wp-admin/admin-ajax.php',
	        data: 		{
	            'action': 		'setPageID',
	            'formData': 	formData,
	        },
	     success: function(data){			        	
	        	var loadUrl = 'http://www.carlynco.com/?page_id='+formData;
				$("#contact-info").css("float","left");
				 	$("body.page-id-12 .entry-content").css("background","");		       
	       	jQuery("#_image").load(loadUrl);
	    }	        
  });
     return false;
	  } ,
	  function(){
		//jQuery("#ajax").html(" ");
		 
	
	});
	
	
	*/

});

