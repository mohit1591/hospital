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
    	 
        <!-- upper portion -->
        <div class="createuser">
            <!-- // left -->
            <div class="box">
                <label>Employee Type</label>
                <select>
                  <option>-Select-</option>
                </select>
                <textarea></textarea>
            </div>

            <!-- // right -->
            <div class="box">
                <div class="boxleft">
                    <div class="grp">
                        <label>Employee Name</label>
                        <select>
                          <option>-Select-</option>
                        </select>
                    </div>
                    <div class="grp">
                        <label>User Name <span class="star">*</span></label>
                        <input type="text" name="">
                    </div>
                    <div class="grp">
                        <label>Password</label>
                        <input type="text" name="">
                    </div>
                    <div class="grp">
                        <label>Confirm Password</label>
                        <input type="text" name="">
                    </div>

                </div> <!-- boxleft -->
                </div> <!-- box -->

                <div class="box">
                    <button class="btn-custom-small"> <i class="fa fa-plus"></i> new</button>
                </div> <!-- box -->
                    

            
        </div> <!-- createuser -->







       <!-- table format -->
       <div class="createuser">
           
           <table class="cu-tbl table-bordered">
              <thead>
                <tr>
                  <th>SNo.</th>
                  <th>Name</th>
                  <th><input type="checkbox" name=""> <span>Access</span></th>
                  <th><input type="checkbox" name=""> <span>Submit</span></th>
                  <th><input type="checkbox" name=""> <span>Update</span></th>
                  <th><input type="checkbox" name=""> <span>Delete</span></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Pathology Collection Report</td>
                  <td><input type="checkbox" name=""></td>
                  <td><input type="checkbox" name=""></td>
                  <td><input type="checkbox" name=""></td>
                  <td><input type="checkbox" name=""></td>
                </tr>
              </tbody>
           </table>

           

         <div class="createuser-btns">
          <div class="btns">
            <button class="btn-update"><i class="fa fa-floppy-o"></i> Save</button>
            <button class="btn-update"><i class="fa fa-sign-out"></i> Exit</button>

            
          </div> <!-- btns -->
         </div> <!-- createuser-btns --> 

     </div> <!-- createuser -->


       

   </div> <!-- userlist-box -->


 
  

  




  
</section> <!-- userlist -->
<?php
$this->load->view('include/footer');
?>

</div><!-- container-fluid -->
</body>
</html>