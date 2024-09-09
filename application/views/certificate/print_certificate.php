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
    min-height: 29.7cm !important;  
    padding: 3em;
    font-size:13px;
    overflow-y:auto;
  }
    @page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

  <page size="A4">
    <?php
          if($flag != 1){
            echo $template_header;
          } else {
            echo '<div style="width:100%;height:160px">&nbsp;</div>';
          }
    ?>
    <?php echo $template; ?>
   
  </page>

</body>
</html>
