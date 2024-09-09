<?php
echo $html->part;
?>  
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript">
	var html_height = $( window ).height();   
</script> 
<?php
 $a = "<script type='text/javascript'>document.write(html_height);</script>";
$this->db->query("insert into path_report_height set  height = 'html_height' ");

?>
