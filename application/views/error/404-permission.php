<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!---bootstrap---->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>script.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
var save_method; 
var table;

$(document).ready(function() {
    table = $('#table').DataTable({
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('branch/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
});



$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'branch/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_branch(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'branch/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_branch(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'branch/view/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

 
function checkboxValues() 
{         
    $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
       if($(this).prop('checked')==true && !isNaN($(this).val()))
       {
            allVals.push($(this).val());
       } 
     });
     allbranch_delete(allVals);
} 

function allbranch_delete(allVals)
 {    
   if(allVals!="")
   {
       $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
        })
        .one('click', '#delete', function(e)
        { 
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('branch/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   }      
 }
</script>
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    
    <div class="userlist-box">
    	 
       <div class="permission">
           <div class="pheader">
                   <div class="p-1"><strong>Section</strong></div>
                   <div class="p-2"><strong>Action</strong></div>
                   <div class="p-3"><strong>Permission</strong></div>
           </div> <!-- pheader -->

           <div class="pbody">
             <div class="p-1"><strong>Branch</strong></div> <!-- p-1 -->
             <div class="p-2">
                    <div class="action">Add</div>
                    <div class="action">Edit</div>
                    <div class="action">Delete</div>
             </div> <!-- p-2 -->
             <div class="p-3">
                  <div class="per">
                      <span><input type="radio" name=""> Active</span> <span><input type="radio" name=""> Inactive</span>
                  </div>
                  <div class="per">
                      <span><input type="radio" name=""> Active</span> <span><input type="radio" name=""> Inactive</span>
                  </div>
                  <div class="per">
                      <span><input type="radio" name=""> Active</span> <span><input type="radio" name=""> Inactive</span>
                  </div>
             </div> <!-- p-3 -->        

           </div> <!-- pbody -->

           <div class="pbody">
             <div class="p-1"><strong>Branch</strong></div> <!-- p-1 -->
             <div class="p-2">
                    <div class="action">Add</div>
                    <div class="action">Edit</div>
                    <div class="action">Delete</div>
             </div> <!-- p-2 -->
             <div class="p-3">
                  <div class="per">
                      <span><input type="radio" name=""> Active</span> <span><input type="radio" name=""> Inactive</span>
                  </div>
                  <div class="per">
                      <span><input type="radio" name=""> Active</span> <span><input type="radio" name=""> Inactive</span>
                  </div>
                  <div class="per">
                      <span><input type="radio" name=""> Active</span> <span><input type="radio" name=""> Inactive</span>
                  </div>
             </div> <!-- p-3 -->        

           </div> <!-- pbody -->

           



      </div> <!-- permission -->




     <div class="permission-btns">
      <div class="btns">
        <button class="btn-update"><i class="fa fa-floppy-o"></i> Save</button>
        <button class="btn-update"><i class="fa fa-sign-out"></i> Exit</button>

        <!-- <button class="btn-custom-small">new</button> -->
      </div>
     </div>


       

   </div> <!-- userlist-box -->


 
  

  




  
</section> <!-- userlist -->
<?php
$this->load->view('include/footer');
?>

</div><!-- container-fluid -->
</body>
</html>