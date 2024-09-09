<!--Added By Nitin Sharma 02/02/2024-->
<div class="cp-modal" style="width:60% !important;">
    <div class="modal-dialog">
        <div class="overlay-loader" id="overlay-loader">
            <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
        </div> 
        <div class="modal-content modal-top"> 
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
            <div class="modal-body"> 
                <div class="hr-scroll">
                    <table id="test_table" class="table table-striped table-bordered opd_booking_list " cellspacing="0" width="100%">
                        <thead class="bg-theme">
                            <tr>
                                <th>UHID No.</th> 
                                <th>Lab Ref. No.</th> 
                                <!--<th>Relation Name</th>-->
                                <th>Mobile No.</th>
                                <th>Booking Date</th>
                                <th>Total Amount</th>
                                <th>Discount</th>
                                <th>Status</th> 
                                <th>Action</th>
                            </tr>
                        </thead>  
                    </table>
                </div>
            </div> <!-- modal-body -->
            <div class="modal-footer"> 
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button> &nbsp;
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div> <!-- modal -->


<script>  
var table1; 
 $(document).ready(function() { 
    table1 = $('#test_table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('opd/test_ajax_list')?>",
            "type": "POST",
            "data" : {
                "booking_id" : "<?php echo $booking_id?>"
            }
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable
            'searchable': false, 

        },
        ],
    });
}); 
</script>   