
$('#btn-save-data').click(function(ev) {
   __saveData();
});
  
/* ==== control process ==== */
 var redirectPage;
 function __actioneExecuteProcess(paramControl, paramFrm, paramRedir){
	
	var processData = linkUrl; 
    __enablecarprocess();
    redirectPage = paramRedir;
    urlControl = processData + paramControl;   
	 
	 $.ajax(urlControl, {
		type: 'post',
		data: new FormData($('#' + paramFrm)[0]), 
		dataType: 'json',
		processData: false,
		contentType: false,
		success: function (dataRes) {
		   if(dataRes.result == 'success'){
			  $('#MassageCheckRes').addClass("TxtTrue");  
			  $('#MassageCheckRes').html(dataRes.message);
			  __countdownProcess('2');
		   }
		   else if(dataRes.result == 'error'){
			  $('#MassageCheckRes').addClass("TxtFail");
			  $('#MassageCheckRes').html(dataRes.message);
			   __countdownProcess('10');
		   }
       else if(dataRes.result == 'redirect'){
          $('#MassageCheckRes').addClass("TxtTrue");  
          $('#MassageCheckRes').html(dataRes.message); 
          
          interval = setInterval(function() {     
             var urlRedirect = dataRes.datalink;
             window.open(urlRedirect, '_self');  
           }, 1000);     
          
       }
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
		  console.log(textStatus)
		}
    });
		   
 }
 
 function __disablecarprocess(){
    $('#overlay-process').hide();
    $('#overlay-msg').hide();
    __autoReturnPage();
  }
    
  function __enablecarprocess(){  
     var evWriteCode = '<div id="overlay-process"></div><div id="overlay-msg"><div class="cropbox-process"><div class="bg-overlay-popup2"><div id="img-load-process"><img src="https://www.1hotelsolution.com/reservation/hotel-setup/assets/images/process/load.style5.gif"></div><p id="MassageCheckRes" class="">Please wait process ....</p><div class="clearfix"></div></div></div></div>';
     $('#jwProcess').html(evWriteCode);
     $('#overlay-process').show();
     $('#overlay-msg').show();     
  }
  
  function __autoReturnPage(){
    var returnToPage = linkUrl + redirectPage; 
    window.open(returnToPage, '_self');
  }
  
  function __countdownProcess(Para){
     var interval;
     var seconds = Para;  
    
    if(Para != ''){
       interval = setInterval(function() {           
       var el = Para; 
        if(seconds == 0) {
          __disablecarprocess()
          clearInterval(interval);
         return;
        }
      
        seconds--;
       }, 1000);
     }
   }
   
