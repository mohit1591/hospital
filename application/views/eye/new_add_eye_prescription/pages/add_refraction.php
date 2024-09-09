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
            <section style="display:grid;grid-template-columns:1fr 1fr;grid-gap:10px;">
               <aside>
                  <div class="row">
                     <div class="col-md-12">
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
                     <div class="col-md-12">
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
               </aside>
               <aside style="display:flex;justify-content:space-between;width:100%;margin:0 auto;text-align:center;">
                  <div class="modal-footerr mo_plus text-center tab_1 tbs_val" style="width:48%;height:405px;background-color:#f9c0c0">
                      <style>
                          .tbs_val button {width:100%;border:none;background:transparent;}
                      </style>
                      <div>
                          <input type="radio" onclick="$('.sph').hide();$('.sph._plus').show();"> + PLUS &emsp;
                          <input type="radio" onclick="$('.sph').hide();$('.sph._minus').show();"> - MINUS
                      </div>
                      <table width="100%" border="1" class="sph _plus">
                          <thead style="background:#f47f7f;">
                          <tr>
                              <th class="text-center" colspan="2">Distant-Sph (+)</th>
                          </tr>
                          </thead>
                          <tr>
                              <td><button type="button" value="+0.00" class="dist_sph_val">+0.00</button></td>
                              <td><button type="button" value="+4.50" class="dist_sph_val">+4.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+0.25" class="dist_sph_val">+0.25</button></td>
                              <td><button type="button" value="+4.75" class="dist_sph_val">+4.75</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+0.50" class="dist_sph_val">+0.50</button></td>
                              <td><button type="button" value="+5.00" class="dist_sph_val">+5.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+0.75" class="dist_sph_val">+0.75</button></td>
                              <td><button type="button" value="+5.50" class="dist_sph_val">+5.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+1.00" class="dist_sph_val">+1.00</button></td>
                              <td><button type="button" value="+6.00" class="dist_sph_val">+6.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+1.25" class="dist_sph_val">+1.25</button></td>
                              <td><button type="button" value="+6.50" class="dist_sph_val">+6.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+1.50" class="dist_sph_val">+1.50</button></td>
                              <td><button type="button" value="+7.00" class="dist_sph_val">+7.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+1.75" class="dist_sph_val">+1.75</button></td>
                              <td><button type="button" value="+7.50" class="dist_sph_val">+7.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+2.00" class="dist_sph_val">+2.00</button></td>
                              <td><button type="button" value="+8.00" class="dist_sph_val">+8.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+2.25" class="dist_sph_val">+2.25</button></td>
                              <td><button type="button" value="+9.00" class="dist_sph_val">+9.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+2.50" class="dist_sph_val">+2.50</button></td>
                              <td><button type="button" value="+10.00" class="dist_sph_val">+10.00</button></td>
                          </tr>
                          
                          <tr>
                              <td><button type="button" value="+2.75" class="dist_sph_val">+2.75</button></td>
                              <td><button type="button" value="+11.00" class="dist_sph_val">+11.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+3.00" class="dist_sph_val">+3.00</button></td>
                              <td><button type="button" value="+12.00" class="dist_sph_val">+12.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+3.25" class="dist_sph_val">+3.25</button></td>
                              <td><button type="button" value="+13.00" class="dist_sph_val">+13.00</button></td>
                          </tr>
                          
                          <tr>
                              <td><button type="button" value="+3.50" class="dist_sph_val">+3.50</button></td>
                              <td><button type="button" value="+14.00" class="dist_sph_val">+14.00</button></td>
                          </tr>
                          
                          <tr>
                              <td><button type="button" value="+3.75" class="dist_sph_val">+3.75</button></td>
                              <td><button type="button" value="+16.00" class="dist_sph_val">+16.00</button></td>
                          </tr>
                          
                          <tr>
                              <td><button type="button" value="+4.00" class="dist_sph_val">+4.00</button></td>
                              <td><button type="button" value="+18.00" class="dist_sph_val">+18.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+4.25" class="dist_sph_val">+4.25</button></td>
                              <td><button type="button" value="+20.00" class="dist_sph_val">+20.00</button></td>
                          </tr>
                          
                      </table>
                      
                      <table width="100%" border="1" class="sph _minus" style="display:none;">
                          <thead style="background:#f47f7f;">
                          <tr>
                              <th class="text-center" colspan="2">Distant-Sph (-)</th>
                          </tr>
                          </thead>
                          <tr>
                              <td><button type="button" value="-0.00" class="dist_sph_val">-0.00</button></td>
                              <td><button type="button" value="-4.50" class="dist_sph_val">-4.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-0.25" class="dist_sph_val">-0.25</button></td>
                              <td><button type="button" value="-4.75" class="dist_sph_val">-4.75</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-0.50" class="dist_sph_val">-0.50</button></td>
                              <td><button type="button" value="-5.00" class="dist_sph_val">-5.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-0.75" class="dist_sph_val">-0.75</button></td>
                              <td><button type="button" value="-5.50" class="dist_sph_val">-5.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-1.00" class="dist_sph_val">-1.00</button></td>
                              <td><button type="button" value="-6.00" class="dist_sph_val">-6.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-1.25" class="dist_sph_val">-1.25</button></td>
                              <td><button type="button" value="-6.50" class="dist_sph_val">-6.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-1.50" class="dist_sph_val">-1.50</button></td>
                              <td><button type="button" value="-7.00" class="dist_sph_val">-7.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-1.75" class="dist_sph_val">-1.75</button></td>
                              <td><button type="button" value="-7.50" class="dist_sph_val">-7.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-2.00" class="dist_sph_val">-2.00</button></td>
                              <td><button type="button" value="-8.00" class="dist_sph_val">-8.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-2.25" class="dist_sph_val">-2.25</button></td>
                              <td><button type="button" value="-9.00" class="dist_sph_val">-9.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-2.50" class="dist_sph_val">-2.50</button></td>
                              <td><button type="button" value="-10.00" class="dist_sph_val">-10.00</button></td>
                          </tr>
                          
                          <tr>
                              <td><button type="button" value="-2.75" class="dist_sph_val">-2.75</button></td>
                              <td><button type="button" value="-11.00" class="dist_sph_val">-11.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-3.00" class="dist_sph_val">-3.00</button></td>
                              <td><button type="button" value="-12.00" class="dist_sph_val">-12.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-3.25" class="dist_sph_val">-3.25</button></td>
                              <td><button type="button" value="-13.00" class="dist_sph_val">-13.00</button></td>
                          </tr>
                          
                          <tr>
                              <td><button type="button" value="-3.50" class="dist_sph_val">-3.50</button></td>
                              <td><button type="button" value="-14.00" class="dist_sph_val">-14.00</button></td>
                          </tr>
                          
                          <tr>
                              <td><button type="button" value="-3.75" class="dist_sph_val">-3.75</button></td>
                              <td><button type="button" value="-16.00" class="dist_sph_val">-16.00</button></td>
                          </tr>
                          
                          <tr>
                              <td><button type="button" value="-4.00" class="dist_sph_val">-4.00</button></td>
                              <td><button type="button" value="-18.00" class="dist_sph_val">-18.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-4.25" class="dist_sph_val">-4.25</button></td>
                              <td><button type="button" value="-20.00" class="dist_sph_val">-20.00</button></td>
                          </tr>
                          
                      </table>
                     
                     <style>
                        .eye_btnNew {display:grid;grid-template-columns:1fr 1fr 1fr;margin-top:10px;}
                        .eye_btnNew > div {float:left;width: 100%;}
                        .eye_btnNew > div button {float:left;width:100%;height:20px;font-size:12px;border:1px solid #ddd;border-radius:1px;}
                     </style>
                     
                     
                  </div>
                  <!--<div class="modal-footerr dd-none mo_plus text-center tab_1 tbs_val" style="width:48%;height:405px;background:#f9c0c0;">
                     
                     
                     
                     
                     
                     
                  </div>-->
                  <div class="modal-footerr d-none text-center tab_2 tbs_val" style="width:48%;height:405px;background-color:#c1c1fc;">
                     
                     
                     <div>
                          <input type="radio" onclick="$('.dist').hide();$('.dist._pluss').show();"> + PLUS &emsp;
                          <input type="radio" onclick="$('.dist').hide();$('.dist._minuss').show();"> - MINUS
                      </div>
                     
                     <table width="100%" border="1" class="dist _pluss">
                          <thead style="background:#817ffa;">
                          <tr>
                              <th class="text-center" colspan="2">Distant-Cyl (+)</th>
                          </tr>
                          </thead>
                          <tr>
                              <td><button type="button" value="+0.25" class="dist_cyl_val">+0.20</button></td>
                              <td><button type="button" value="+2.75" class="dist_cyl_val">+2.75</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+0.50" class="dist_cyl_val">+0.50</button></td>
                              <td><button type="button" value="+3.00" class="dist_cyl_val">+3.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+0.75" class="dist_cyl_val">+0.75</button></td>
                              <td><button type="button" value="+3.25" class="dist_cyl_val">+3.25</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+1.00" class="dist_cyl_val">+1.00</button></td>
                              <td><button type="button" value="+3.50" class="dist_cyl_val">+3.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+1.25" class="dist_cyl_val">+1.25</button></td>
                              <td><button type="button" value="+3.75" class="dist_cyl_val">+3.75</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+1.50" class="dist_cyl_val">+1.50</button></td>
                              <td><button type="button" value="+4.00" class="dist_cyl_val">+4.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+1.75" class="dist_cyl_val">+1.75</button></td>
                              <td><button type="button" value="+4.50" class="dist_cyl_val">+4.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+2.00" class="dist_cyl_val">+2.00</button></td>
                              <td><button type="button" value="+5.00" class="dist_cyl_val">+5.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+2.25" class="dist_cyl_val">+2.25</button></td>
                              <td><button type="button" value="+5.50" class="dist_cyl_val">+5.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="+2.50" class="dist_cyl_val">+2.50</button></td>
                              <td><button type="button" value="+6.00" class="dist_cyl_val">+6.00</button></td>
                          </tr>
                          
                          
                      </table>
                      
                      
                      <table width="100%" border="1" class="dist _minuss" style="display:none;">
                          <thead style="background:#817ffa;">
                          <tr>
                              <th class="text-center" colspan="2">Distant-Sph (-)</th>
                          </tr>
                          </thead>
                          <tr>
                              <td><button type="button" value="-0.25" class="dist_cyl_val">-0.20</button></td>
                              <td><button type="button" value="-2.75" class="dist_cyl_val">-2.75</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-0.50" class="dist_cyl_val">-0.50</button></td>
                              <td><button type="button" value="-3.00" class="dist_cyl_val">-3.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-0.75" class="dist_cyl_val">-0.75</button></td>
                              <td><button type="button" value="-3.25" class="dist_cyl_val">-3.25</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-1.00" class="dist_cyl_val">-1.00</button></td>
                              <td><button type="button" value="-3.50" class="dist_cyl_val">-3.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-1.25" class="dist_cyl_val">-1.25</button></td>
                              <td><button type="button" value="-3.75" class="dist_cyl_val">-3.75</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-1.50" class="dist_cyl_val">-1.50</button></td>
                              <td><button type="button" value="-4.00" class="dist_cyl_val">-4.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-1.75" class="dist_cyl_val">-1.75</button></td>
                              <td><button type="button" value="-4.50" class="dist_cyl_val">-4.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-2.00" class="dist_cyl_val">-2.00</button></td>
                              <td><button type="button" value="-5.00" class="dist_cyl_val">-5.00</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-2.25" class="dist_cyl_val">-2.25</button></td>
                              <td><button type="button" value="-5.50" class="dist_cyl_val">-5.50</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="-2.50" class="dist_cyl_val">-2.50</button></td>
                              <td><button type="button" value="-6.00" class="dist_cyl_val">-6.00</button></td>
                          </tr>
                          
                          
                      </table>
                      
                      
                      
                      
                     
                  </div>
                 
                  <div class="modal-footerr d-none text-center tab_3 tbs_val" style="width:48%;;height:405px;background-color:#fdf7c0;">
                     
                     <table width="100%" border="1">
                          <thead style="background:#fdf7c0;">
                          <tr>
                              <th class="text-center" colspan="2">Distant-Axis</th>
                          </tr>
                          </thead>
                          <tr>
                              <td><button type="button" value="5" class="dist_axis_val">5</button></td>
                              <td><button type="button" value="95" class="dist_axis_val">95</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="10" class="dist_axis_val">10</button></td>
                              <td><button type="button" value="100" class="dist_axis_val">100</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="15" class="dist_axis_val">15</button></td>
                              <td><button type="button" value="105" class="dist_axis_val">105</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="20" class="dist_axis_val">20</button></td>
                              <td><button type="button" value="110" class="dist_axis_val">110</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="25" class="dist_axis_val">25</button></td>
                              <td><button type="button" value="115" class="dist_axis_val">115</button></td>
                          </tr>
                         <tr>
                              <td><button type="button" value="30" class="dist_axis_val">30</button></td>
                              <td><button type="button" value="120" class="dist_axis_val">120</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="35" class="dist_axis_val">35</button></td>
                              <td><button type="button" value="125" class="dist_axis_val">125</button></td>
                          </tr>
                         <tr>
                              <td><button type="button" value="40" class="dist_axis_val">40</button></td>
                              <td><button type="button" value="130" class="dist_axis_val">130</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="45" class="dist_axis_val">45</button></td>
                              <td><button type="button" value="135" class="dist_axis_val">135</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="50" class="dist_axis_val">50</button></td>
                              <td><button type="button" value="140" class="dist_axis_val">140</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="55" class="dist_axis_val">55</button></td>
                              <td><button type="button" value="145" class="dist_axis_val">145</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="60" class="dist_axis_val">60</button></td>
                              <td><button type="button" value="150" class="dist_axis_val">150</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="65" class="dist_axis_val">65</button></td>
                              <td><button type="button" value="155" class="dist_axis_val">155</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="70" class="dist_axis_val">70</button></td>
                              <td><button type="button" value="160" class="dist_axis_val">160</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="75" class="dist_axis_val">75</button></td>
                              <td><button type="button" value="165" class="dist_axis_val">165</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="80" class="dist_axis_val">80</button></td>
                              <td><button type="button" value="170" class="dist_axis_val">170</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="85" class="dist_axis_val">85</button></td>
                              <td><button type="button" value="175" class="dist_axis_val">175</button></td>
                          </tr>
                          <tr>
                              <td><button type="button" value="90" class="dist_axis_val">90</button></td>
                              <td><button type="button" value="180" class="dist_axis_val">180</button></td>
                          </tr>

                          
                      </table>
                     
                     
                     </div>
                     <div class="modal-footerrr d-none text-center tab_4 tbs_val" style="width:48%;height:405px;background:#c4e5f5;">
                        
                        <table width="100%" border="1">
                          <thead style="background:#aad4e8;">
                          <tr>
                              <th class="text-center" colspan="2">Distant-Vision</th>
                          </tr>
                          </thead>
                          <tr>
                              <td><button type="button" value="1/60" class="dist_vision_val">1/60</button></td>
                              <td><button type="button" value="6/24P" class="dist_vision_val">6/24P</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="2/60" class="dist_vision_val">2/60</button></td>
                              <td><button type="button" value="6/18" class="dist_vision_val">6/18</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="3/60" class="dist_vision_val">3/60</button></td>
                              <td><button type="button" value="6/18P" class="dist_vision_val">6/18P</button></td>
                          </tr>

                           <tr>
                              <td><button type="button" value="4/60" class="dist_vision_val">4/60</button></td>
                              <td><button type="button" value="6/12" class="dist_vision_val">6/12</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="5/60" class="dist_vision_val">5/60</button></td>
                              <td><button type="button" value="6/12P" class="dist_vision_val">6/12P</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="6/60" class="dist_vision_val">6/60</button></td>
                              <td><button type="button" value="6/9" class="dist_vision_val">6/9</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="6/36" class="dist_vision_val">6/36</button></td>
                              <td><button type="button" value="6/9P" class="dist_vision_val">6/9P</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="6/36P" class="dist_vision_val">6/36P</button></td>
                              <td><button type="button" value="6/6" class="dist_vision_val">6/6</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="6/24" class="dist_vision_val">6/24</button></td>
                              <td><button type="button" value="6/6P" class="dist_vision_val">6/6P</button></td>
                          </tr>

                           </table>
                        
                     </div>
                     <div class="modal-footerr d-none text-center tab_5 tbs_val" style="width:48%;height:405px;background:#f0c4f5;">
                        
                        <table width="100%" border="1">
                          <thead style="background:#e695f0;">
                          <tr>
                              <th class="text-center" colspan="2">Add-Sph</th>
                          </tr>
                          </thead>
                          <tr>
                              <td><button type="button" value="+0.00" class="add_sph_val">+0.00</button></td>
                              <td><button type="button" value="+2.25" class="add_sph_val">+2.25</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="+0.25" class="add_sph_val">+0.25</button></td>
                              <td><button type="button" value="+2.50" class="add_sph_val">+2.50</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="+0.50" class="add_sph_val">+0.50</button></td>
                              <td><button type="button" value="+2.75" class="add_sph_val">+2.75</button></td>
                          </tr>

                           <tr>
                              <td><button type="button" value="+0.75" class="add_sph_val">+0.75</button></td>
                              <td><button type="button" value="+3.00" class="add_sph_val">+3.00</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="+1.00" class="add_sph_val">+1.00</button></td>
                              <td><button type="button" value="+3.25" class="add_sph_val">+3.25</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="+1.25" class="add_sph_val">+1.25</button></td>
                              <td><button type="button" value="+3.50" class="add_sph_val">+3.50</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="+1.50" class="add_sph_val">+1.50</button></td>
                              <td><button type="button" value="+3.75" class="add_sph_val">+3.75</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="+1.75" class="add_sph_val">+1.75</button></td>
                              <td> <button type="button" value="+4.00" class="add_sph_val">+4.00</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="+2.00" class="add_sph_val">+2.00</button></td>
                              <td>&nbsp;</td>
                          </tr>

                           </table>
                     </div>
                     <div class="modal-footerr d-none text-center tab_6 tbs_val" style="width:48%;height:405px;background:#c2f0de;">
                        
                        <table width="100%" border="1">
                          <thead style="background:#95f0cc;">
                          <tr>
                              <th class="text-center">Near-Vision</th>
                          </tr>
                          </thead>
                          <tr>
                              <td><button type="button" value="N5" class="near_vision_val">N5</button></td>
                              
                          </tr>

                          <tr>
                              <td><button type="button" value="N6" class="near_vision_val">N6</button></td>
                              
                          </tr>

                          <tr>
                              <td><button type="button" value="N8" class="near_vision_val">N8</button></td>
                              
                          </tr>

                           <tr>
                              <td><button type="button" value="N10" class="near_vision_val">N10</button></td>
                              
                          </tr>

                          <tr>
                              <td><button type="button" value="N12" class="near_vision_val">N12</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="N14" class="near_vision_val">N14</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="N18" class="near_vision_val">N18</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="N24" class="near_vision_val">N24</button></td>
                          </tr>

                          <tr>
                              <td><button type="button" value="N36" class="near_vision_val">N36</button></td>
                          </tr>

                           </table>
                     </div>
               </aside>
            </section>
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
       //alert(tbs);
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
     //nearl_sph_val();
     //nearr_sph_val();
     tabs_next();
   });
   
   $('.dist_cyl_val').click(function(){
     $('.l_tab_f_'+last_tab_no_txt).val($(this).val());
     if(tab_no_txt==8)
     {
      //$('.tab_f_8_1').val($(this).val()); 
     }
     else{
        //$('.tab_f_'+tab_no+'_1').val($(this).val());
     }
    
     tabs_next();
   });
   $('.dist_axis_val').click(function(){
     $('.l_tab_f_'+last_tab_no_txt).val($(this).val());
     if(tab_no_txt==9)
     {
      //$('.tab_f_9_1').val($(this).val()); 
     }
     else{
      //$('.tab_f_'+tab_no+'_1').val($(this).val());
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
     
     var near_twoo=$('.l_tab_f_2').val();
     var near_threee=$('.l_tab_f_3').val();
     
     if(addsph == '')
       addsph='0.00';
     var nearsph=parseFloat(disph)+parseFloat(addsph);
     if(nearsph>0)
       nearsph='+'+nearsph.toFixed(2); 
     $('.tab_f_14').val(nearsph);
     $('.tab_f_2_1').val(near_twoo);
     $('.tab_f_3_1').val(near_threee);
   }
   function nearr_sph_val()
   {
     var disph=$('.l_tab_f_7').val();
     var addsph=$('.l_tab_f_11').val();
     var near_eight=$('.l_tab_f_8').val();
     var near_nine=$('.l_tab_f_9').val();
     
      if(addsph == '')
       addsph='0.00';
     var nearsph=parseFloat(disph)+parseFloat(addsph);
      if(nearsph>0)
       nearsph='+'+nearsph.toFixed(2); 
     $('.tab_f_15').val(nearsph);
     $('.tab_f_8_1').val(near_eight);
     $('.tab_f_9_1').val(near_nine);
     
     
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