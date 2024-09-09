<!DOCTYPE html>
<html>
<head>
<title></title>
<style>
  
*{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
  page {
    background: white;
    display: block;
    margin: 1em auto 0;
    margin-bottom: 0.5cm;
  }
  page[size="A4"] {  
                  
width: 21cm;      
padding: 3em;
      font-size: 13px;
      float: left;
  }
    @page {
    size: auto;   
    margin: 0;  
}
</style>
</head>

<!--<body id="expenses" style="font:13px Arial;"> -->
<body id="expenses" style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">
<page size="A4">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">OPD Doctor Summary Report</span></td>
      </tr>
      <tr>
        <td style="text-align:center;font-size:13px;padding:1em;">
          <strong>From</strong>
          <span><?php echo $get['start_date']; ?></span>
          <strong>To</strong>
          <span><?php echo $get['end_date']; ?></span>
        </td>
        <td><input type="button" name="button_print" value="Print" id="print" onClick="return my_function();"/></td>
      </tr>
    <table>
    

    <div style="float:left; margin-top:10px; width:100%;border:1px solid #111;border-top:none;font-size:13px;">       
        
          <div style="float:left;width:100%;border:1px solid #111;border-left:none;border-right:none;font-size:13px;">
            <div style="float:left;width:25%;font-weight:600;padding:4px;"><u>S. No.</u></div>
            <div style="float:left;width:50%;font-weight:600;padding:4px;"><u>Doctor Name</u></div>
            <div style="float:left;width:25%;font-weight:600;padding:4px;"><u>Consultant</u></div> 
          </div>
         

     <?php //echo "<pre>"; print_r($doctor_list);
     if(!empty($doctor_list))
     {
        
        $n=1;
        foreach($doctor_list as $expenses)
        {
            
          ?> 
          <div style="float:left;width:100%; min-height: 35px;  margin-bottom: 3px;">
              <div style="float:left;width:25%;padding:1px 4px;"><?php echo $n; ?></div>
              <div style="float:left;width:50%;padding:1px 4px;"><?php echo $expenses->doctor_name; ?>  </div>
              <div style="float:left;width:25%;padding:1px 4px;"><?php echo $expenses->total_count; ?></div> 
        </div> 
    <?php
      $n++;
      }

     }
     else
     {
      ?>
       <div style="float:left;width:100%;">
            <div style="float:left;width:100%;padding:1px 4px;text-indent:15px;"><div style="text-align:center;color:#990000;">Record not found</div></div>
       </div> 
      <?php
     }
     ?>           
    </div>        
</page>

</body>
</html>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
 <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

<script>
function my_function()
{
 $("#print").hide();
  window.print();
}
</script>
