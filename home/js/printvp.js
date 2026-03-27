function printvpen(titlespan,imagespan,contentspan)
{
  var pgURL = document.location.href;
  var portalPosition = pgURL.indexOf("portal/");
  var vpName = "logo_"+pgURL.substring(portalPosition+7, pgURL.indexOf("/", portalPosition+8));
  if(vpName=="logo_!ut"){vpName = "moi_logo";}
  var logo = "/portal/images/theme/moinew/"+vpName+".gif";  
  
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var pgtitle = document.getElementById(titlespan).innerHTML; 
  var image = document.getElementById(imagespan).innerHTML; 
  var content = document.getElementById(contentspan).innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>Ministry of Interior Kingdom of Saudi Arabia</title></head>'); 
   docprint.document.write('<body onLoad="self.print()">');     
   docprint.document.write('<table><tr><td align="left" style="padding-bottom:20px;" colspan="2">');
   docprint.document.write('<img src="'+logo+'"/>');
   docprint.document.write('</td></tr>');
   docprint.document.write('<tr><td align="left" style="padding-bottom:10px;font-size:18px;color:#558B34" colspan="2"><b>');
   docprint.document.write(pgtitle);
   docprint.document.write('</b></td></tr>');
   docprint.document.write('<tr><td valign="top" align="left">');
   docprint.document.write(content);
   docprint.document.write('</td>');
   docprint.document.write('<td valign="top" align="left" style="text-align:right">');
   docprint.document.write(image);
   docprint.document.write('</td></tr></table>');
   docprint.document.write('</body></html>');
   docprint.document.close(); 
   docprint.focus();
}

function printvpar(titlespan,imagespan,contentspan)
{
  var pgURL = document.location.href;
  var portalPosition = pgURL.indexOf("portal/");
  var vpName = "logo_"+pgURL.substring(portalPosition+7, pgURL.indexOf("/", portalPosition+8));
  if(vpName=="logo_!ut"){vpName = "moi_logo";}
  var logo = "/portal/images/theme/moinew/"+vpName+"_rtl.gif";

  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var pgtitle = document.getElementById(titlespan).innerHTML; 
  var image = document.getElementById(imagespan).innerHTML; 
  var content = document.getElementById(contentspan).innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>وزارة الداخلية  المملكة العربية السعودية</title></head>'); 
   docprint.document.write('<body onLoad="self.print()">');     
   docprint.document.write('<table><tr><td align="right" style="padding-bottom:20px;" colspan="2">');
   docprint.document.write('<img src="'+logo+'"/>');
   docprint.document.write('</td></tr>');
   docprint.document.write('<tr><td align="right" style="padding-bottom:10px;font-size:18px;color:#558B34" colspan="2"><b>');
   docprint.document.write(pgtitle);
   docprint.document.write('</b></td></tr>');
   docprint.document.write('<tr><td valign="top" align="right;">');
   docprint.document.write(image);
   docprint.document.write('</td>');
   docprint.document.write('<td valign="top" align="right;" style="text-align:right">');
   docprint.document.write(content);
   docprint.document.write('</td></tr></table>');
   docprint.document.write('</body></html>');
   docprint.document.close(); 
   docprint.focus();
}