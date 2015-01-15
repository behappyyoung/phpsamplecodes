var referer = document.referrer;
if((/young./i.test(referer))||(/dev./i.test(referer))){        // dev
    window.onload = function() {
      if(/dashboard/i.test(referer)){
         //document.getElementsByTagName('body')[0].style.overflow="hidden";
         document.getElementById('mpw_footer').style.display="none";         
         var divs = document.getElementById('uxNotificationPanel_uxContainer').getElementsByTagName('*');
         for(var divsub in divs){
              if(/panel-alert/i.test(divs[divsub].className)){
                  divs[divsub].style.display='none';
              }
              
         }
      }
    }
}else if((/livingplus./i.test(referer))||(/betterme./i.test(referer))){                             // living plus
 window.onload = function() {
      if(/dashboard/i.test(referer)){
         document.getElementsByTagName('body')[0].style.overflow="hidden";
         document.getElementById('mpw_footer').style.display="none";
         var divs = document.getElementById('uxNotificationPanel_uxContainer').getElementsByTagName('*');
         for(var divsub in divs){
              if(/panel-alert/i.test(divs[divsub].className)){
                  divs[divsub].style.display='none';
              }
              
         }
      }
    }       
}else if(/qa./i.test(referer)){                             // qa
       window.onload = function() {
      if(/dashboard/i.test(referer)){
         //document.getElementsByTagName('body')[0].style.overflow="hidden";
         document.getElementById('mpw_footer').style.display="none";         
         var divs = document.getElementById('uxNotificationPanel_uxContainer').getElementsByTagName('*');
         for(var divsub in divs){
              if(/panel-alert/i.test(divs[divsub].className)){
                  divs[divsub].style.display='none';
              }
              
         }
      }
    } 
}else{                                                      // live
   
}
