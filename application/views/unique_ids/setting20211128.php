<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 



</head>

<body>
 

<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    
    <div class="userlist-box">
    <div class="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
     


      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3"><strong>Title</strong></div>
                <div class="col-xs-3"><strong>Prefix</strong></div>
                <div class="col-xs-3"><strong>Start Part</strong></div>
                <div class="col-xs-3">
                    &nbsp;
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <?php
        //echo count($unique_list); exit;
        //print '<pre>'; print_r($unique_list);die;
        if(!empty($unique_list))
        {
          foreach($unique_list as $unique)
          {
            if($unique->unique_id=='1' && in_array('1',$users_data['permission']['section']))
            { 

           ?>
            <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>

                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>

              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
     
      <?php 
      }
      elseif($unique->unique_id=='2' && in_array('2',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='3' && in_array('20',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='4' && in_array('19',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, CURRENTDATE '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      
      elseif($unique->unique_id=='9' && in_array('85',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='56' && in_array('85',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='10' && in_array('56',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row --><script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='11' && (in_array('58',$users_data['permission']['section']) ||  in_array('167',$users_data['permission']['section']) ))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='13' && in_array('58',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='14' && in_array('59',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='16' && in_array('60',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='17' && in_array('61',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='18' && in_array('151',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='19' )
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      } 
      elseif($unique->unique_id=='20' && in_array('93',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='21' && in_array('151',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='22' && in_array('121',$users_data['permission']['section']))
      {
          ?>
      <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='23' && in_array('134',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='24' && in_array('130',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='25' && in_array('145',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='26' && in_array('145',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
        elseif($unique->unique_id=='27' && in_array('165',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
         elseif($unique->unique_id=='28' && in_array('166',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='29' && in_array('172',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }

        elseif($unique->unique_id=='30' && in_array('167',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
        elseif($unique->unique_id=='31' && in_array('168',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
        elseif($unique->unique_id=='32' && in_array('169',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }

        elseif($unique->unique_id=='33' && in_array('170',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='34' && in_array('207',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }

       elseif($unique->unique_id=='35' && in_array('183',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }

       elseif($unique->unique_id=='36' && in_array('182',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }

       elseif($unique->unique_id=='37' && in_array('181',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }

       elseif($unique->unique_id=='38' && in_array('180',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
 elseif($unique->unique_id=='39' && in_array('179',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }

 elseif($unique->unique_id=='40' && in_array('178',$users_data['permission']['section']))
      {
        //print_r($unique);
        ?>
    <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit[<?php echo $unique->id; ?>]" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> 
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form><!-- row -->
      <?php
      }
      elseif($unique->unique_id=='41' && in_array('265',$users_data['permission']['section']))
      {
        //print_r($unique);
        ?>
    <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit[<?php echo $unique->id; ?>]" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> 
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form><!-- row -->
      <?php
      }
      elseif($unique->unique_id=='43' && in_array('262',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      
      elseif($unique->unique_id=='48' && in_array('341',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='49' && in_array('342',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      
      elseif($unique->unique_id=='53' && in_array('349',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='55' && in_array('387',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      
      elseif($unique->unique_id=='60' && in_array('387',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='61' && in_array('358',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='62' && in_array('358',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='63' && in_array('358',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='64' && in_array('358',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='65' && in_array('358',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='66' && in_array('358',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='67' && in_array('358',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='68' && in_array('358',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='69' && in_array('358',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='70' && in_array('406',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='71' && in_array('406',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      
      elseif($unique->unique_id=='72' && in_array('397',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='73' && in_array('409',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      elseif($unique->unique_id=='74' && in_array('207',$users_data['permission']['section']))
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      
     elseif($unique->unique_id=='319')
      {
        ?>
         <form id="unique_id_form<?php echo $unique->id; ?>">
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-3">
                    <input class="unique_ids form-control text-uppercase tooltip-text"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  type="text" data-toggle="tooltip"  title="Allow only alpha numeric, '{year} {month} {day} {currentdate} for current year and '/'."  placeholder="Example SARAB">
                </div>
                <div class="col-xs-3">
                    <input class="form-control media5 tooltip-text numeric"  name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." >
                </div>
                <div class="col-xs-3">
                 <input name="p_id" value="<?php echo $unique->p_id; ?>" type="hidden">
                 <input name="branch_id" value="<?php echo $unique->branch_id; ?>" type="hidden">
                    <button class="btn-new" name="submit" value="Save" type="submit"> Save</button>
                </div>
              </div>
        </div>
      </div> <!-- row -->
      <script type="text/javascript">
        $("#unique_id_form<?php echo $unique->id; ?>").on("submit", function(event) 
        { 
          event.preventDefault(); 
          $('.overlay-loader').show();
          $.ajax({
            url: "<?php echo base_url(); ?>unique_ids/",
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            {
               flash_session_msg(result);    
               $('.overlay-loader').hide();    
            }
          });
        });

        $('.tooltip-text').tooltip({
            placement: 'right', 
            container: 'body',
            trigger   : 'focus' 
        });
      </script>
       </form>
      <?php
      }
      ?>
        <?php
            }
          }
         ?> 
      
      
       


   </div> <!-- close -->
 
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

 function onlyAlphabets(e, t) {

            try {

                if (window.event) {

                    var charCode = window.event.keyCode;

                }

                else if (e) {

                    var charCode = e.which;

                }

                else { return true; }

                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))

                    return true;

                else

                    return false;

            }

            catch (err) {

                alert(err.Description);

            }

        } 
 

</script>   
</div> <!-- container_fluid -->
</body>
</html>