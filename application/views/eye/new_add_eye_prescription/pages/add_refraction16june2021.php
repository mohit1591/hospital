<style>
*{margin:0;padding:0;box-sizing:border-box;}
.grid-frame {float:left;width:100%;padding:10px;display:flex;justify-content: space-between;flex-wrap: wrap;}
.grid-box{float:left;width:95%;height:fit-content;border:1px solid #fff;margin-bottom:10px;padding:5px;}
.grid-box-3{float:left;width:auto;height:fit-content;border:1px solid #fff;margin-bottom:10px;padding:5px;}
.tbl_responsive {float:left;width:100%;overflow:auto;}
.tbl_grid{float:left;width:100%;border-color:#aaa;border-collapse:collapse;display:table;}
.tbl_grid td{border:1px solid #aaa;font-size:13px;}
.form-radio {display:block;font-size:13px;padding:4px;}
.submitBtn {background:#666;color:#FFF;font-size:13px;border:3px solid #666;border-radius:3px;padding:2px 10px;cursor:pointer;}
input.w-40px{width:32.7px;border:none;padding:2px 4px;}
</style>
<div class="modal-dialog">
  <div class="overlay-loader" id="overlay-loader">
    <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
  </div>
  <div class="modal-content"> 
    <form method="post" id="ucva_add">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4><?php echo str_replace('-',' ', $page_title); ?></h4> 
      </div>
      <div class="modal-body">
        <div class="row"> 
          <div class="col-md-6">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th colspan="5" class="text-center">R / OD</th>
                </tr>
              </thead>
              <thead class="bg-info">
                <tr>
                  <th width="20%"></th>
                  <th width="20%">Sph</th>
                  <th width="20%">Cyl</th>
                  <th width="20%">Axis</th>
                  <th width="20%">Vision</th>
                </tr>
              </thead>
              <tbody id="focus_tab">
                <tr>
                  <td style="text-align:left;">Distant</td>
                  <td><input type="text" onclick="tabs_active('1','1');" class="l_tab_f_1" autofocus=""></td>
                  <td><input type="text" onclick="tabs_active('2','2');" class="l_tab_f_2"></td>
                  <td><input type="text" onclick="tabs_active('3','3');" class="l_tab_f_3"></td>
                  <td><input type="text" onclick="tabs_active('4','4');" class="l_tab_f_4"></td>
                </tr>
                <tr>
                  <td style="text-align:left;">Add <span class="text-danger">#</span></td>
                  <td><input type="text" onclick="tabs_active('5','5');" class="l_tab_f_5"></td>
                  <td><input type="text" disabled=""></td>
                  <td><input type="text" disabled=""></td>
                  <td><input type="text" class="tab_f_16"></td>
                </tr>
                <tr>
                  <td style="text-align:left;">Near</td>
                  <td><input type="text" class="tab_f_14"></td>
                  <td><input type="text" class="tab_f_2_1"></td>
                  <td><input type="text" class="tab_f_3_1"></td>
                  <td><input type="text" onclick="tabs_active('6','6');" class="l_tab_f_6"></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="col-md-6">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th colspan="5" class="text-center">L / OS</th>
                </tr>
              </thead>
              <thead class="bg-info">
                <tr>
                  <th width="20%"></th>
                  <th width="20%">Sph</th>
                  <th width="20%">Cyl</th>
                  <th width="20%">Axis</th>
                  <th width="20%">Vision</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="text-align:left;">Distant</td>
                  <td><input type="text" onclick="tabs_active('1','7');" class="l_tab_f_7"></td>
                  <td><input type="text" onclick="tabs_active('2','8');" class="l_tab_f_8"></td>
                  <td><input type="text" onclick="tabs_active('3','9');" class="l_tab_f_9"></td>
                  <td><input type="text" onclick="tabs_active('4','10');" class="l_tab_f_10"></td>
                </tr>
                <tr>
                  <td style="text-align:left;">Add <span class="text-danger">#</span></td>
                  <td><input type="text" onclick="tabs_active('5','11');" class="l_tab_f_11"></td>
                  <td><input type="text" class="" disabled></td>
                  <td><input type="text" class="" disabled></td>
                  <td><input type="text" class="tab_f_17"></td>
                </tr>
                <tr>
                  <td style="text-align:left;">Near</td>
                  <td><input type="text" class="tab_f_15"></td>
                  <td><input type="text" class="tab_f_8_1"></td>
                  <td><input type="text" class="tab_f_9_1"></td>
                  <td><input type="text" onclick="tabs_active('6','12');" class="l_tab_f_12"></td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>

        <hr>
        <div class="row m-t-5 mb-5">
          <div class="col-md-4">
           <!--  <div class="btn-group btn-toggle">
             <button class="btn btn-sm btn-info active" onclick="$('.mo_plus').toggle();">Plus</button>
             <button class="btn btn-sm btn-default" onclick="$('.mo_plus').toggle();">Minus</button>
           </div> -->
          </div>
          <div class="col-md-4 text-center">
           <!--  <button class="btn btn-info btn-sm">Right = Left</button> -->
          </div>
          <div class="col-md-4 text-right">
            <div class="btn-group">
              <button type="button" class="btn btn-sm btn-info" onclick="tabs_prev();">Previous</button>
              <button type="button" class="btn btn-sm btn-info" onclick="tabs_next();">Next</button>
            </div>
          </div>
        </div>

        <!-- //////// Plus 1 //////// -->
        <div class="modal-footer mo_plus text-left tab_1 tbs_val">
          <h5>Distant-Sph (+)</h5>
          <div class="btn-group">
            <button type="button" value="+0.00" class="btn btn-sm btn-default dist_sph_val">+0.00</button>
            <button type="button" value="+0.25" class="btn btn-sm btn-default dist_sph_val">+0.25</button>
            <button type="button" value="+0.50" class="btn btn-sm btn-default dist_sph_val">+0.50</button>
            <button type="button" value="+0.75" class="btn btn-sm btn-default dist_sph_val">+0.75</button>
            <button type="button" value="+1.00" class="btn btn-sm btn-default dist_sph_val">+1.00</button>
            <button type="button" value="+1.25" class="btn btn-sm btn-default dist_sph_val">+1.25</button>
            <button type="button" value="+1.50" class="btn btn-sm btn-default dist_sph_val">+1.50</button>
            <button type="button" value="+1.75" class="btn btn-sm btn-default dist_sph_val">+1.75</button>
            <button type="button" value="+2.00" class="btn btn-sm btn-default dist_sph_val">+2.00</button>
            <button type="button" value="+2.25" class="btn btn-sm btn-default dist_sph_val">+2.25</button>
            <button type="button" value="+2.50" class="btn btn-sm btn-default dist_sph_val">+2.50</button>
            <button type="button" value="+2.75" class="btn btn-sm btn-default dist_sph_val">+2.75</button>
            <button type="button" value="+3.00" class="btn btn-sm btn-default dist_sph_val">+3.00</button>
            <button type="button" value="+3.25" class="btn btn-sm btn-default dist_sph_val">+3.25</button>
            <button type="button" value="+3.50" class="btn btn-sm btn-default dist_sph_val">+3.50</button>
            <button type="button" value="+3.75" class="btn btn-sm btn-default dist_sph_val">+3.75</button>
            <button type="button" value="+4.00" class="btn btn-sm btn-default dist_sph_val">+4.00</button>
            <button type="button" value="+4.25" class="btn btn-sm btn-default dist_sph_val">+4.25</button>
            <button type="button" value="+4.50" class="btn btn-sm btn-default dist_sph_val">+4.50</button>
            <button type="button" value="+4.75" class="btn btn-sm btn-default dist_sph_val">+4.75</button>
            <button type="button" value="+5.00" class="btn btn-sm btn-default dist_sph_val">+5.00</button>
            <button type="button" value="+5.25" class="btn btn-sm btn-default dist_sph_val">+5.25</button>
            <button type="button" value="+5.50" class="btn btn-sm btn-default dist_sph_val">+5.50</button>
            <button type="button" value="+5.75" class="btn btn-sm btn-default dist_sph_val">+5.75</button>
            <button type="button" value="+6.00" class="btn btn-sm btn-default dist_sph_val">+6.00</button>
            <button type="button" value="+6.25" class="btn btn-sm btn-default dist_sph_val">+6.25</button>
            <button type="button" value="+6.50" class="btn btn-sm btn-default dist_sph_val">+6.50</button>
            <button type="button" value="+6.75" class="btn btn-sm btn-default dist_sph_val">+6.75</button>
            <button type="button" value="+7.00" class="btn btn-sm btn-default dist_sph_val">+7.00</button>
            <button type="button" value="+7.25" class="btn btn-sm btn-default dist_sph_val">+7.25</button>
            <button type="button" value="+7.50" class="btn btn-sm btn-default dist_sph_val">+7.50</button>
            <button type="button" value="+7.75" class="btn btn-sm btn-default dist_sph_val">+7.75</button>
            <button type="button" value="+8.00" class="btn btn-sm btn-default dist_sph_val">+8.00</button>
            <button type="button" value="+8.25" class="btn btn-sm btn-default dist_sph_val">+8.25</button>
            <button type="button" value="+8.50" class="btn btn-sm btn-default dist_sph_val">+8.50</button>
            <button type="button" value="+8.75" class="btn btn-sm btn-default dist_sph_val">+8.75</button>
            <button type="button" value="+9.00" class="btn btn-sm btn-default dist_sph_val">+9.00</button>
            <button type="button" value="+9.25" class="btn btn-sm btn-default dist_sph_val">+9.25</button>
            <button type="button" value="+9.50" class="btn btn-sm btn-default dist_sph_val">+9.50</button>
            <button type="button" value="+9.75" class="btn btn-sm btn-default dist_sph_val">+9.75</button>
            <button type="button" value="+10.00" class="btn btn-sm btn-default dist_sph_val">+10.00</button>
            <button type="button" value="+10.25" class="btn btn-sm btn-default dist_sph_val">+10.25</button>
            <button type="button" value="+10.50" class="btn btn-sm btn-default dist_sph_val">+10.50</button>
            <button type="button" value="+10.75" class="btn btn-sm btn-default dist_sph_val">+10.75</button>
            <button type="button" value="+11.00" class="btn btn-sm btn-default dist_sph_val">+11.00</button>
            <button type="button" value="+11.25" class="btn btn-sm btn-default dist_sph_val">+11.25</button>
            <button type="button" value="+11.50" class="btn btn-sm btn-default dist_sph_val">+11.50</button>
            <button type="button" value="+11.75" class="btn btn-sm btn-default dist_sph_val">+11.75</button>
            <button type="button" value="+12.00" class="btn btn-sm btn-default dist_sph_val">+12.00</button>
            <button type="button" value="+12.25" class="btn btn-sm btn-default dist_sph_val">+12.25</button>
            <button type="button" value="+12.50" class="btn btn-sm btn-default dist_sph_val">+12.50</button>
            <button type="button" value="+12.75" class="btn btn-sm btn-default dist_sph_val">+12.75</button>
            <button type="button" value="+13.00" class="btn btn-sm btn-default dist_sph_val">+13.00</button>
            <button type="button" value="+13.25" class="btn btn-sm btn-default dist_sph_val">+13.25</button>
            <button type="button" value="+13.50" class="btn btn-sm btn-default dist_sph_val">+13.50</button>
            <button type="button" value="+13.75" class="btn btn-sm btn-default dist_sph_val">+13.75</button>
            <button type="button" value="+14.00" class="btn btn-sm btn-default dist_sph_val">+14.00</button>
            <button type="button" value="+14.25" class="btn btn-sm btn-default dist_sph_val">+14.25</button>
            <button type="button" value="+14.50" class="btn btn-sm btn-default dist_sph_val">+14.50</button>
            <button type="button" value="+14.75" class="btn btn-sm btn-default dist_sph_val">+14.75</button>
            <button type="button" value="+15.00" class="btn btn-sm btn-default dist_sph_val">+15.00</button>
            <button type="button" value="+15.25" class="btn btn-sm btn-default dist_sph_val">+15.25</button>
            <button type="button" value="+15.50" class="btn btn-sm btn-default dist_sph_val">+15.50</button>
            <button type="button" value="+15.75" class="btn btn-sm btn-default dist_sph_val">+15.75</button>
            <button type="button" value="+16.00" class="btn btn-sm btn-default dist_sph_val">+16.00</button>
            <button type="button" value="+16.25" class="btn btn-sm btn-default dist_sph_val">+16.25</button>
            <button type="button" value="+16.50" class="btn btn-sm btn-default dist_sph_val">+16.50</button>
            <button type="button" value="+16.75" class="btn btn-sm btn-default dist_sph_val">+16.75</button>
            <button type="button" value="+17.00" class="btn btn-sm btn-default dist_sph_val">+17.00</button>
            <button type="button" value="+17.25" class="btn btn-sm btn-default dist_sph_val">+17.25</button>
            <button type="button" value="+17.50" class="btn btn-sm btn-default dist_sph_val">+17.50</button>
            <button type="button" value="+17.75" class="btn btn-sm btn-default dist_sph_val">+17.75</button>
            <button type="button" value="+18.00" class="btn btn-sm btn-default dist_sph_val">+18.00</button>
            <button type="button" value="+18.25" class="btn btn-sm btn-default dist_sph_val">+18.25</button>
            <button type="button" value="+18.50" class="btn btn-sm btn-default dist_sph_val">+18.50</button>
            <button type="button" value="+18.75" class="btn btn-sm btn-default dist_sph_val">+18.75</button>
            <button type="button" value="+19.00" class="btn btn-sm btn-default dist_sph_val">+19.00</button>
            <button type="button" value="+19.25" class="btn btn-sm btn-default dist_sph_val">+19.25</button>
            <button type="button" value="+19.50" class="btn btn-sm btn-default dist_sph_val">+19.50</button>
            <button type="button" value="+19.75" class="btn btn-sm btn-default dist_sph_val">+19.75</button>
            <button type="button" value="+20.00" class="btn btn-sm btn-default dist_sph_val">+20.00</button>
            <button type="button" value="+20.25" class="btn btn-sm btn-default dist_sph_val">+20.25</button>
            <button type="button" value="+20.50" class="btn btn-sm btn-default dist_sph_val">+20.50</button>
            <button type="button" value="+20.75" class="btn btn-sm btn-default dist_sph_val">+20.75</button>
            <button type="button" value="+21.00" class="btn btn-sm btn-default dist_sph_val">+21.00</button>
            <button type="button" value="+21.25" class="btn btn-sm btn-default dist_sph_val">+21.25</button>
            <button type="button" value="+21.50" class="btn btn-sm btn-default dist_sph_val">+21.50</button>
            <button type="button" value="+21.75" class="btn btn-sm btn-default dist_sph_val">+21.75</button>
            <button type="button" value="+22.00" class="btn btn-sm btn-default dist_sph_val">+22.00</button>
            <button type="button" value="+22.25" class="btn btn-sm btn-default dist_sph_val">+22.25</button>
            <button type="button" value="+22.50" class="btn btn-sm btn-default dist_sph_val">+22.50</button>
            <button type="button" value="+22.75" class="btn btn-sm btn-default dist_sph_val">+22.75</button>
            <button type="button" value="+23.00" class="btn btn-sm btn-default dist_sph_val">+23.00</button>
            <button type="button" value="+23.25" class="btn btn-sm btn-default dist_sph_val">+23.25</button>
            <button type="button" value="+23.50" class="btn btn-sm btn-default dist_sph_val">+23.50</button>
            <button type="button" value="+23.75" class="btn btn-sm btn-default dist_sph_val">+23.75</button>
            <button type="button" value="+24.00" class="btn btn-sm btn-default dist_sph_val">+24.00</button>
            <button type="button" value="+24.25" class="btn btn-sm btn-default dist_sph_val">+24.25</button>
            <button type="button" value="+24.50" class="btn btn-sm btn-default dist_sph_val">+24.50</button>
            <button type="button" value="+24.75" class="btn btn-sm btn-default dist_sph_val">+24.75</button>
            <button type="button" value="+25.00" class="btn btn-sm btn-default dist_sph_val">+25.00</button>
          </div>
        </div>

         <!-- //////// Minus //////// -->
          <div class="modal-footer dd-none mo_plus text-left tab_1 tbs_val">
            <h5>Distant-Sph (-)</h5>
            <div class="btn-group">
              <button type="button" value="-0.00" class="btn btn-sm btn-default dist_sph_val">-0.00</button>
              <button type="button" value="-0.25" class="btn btn-sm btn-default dist_sph_val">-0.25</button>
              <button type="button" value="-0.50" class="btn btn-sm btn-default dist_sph_val">-0.50</button>
              <button type="button" value="-0.75" class="btn btn-sm btn-default dist_sph_val">-0.75</button>
              <button type="button" value="-1.00" class="btn btn-sm btn-default dist_sph_val">-1.00</button>
              <button type="button" value="-1.25" class="btn btn-sm btn-default dist_sph_val">-1.25</button>
              <button type="button" value="-1.50" class="btn btn-sm btn-default dist_sph_val">-1.50</button>
              <button type="button" value="-1.75" class="btn btn-sm btn-default dist_sph_val">-1.75</button>
              <button type="button" value="-2.00" class="btn btn-sm btn-default dist_sph_val">-2.00</button>
              <button type="button" value="-2.25" class="btn btn-sm btn-default dist_sph_val">-2.25</button>
              <button type="button" value="-2.50" class="btn btn-sm btn-default dist_sph_val">-2.50</button>
              <button type="button" value="-2.75" class="btn btn-sm btn-default dist_sph_val">-2.75</button>
              <button type="button" value="-3.00" class="btn btn-sm btn-default dist_sph_val">-3.00</button>
              <button type="button" value="-3.25" class="btn btn-sm btn-default dist_sph_val">-3.25</button>
              <button type="button" value="-3.50" class="btn btn-sm btn-default dist_sph_val">-3.50</button>
              <button type="button" value="-3.75" class="btn btn-sm btn-default dist_sph_val">-3.75</button>
              <button type="button" value="-4.00" class="btn btn-sm btn-default dist_sph_val">-4.00</button>
              <button type="button" value="-4.25" class="btn btn-sm btn-default dist_sph_val">-4.25</button>
              <button type="button" value="-4.50" class="btn btn-sm btn-default dist_sph_val">-4.50</button>
              <button type="button" value="-4.75" class="btn btn-sm btn-default dist_sph_val">-4.75</button>
              <button type="button" value="-5.00" class="btn btn-sm btn-default dist_sph_val">-5.00</button>
              <button type="button" value="-5.25" class="btn btn-sm btn-default dist_sph_val">-5.25</button>
              <button type="button" value="-5.50" class="btn btn-sm btn-default dist_sph_val">-5.50</button>
              <button type="button" value="-5.75" class="btn btn-sm btn-default dist_sph_val">-5.75</button>
              <button type="button" value="-6.00" class="btn btn-sm btn-default dist_sph_val">-6.00</button>
              <button type="button" value="-6.25" class="btn btn-sm btn-default dist_sph_val">-6.25</button>
              <button type="button" value="-6.50" class="btn btn-sm btn-default dist_sph_val">-6.50</button>
              <button type="button" value="-6.75" class="btn btn-sm btn-default dist_sph_val">-6.75</button>
              <button type="button" value="-7.00" class="btn btn-sm btn-default dist_sph_val">-7.00</button>
              <button type="button" value="-7.25" class="btn btn-sm btn-default dist_sph_val">-7.25</button>
              <button type="button" value="-7.50" class="btn btn-sm btn-default dist_sph_val">-7.50</button>
              <button type="button" value="-7.75" class="btn btn-sm btn-default dist_sph_val">-7.75</button>
              <button type="button" value="-8.00" class="btn btn-sm btn-default dist_sph_val">-8.00</button>
              <button type="button" value="-8.25" class="btn btn-sm btn-default dist_sph_val">-8.25</button>
              <button type="button" value="-8.50" class="btn btn-sm btn-default dist_sph_val">-8.50</button>
              <button type="button" value="-8.75" class="btn btn-sm btn-default dist_sph_val">-8.75</button>
              <button type="button" value="-9.00" class="btn btn-sm btn-default dist_sph_val">-9.00</button>
              <button type="button" value="-9.25" class="btn btn-sm btn-default dist_sph_val">-9.25</button>
              <button type="button" value="-9.50" class="btn btn-sm btn-default dist_sph_val">-9.50</button>
              <button type="button" value="-9.75" class="btn btn-sm btn-default dist_sph_val">-9.75</button>
              <button type="button" value="-10.00" class="btn btn-sm btn-default dist_sph_val">-10.00</button>
              <button type="button" value="-10.25" class="btn btn-sm btn-default dist_sph_val">-10.25</button>
              <button type="button" value="-10.50" class="btn btn-sm btn-default dist_sph_val">-10.50</button>
              <button type="button" value="-10.75" class="btn btn-sm btn-default dist_sph_val">-10.75</button>
              <button type="button" value="-11.00" class="btn btn-sm btn-default dist_sph_val">-11.00</button>
              <button type="button" value="-11.25" class="btn btn-sm btn-default dist_sph_val">-11.25</button>
              <button type="button" value="-11.50" class="btn btn-sm btn-default dist_sph_val">-11.50</button>
              <button type="button" value="-11.75" class="btn btn-sm btn-default dist_sph_val">-11.75</button>
              <button type="button" value="-12.00" class="btn btn-sm btn-default dist_sph_val">-12.00</button>
              <button type="button" value="-12.25" class="btn btn-sm btn-default dist_sph_val">-12.25</button>
              <button type="button" value="-12.50" class="btn btn-sm btn-default dist_sph_val">-12.50</button>
              <button type="button" value="-12.75" class="btn btn-sm btn-default dist_sph_val">-12.75</button>
              <button type="button" value="-13.00" class="btn btn-sm btn-default dist_sph_val">-13.00</button>
              <button type="button" value="-13.25" class="btn btn-sm btn-default dist_sph_val">-13.25</button>
              <button type="button" value="-13.50" class="btn btn-sm btn-default dist_sph_val">-13.50</button>
              <button type="button" value="-13.75" class="btn btn-sm btn-default dist_sph_val">-13.75</button>
              <button type="button" value="-14.00" class="btn btn-sm btn-default dist_sph_val">-14.00</button>
              <button type="button" value="-14.25" class="btn btn-sm btn-default dist_sph_val">-14.25</button>
              <button type="button" value="-14.50" class="btn btn-sm btn-default dist_sph_val">-14.50</button>
              <button type="button" value="-14.75" class="btn btn-sm btn-default dist_sph_val">-14.75</button>
              <button type="button" value="-15.00" class="btn btn-sm btn-default dist_sph_val">-15.00</button>
              <button type="button" value="-15.25" class="btn btn-sm btn-default dist_sph_val">-15.25</button>
              <button type="button" value="-15.50" class="btn btn-sm btn-default dist_sph_val">-15.50</button>
              <button type="button" value="-15.75" class="btn btn-sm btn-default dist_sph_val">-15.75</button>
              <button type="button" value="-16.00" class="btn btn-sm btn-default dist_sph_val">-16.00</button>
              <button type="button" value="-16.25" class="btn btn-sm btn-default dist_sph_val">-16.25</button>
              <button type="button" value="-16.50" class="btn btn-sm btn-default dist_sph_val">-16.50</button>
              <button type="button" value="-16.75" class="btn btn-sm btn-default dist_sph_val">-16.75</button>
              <button type="button" value="-17.00" class="btn btn-sm btn-default dist_sph_val">-17.00</button>
              <button type="button" value="-17.25" class="btn btn-sm btn-default dist_sph_val">-17.25</button>
              <button type="button" value="-17.50" class="btn btn-sm btn-default dist_sph_val">-17.50</button>
              <button type="button" value="-17.75" class="btn btn-sm btn-default dist_sph_val">-17.75</button>
              <button type="button" value="-18.00" class="btn btn-sm btn-default dist_sph_val">-18.00</button>
              <button type="button" value="-18.25" class="btn btn-sm btn-default dist_sph_val">-18.25</button>
              <button type="button" value="-18.50" class="btn btn-sm btn-default dist_sph_val">-18.50</button>
              <button type="button" value="-18.75" class="btn btn-sm btn-default dist_sph_val">-18.75</button>
              <button type="button" value="-19.00" class="btn btn-sm btn-default dist_sph_val">-19.00</button>
              <button type="button" value="-19.25" class="btn btn-sm btn-default dist_sph_val">-19.25</button>
              <button type="button" value="-19.50" class="btn btn-sm btn-default dist_sph_val">-19.50</button>
              <button type="button" value="-19.75" class="btn btn-sm btn-default dist_sph_val">-19.75</button>
              <button type="button" value="-20.00" class="btn btn-sm btn-default dist_sph_val">-20.00</button>
              <button type="button" value="-20.25" class="btn btn-sm btn-default dist_sph_val">-20.25</button>
              <button type="button" value="-20.50" class="btn btn-sm btn-default dist_sph_val">-20.50</button>
              <button type="button" value="-20.75" class="btn btn-sm btn-default dist_sph_val">-20.75</button>
              <button type="button" value="-21.00" class="btn btn-sm btn-default dist_sph_val">-21.00</button>
              <button type="button" value="-21.25" class="btn btn-sm btn-default dist_sph_val">-21.25</button>
              <button type="button" value="-21.50" class="btn btn-sm btn-default dist_sph_val">-21.50</button>
              <button type="button" value="-21.75" class="btn btn-sm btn-default dist_sph_val">-21.75</button>
              <button type="button" value="-22.00" class="btn btn-sm btn-default dist_sph_val">-22.00</button>
              <button type="button" value="-22.25" class="btn btn-sm btn-default dist_sph_val">-22.25</button>
              <button type="button" value="-22.50" class="btn btn-sm btn-default dist_sph_val">-22.50</button>
              <button type="button" value="-22.75" class="btn btn-sm btn-default dist_sph_val">-22.75</button>
              <button type="button" value="-23.00" class="btn btn-sm btn-default dist_sph_val">-23.00</button>
              <button type="button" value="-23.25" class="btn btn-sm btn-default dist_sph_val">-23.25</button>
              <button type="button" value="-23.50" class="btn btn-sm btn-default dist_sph_val">-23.50</button>
              <button type="button" value="-23.75" class="btn btn-sm btn-default dist_sph_val">-23.75</button>
              <button type="button" value="-24.00" class="btn btn-sm btn-default dist_sph_val">-24.00</button>
              <button type="button" value="-24.25" class="btn btn-sm btn-default dist_sph_val">-24.25</button>
              <button type="button" value="-24.50" class="btn btn-sm btn-default dist_sph_val">-24.50</button>
              <button type="button" value="-24.75" class="btn btn-sm btn-default dist_sph_val">-24.75</button>
              <button type="button" value="-25.00" class="btn btn-sm btn-default dist_sph_val">-25.00</button>
            </div>
          </div>
        <!-- /////////// -->
        <!-- ///// 2 Pulse////// -->
        <div class="modal-footer d-none text-left tab_2 tbs_val">
          <h5>Distant-Cyl (+)</h5>
          <div class="btn-group">
            <button type="button" value="+0.00" class="btn btn-sm btn-default dist_cyl_val">+0.00</button>
            <button type="button" value="+0.25" class="btn btn-sm btn-default dist_cyl_val">+0.25</button>
            <button type="button" value="+0.50" class="btn btn-sm btn-default dist_cyl_val">+0.50</button>
            <button type="button" value="+0.75" class="btn btn-sm btn-default dist_cyl_val">+0.75</button>
            <button type="button" value="+1.00" class="btn btn-sm btn-default dist_cyl_val">+1.00</button>
            <button type="button" value="+1.25" class="btn btn-sm btn-default dist_cyl_val">+1.25</button>
            <button type="button" value="+1.50" class="btn btn-sm btn-default dist_cyl_val">+1.50</button>
            <button type="button" value="+1.75" class="btn btn-sm btn-default dist_cyl_val">+1.75</button>
            <button type="button" value="+2.00" class="btn btn-sm btn-default dist_cyl_val">+2.00</button>
            <button type="button" value="+2.25" class="btn btn-sm btn-default dist_cyl_val">+2.25</button>
            <button type="button" value="+2.50" class="btn btn-sm btn-default dist_cyl_val">+2.50</button>
            <button type="button" value="+2.75" class="btn btn-sm btn-default dist_cyl_val">+2.75</button>
            <button type="button" value="+3.00" class="btn btn-sm btn-default dist_cyl_val">+3.00</button>
            <button type="button" value="+3.25" class="btn btn-sm btn-default dist_cyl_val">+3.25</button>
            <button type="button" value="+3.50" class="btn btn-sm btn-default dist_cyl_val">+3.50</button>
            <button type="button" value="+3.75" class="btn btn-sm btn-default dist_cyl_val">+3.75</button>
            <button type="button" value="+4.00" class="btn btn-sm btn-default dist_cyl_val">+4.00</button>
            <button type="button" value="+4.25" class="btn btn-sm btn-default dist_cyl_val">+4.25</button>
            <button type="button" value="+4.50" class="btn btn-sm btn-default dist_cyl_val">+4.50</button>
            <button type="button" value="+4.75" class="btn btn-sm btn-default dist_cyl_val">+4.75</button>
            <button type="button" value="+5.00" class="btn btn-sm btn-default dist_cyl_val">+5.00</button>
            <button type="button" value="+5.25" class="btn btn-sm btn-default dist_cyl_val">+5.25</button>
            <button type="button" value="+5.50" class="btn btn-sm btn-default dist_cyl_val">+5.50</button>
            <button type="button" value="+5.75" class="btn btn-sm btn-default dist_cyl_val">+5.75</button>
            <button type="button" value="+6.00" class="btn btn-sm btn-default dist_cyl_val">+6.00</button>
            <button type="button" value="+6.25" class="btn btn-sm btn-default dist_cyl_val">+6.25</button>
            <button type="button" value="+6.50" class="btn btn-sm btn-default dist_cyl_val">+6.50</button>
            <button type="button" value="+6.75" class="btn btn-sm btn-default dist_cyl_val">+6.75</button>
            <button type="button" value="+7.00" class="btn btn-sm btn-default dist_cyl_val">+7.00</button>
            <button type="button" value="+7.25" class="btn btn-sm btn-default dist_cyl_val">+7.25</button>
            <button type="button" value="+7.50" class="btn btn-sm btn-default dist_cyl_val">+7.50</button>
            <button type="button" value="+7.75" class="btn btn-sm btn-default dist_cyl_val">+7.75</button>
            <button type="button" value="+8.00" class="btn btn-sm btn-default dist_cyl_val">+8.00</button>
            <button type="button" value="+8.25" class="btn btn-sm btn-default dist_cyl_val">+8.25</button>
            <button type="button" value="+8.50" class="btn btn-sm btn-default dist_cyl_val">+8.50</button>
            <button type="button" value="+8.75" class="btn btn-sm btn-default dist_cyl_val">+8.75</button>
            <button type="button" value="+9.00" class="btn btn-sm btn-default dist_cyl_val">+9.00</button>
            <button type="button" value="+9.25" class="btn btn-sm btn-default dist_cyl_val">+9.25</button>
            <button type="button" value="+9.50" class="btn btn-sm btn-default dist_cyl_val">+9.50</button>
          </div>
        </div>  

         <!-- ///// 2 Minus ////// -->
        <div class="modal-footer d-none mo_plus_2 text-left tab_2 tbs_val">
          <h5>Distant-Cyl (-)</h5>
          <div class="btn-group">
            <button type="button" value="-0.00" class="btn btn-sm btn-default dist_cyl_val">-0.00</button>
            <button type="button" value="-0.25" class="btn btn-sm btn-default dist_cyl_val">-0.25</button>
            <button type="button" value="-0.50" class="btn btn-sm btn-default dist_cyl_val">-0.50</button>
            <button type="button" value="-0.75" class="btn btn-sm btn-default dist_cyl_val">-0.75</button>
            <button type="button" value="-1.00" class="btn btn-sm btn-default dist_cyl_val">-1.00</button>
            <button type="button" value="-1.25" class="btn btn-sm btn-default dist_cyl_val">-1.25</button>
            <button type="button" value="-1.50" class="btn btn-sm btn-default dist_cyl_val">-1.50</button>
            <button type="button" value="-1.75" class="btn btn-sm btn-default dist_cyl_val">-1.75</button>
            <button type="button" value="-2.00" class="btn btn-sm btn-default dist_cyl_val">-2.00</button>
            <button type="button" value="-2.25" class="btn btn-sm btn-default dist_cyl_val">-2.25</button>
            <button type="button" value="-2.50" class="btn btn-sm btn-default dist_cyl_val">-2.50</button>
            <button type="button" value="-2.75" class="btn btn-sm btn-default dist_cyl_val">-2.75</button>
            <button type="button" value="-3.00" class="btn btn-sm btn-default dist_cyl_val">-3.00</button>
            <button type="button" value="-3.25" class="btn btn-sm btn-default dist_cyl_val">-3.25</button>
            <button type="button" value="-3.50" class="btn btn-sm btn-default dist_cyl_val">-3.50</button>
            <button type="button" value="-3.75" class="btn btn-sm btn-default dist_cyl_val">-3.75</button>
            <button type="button" value="-4.00" class="btn btn-sm btn-default dist_cyl_val">-4.00</button>
            <button type="button" value="-4.25" class="btn btn-sm btn-default dist_cyl_val">-4.25</button>
            <button type="button" value="-4.50" class="btn btn-sm btn-default dist_cyl_val">-4.50</button>
            <button type="button" value="-4.75" class="btn btn-sm btn-default dist_cyl_val">-4.75</button>
            <button type="button" value="-5.00" class="btn btn-sm btn-default dist_cyl_val">-5.00</button>
            <button type="button" value="-5.25" class="btn btn-sm btn-default dist_cyl_val">-5.25</button>
            <button type="button" value="-5.50" class="btn btn-sm btn-default dist_cyl_val">-5.50</button>
            <button type="button" value="-5.75" class="btn btn-sm btn-default dist_cyl_val">-5.75</button>
            <button type="button" value="-6.00" class="btn btn-sm btn-default dist_cyl_val">-6.00</button>
            <button type="button" value="-6.25" class="btn btn-sm btn-default dist_cyl_val">-6.25</button>
            <button type="button" value="-6.50" class="btn btn-sm btn-default dist_cyl_val">-6.50</button>
            <button type="button" value="-6.75" class="btn btn-sm btn-default dist_cyl_val">-6.75</button>
            <button type="button" value="-7.00" class="btn btn-sm btn-default dist_cyl_val">-7.00</button>
            <button type="button" value="-7.25" class="btn btn-sm btn-default dist_cyl_val">-7.25</button>
            <button type="button" value="-7.50" class="btn btn-sm btn-default dist_cyl_val">-7.50</button>
            <button type="button" value="-7.75" class="btn btn-sm btn-default dist_cyl_val">-7.75</button>
            <button type="button" value="-8.00" class="btn btn-sm btn-default dist_cyl_val">-8.00</button>
            <button type="button" value="-8.25" class="btn btn-sm btn-default dist_cyl_val">-8.25</button>
            <button type="button" value="-8.50" class="btn btn-sm btn-default dist_cyl_val">-8.50</button>
            <button type="button" value="-8.75" class="btn btn-sm btn-default dist_cyl_val">-8.75</button>
            <button type="button" value="-9.00" class="btn btn-sm btn-default dist_cyl_val">-9.00</button>
            <button type="button" value="-9.25" class="btn btn-sm btn-default dist_cyl_val">-9.25</button>
            <button type="button" value="-9.50" class="btn btn-sm btn-default dist_cyl_val">-9.50</button>
          </div>
        </div>  
        <!-- ////// 3 ///// -->
        <div class="modal-footer d-none text-left tab_3 tbs_val">
          <h5>Distant-Axis</h5>
          <div class="btn-group">          
           <button type="button" value="5" class="btn btn-sm btn-default dist_axis_val">5</button>
           <button type="button" value="10" class="btn btn-sm btn-default dist_axis_val">10</button>
           <button type="button" value="15" class="btn btn-sm btn-default dist_axis_val">15</button>
           <button type="button" value="20" class="btn btn-sm btn-default dist_axis_val">20</button>
           <button type="button" value="25" class="btn btn-sm btn-default dist_axis_val">25</button>
           <button type="button" value="30" class="btn btn-sm btn-default dist_axis_val">30</button>
           <button type="button" value="35" class="btn btn-sm btn-default dist_axis_val">35</button>
           <button type="button" value="40" class="btn btn-sm btn-default dist_axis_val">40</button>
           <button type="button" value="45" class="btn btn-sm btn-default dist_axis_val">45</button>
           <button type="button" value="50" class="btn btn-sm btn-default dist_axis_val">50</button>
           <button type="button" value="55" class="btn btn-sm btn-default dist_axis_val">55</button>
           <button type="button" value="60" class="btn btn-sm btn-default dist_axis_val">60</button>
           <button type="button" value="65" class="btn btn-sm btn-default dist_axis_val">65</button>
           <button type="button" value="70" class="btn btn-sm btn-default dist_axis_val">70</button>
           <button type="button" value="75" class="btn btn-sm btn-default dist_axis_val">75</button>
           <button type="button" value="80" class="btn btn-sm btn-default dist_axis_val">80</button>
           <button type="button" value="85" class="btn btn-sm btn-default dist_axis_val">85</button>
           <button type="button" value="90" class="btn btn-sm btn-default dist_axis_val">90</button>
           <button type="button" value="95" class="btn btn-sm btn-default dist_axis_val">95</button>
           <button type="button" value="100" class="btn btn-sm btn-default dist_axis_val">100</button>
           <button type="button" value="105" class="btn btn-sm btn-default dist_axis_val">105</button>
           <button type="button" value="110" class="btn btn-sm btn-default dist_axis_val">110</button>
           <button type="button" value="115" class="btn btn-sm btn-default dist_axis_val">115</button>
           <button type="button" value="120" class="btn btn-sm btn-default dist_axis_val">120</button>
           <button type="button" value="125" class="btn btn-sm btn-default dist_axis_val">125</button>
           <button type="button" value="130" class="btn btn-sm btn-default dist_axis_val">130</button>
           <button type="button" value="135" class="btn btn-sm btn-default dist_axis_val">135</button>
           <button type="button" value="140" class="btn btn-sm btn-default dist_axis_val">140</button>
           <button type="button" value="145" class="btn btn-sm btn-default dist_axis_val">145</button>
           <button type="button" value="150" class="btn btn-sm btn-default dist_axis_val">150</button>
           <button type="button" value="155" class="btn btn-sm btn-default dist_axis_val">155</button>
           <button type="button" value="160" class="btn btn-sm btn-default dist_axis_val">160</button>
           <button type="button" value="165" class="btn btn-sm btn-default dist_axis_val">165</button>
           <button type="button" value="170" class="btn btn-sm btn-default dist_axis_val">170</button>
           <button type="button" value="175" class="btn btn-sm btn-default dist_axis_val">175</button>
           <button type="button" value="180" class="btn btn-sm btn-default dist_axis_val">180</button>
         </div>
        </div>
        <!-- ////// 4 ///// -->
        <div class="modal-footer d-none text-left tab_4 tbs_val">
          <h5>Distant-Vision</h5>
          <div class="btn-group">        
            <button type="button" value="1/60" class="btn btn-sm btn-default dist_vision_val">1/60</button>
            <button type="button" value="2/60" class="btn btn-sm btn-default dist_vision_val">2/60</button>
            <button type="button" value="3/60" class="btn btn-sm btn-default dist_vision_val">3/60</button>
            <button type="button" value="4/60" class="btn btn-sm btn-default dist_vision_val">4/60</button>
            <button type="button" value="5/60" class="btn btn-sm btn-default dist_vision_val">5/60</button>
            <button type="button" value="6/60" class="btn btn-sm btn-default dist_vision_val">6/60</button>
            <button type="button" value="6/36" class="btn btn-sm btn-default dist_vision_val">6/36</button>
            <button type="button" value="6/36P" class="btn btn-sm btn-default dist_vision_val">6/36P</button>
            <button type="button" value="6/24" class="btn btn-sm btn-default dist_vision_val">6/24</button>
            <button type="button" value="6/24P" class="btn btn-sm btn-default dist_vision_val">6/24P</button>
            <button type="button" value="6/18" class="btn btn-sm btn-default dist_vision_val">6/18</button>
            <button type="button" value="6/18P" class="btn btn-sm btn-default dist_vision_val">6/18P</button>
            <button type="button" value="6/12" class="btn btn-sm btn-default dist_vision_val">6/12</button>
            <button type="button" value="6/12P" class="btn btn-sm btn-default dist_vision_val">6/12P</button>
            <button type="button" value="6/9" class="btn btn-sm btn-default dist_vision_val">6/9</button>
            <button type="button" value="6/9P" class="btn btn-sm btn-default dist_vision_val">6/9P</button>
            <button type="button" value="6/6" class="btn btn-sm btn-default dist_vision_val">6/6</button>
            <button type="button" value="6/6P" class="btn btn-sm btn-default dist_vision_val">6/6P</button>
          </div>
        </div>

       <!-- ////// 5 ///// -->
        <div class="modal-footer d-none text-left tab_5 tbs_val">
          <h5>Add-Sph</h5>
            <div class="btn-group">          
                      <button type="button" value="+0.00" class="btn btn-sm btn-default add_sph_val">+0.00</button>
                      <button type="button" value="+0.25" class="btn btn-sm btn-default add_sph_val">+0.25</button>
                      <button type="button" value="+0.50" class="btn btn-sm btn-default add_sph_val">+0.50</button>
                      <button type="button" value="+0.75" class="btn btn-sm btn-default add_sph_val">+0.75</button>
                      <button type="button" value="+1.00" class="btn btn-sm btn-default add_sph_val">+1.00</button>
                      <button type="button" value="+1.25" class="btn btn-sm btn-default add_sph_val">+1.25</button>
                      <button type="button" value="+1.50" class="btn btn-sm btn-default add_sph_val">+1.50</button>
                      <button type="button" value="+1.75" class="btn btn-sm btn-default add_sph_val">+1.75</button>
                      <button type="button" value="+2.00" class="btn btn-sm btn-default add_sph_val">+2.00</button>
                      <button type="button" value="+2.25" class="btn btn-sm btn-default add_sph_val">+2.25</button>
                      <button type="button" value="+2.50" class="btn btn-sm btn-default add_sph_val">+2.50</button>
                      <button type="button" value="+2.75" class="btn btn-sm btn-default add_sph_val">+2.75</button>
                      <button type="button" value="+3.00" class="btn btn-sm btn-default add_sph_val">+3.00</button>
                      <button type="button" value="+3.25" class="btn btn-sm btn-default add_sph_val">+3.25</button>
                      <button type="button" value="+3.50" class="btn btn-sm btn-default add_sph_val">+3.50</button>
                      <button type="button" value="+3.75" class="btn btn-sm btn-default add_sph_val">+3.75</button>
                      <button type="button" value="+4.00" class="btn btn-sm btn-default add_sph_val">+4.00</button>
            </div>
        </div>

      <!-- ////// 6 ///// -->
        <div class="modal-footer d-none text-left tab_6 tbs_val">
          <h5>Near-Vision</h5>
            <div class="btn-group">         
              <button type="button" value="N5" class="btn btn-sm btn-default near_vision_val">N5</button>
              <button type="button" value="N6" class="btn btn-sm btn-default near_vision_val">N6</button>
              <button type="button" value="N8" class="btn btn-sm btn-default near_vision_val">N8</button>
              <button type="button" value="N10" class="btn btn-sm btn-default near_vision_val">N10</button>
              <button type="button" value="N12" class="btn btn-sm btn-default near_vision_val">N12</button>
              <button type="button" value="N14" class="btn btn-sm btn-default near_vision_val">N14</button>
              <button type="button" value="N18" class="btn btn-sm btn-default near_vision_val">N18</button>
              <button type="button" value="N24" class="btn btn-sm btn-default near_vision_val">N24</button>
              <button type="button" value="N36" class="btn btn-sm btn-default near_vision_val">N36</button>
          </div>
        </div>


          
       
      </div>
      <div class="modal-footer">
         <button type="button" class="btn-save" onclick="add_values();" data-dismiss="modal">Add</button>
        <button type="button" class="btn-cancel" data-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
var tab_no=1;
var tab_no_txt=1;
var last_tab_no_txt=1;
function tabs_next()
{
 $('.tbs_val').hide();
  tab_no++;
  tab_no_txt++;
  last_tab_no_txt=tab_no_txt;
  $('.l_tab_f_'+last_tab_no_txt).focus();
  $('.tab_'+tab_no).show();
  if(tab_no=='7')
  {
    tab_no=1;
     $('.l_tab_f_'+last_tab_no_txt).focus();
  }
  else if(tab_no=='6')
  {
    tab_no=6;
  }
  else if(tab_no=='5')
  {
    tab_no=5;
  }
  else if(tab_no=='4')
  {
    tab_no=4;
  }
  else if(tab_no=='3')
  {
    tab_no=3;
  }
  else if(tab_no=='2')
  {
    tab_no=2;
  }
  if(tab_no_txt>=12)
  {
    tab_no_txt=0;
    last_tab_no_txt=12;
  }
   tabs_active(tab_no,tab_no_txt);  
}
function tabs_prev()
{
  $('.tbs_val').hide();
    tab_no--;
  if(tab_no==0)
  {    
    tab_no=6;
  }
  else if(tab_no<0)
  {    
    tab_no=5;
  }

  tab_no_txt--;
  last_tab_no_txt=tab_no_txt;
  if(tab_no_txt<1)
  {    
    tab_no_txt=12;
  }
  $('.tab_'+tab_no).show(); 
 $('.l_tab_f_'+last_tab_no_txt).focus();
}
function tabs_active(tbs,tbtxt)
{
  tab_no=tbs;
  tab_no_txt=tbtxt;
  last_tab_no_txt=tab_no_txt;
  $('.tbs_val').hide();
   $('.tab_'+tab_no).show(); 
  if(tab_no=='6')
  {
    tab_no=0;
  }
}

$('.dist_sph_val').click(function(){
  $('.l_tab_f_'+last_tab_no_txt).val($(this).val());
  nearl_sph_val();
  nearr_sph_val();
  tabs_next();
});

$('.dist_cyl_val').click(function(){
  $('.l_tab_f_'+last_tab_no_txt).val($(this).val());
  if(tab_no_txt==8)
  {
   $('.tab_f_8_1').val($(this).val()); 
  }
  else{
     $('.tab_f_'+tab_no+'_1').val($(this).val());
  }
 
  tabs_next();
});
$('.dist_axis_val').click(function(){
  $('.l_tab_f_'+last_tab_no_txt).val($(this).val());
  if(tab_no_txt==9)
  {
   $('.tab_f_9_1').val($(this).val()); 
  }
  else{
   $('.tab_f_'+tab_no+'_1').val($(this).val());
  }
  tabs_next();
});

$('.dist_vision_val').click(function(){
  $('.l_tab_f_'+last_tab_no_txt).val($(this).val());
  tabs_next();
});

$('.add_sph_val').click(function(){
  $('.l_tab_f_'+last_tab_no_txt).val($(this).val());
  nearl_sph_val();
  nearr_sph_val();
  tabs_next();
});
$('.near_vision_val').click(function(){
  if(last_tab_no_txt==0)
  {    
  $('.l_tab_f_12').val($(this).val());
  }
  else{
    $('.l_tab_f_'+last_tab_no_txt).val($(this).val());
  }
  tabs_next();
});

function nearl_sph_val()
{
  var disph=$('.l_tab_f_1').val();
  var addsph=$('.l_tab_f_5').val();
  if(addsph == '')
    addsph='0.00';
  var nearsph=parseFloat(disph)+parseFloat(addsph);
  if(nearsph>0)
    nearsph='+'+nearsph.toFixed(2); 
  $('.tab_f_14').val(nearsph);
}
function nearr_sph_val()
{
  var disph=$('.l_tab_f_7').val();
  var addsph=$('.l_tab_f_11').val();
   if(addsph == '')
    addsph='0.00';
  var nearsph=parseFloat(disph)+parseFloat(addsph);
   if(nearsph>0)
    nearsph='+'+nearsph.toFixed(2); 
  $('.tab_f_15').val(nearsph);
}

function add_values()
{
 var tab_name='<?php echo $var_name;?>';
  $('#'+tab_name+'_l_dt_sph').val($('.l_tab_f_1').val());
  $('#'+tab_name+'_l_dt_cyl').val($('.l_tab_f_2').val());
  $('#'+tab_name+'_l_dt_axis').val($('.l_tab_f_3').val());
  $('#'+tab_name+'_l_dt_vision').val($('.l_tab_f_4').val());
  $('#'+tab_name+'_l_ad_sph').val($('.l_tab_f_5').val());
  $('#'+tab_name+'_l_ad_vision').val($('.tab_f_16').val());
  $('#'+tab_name+'_l_nr_sph').val($('.tab_f_14').val());
  $('#'+tab_name+'_l_nr_cyl').val($('.tab_f_2_1').val());
  $('#'+tab_name+'_l_nr_axis').val($('.tab_f_3_1').val());
  $('#'+tab_name+'_l_nr_vision').val($('.l_tab_f_6').val());

  $('#'+tab_name+'_r_dt_sph').val($('.l_tab_f_7').val());
  $('#'+tab_name+'_r_dt_cyl').val($('.l_tab_f_8').val());
  $('#'+tab_name+'_r_dt_axis').val($('.l_tab_f_9').val());
  $('#'+tab_name+'_r_dt_vision').val($('.l_tab_f_10').val());
  $('#'+tab_name+'_r_ad_sph').val($('.l_tab_f_11').val());
  $('#'+tab_name+'_r_ad_vision').val($('.tab_f_17').val());
  $('#'+tab_name+'_r_nr_sph').val($('.tab_f_15').val());
  $('#'+tab_name+'_r_nr_cyl').val($('.tab_f_8_1').val());
  $('#'+tab_name+'_r_nr_axis').val($('.tab_f_9_1').val());
  $('#'+tab_name+'_r_nr_vision').val($('.l_tab_f_12').val());
}
</script>


</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->