<html lang="en">
<head>
  <title><?php echo $page_title; ?></title>
  <style>
    page {
      background: white;
      display: block;
      margin: 0 auto;
      margin-bottom: 0.5cm;
    }
    page[size="A4"] {  
      width: 21cm;
      height: 29.7cm; 
      padding: 2em;
    }
    @page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
    .canvasjs-chart-credit{
      display: none;
    }
    tr >td {
      border: 1px solid lightgrey;
          color: grey;
        font-size: 12px;
    }
    table{
      border-spacing:0px;
          font-size: 12px;
    }
    .row{
     color:  #333333;
     font-size: 16px;
    }
  </style>
</head>
<body style="font-family: sans-serif, Arial;background:#cdcdcd;">
<page size="A4" style="margin-right: 0.25in;margin-left: 0.25in;">
   

  <div class="row" style="float:left;width:100%;min-height:100px;*border:1px solid #eee;clear:both;margin-top:1em;">
    <div style="float:left;width:32%;min-height:100px;margin-right:1.5%;">
      <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;"><?php echo $data= get_setting_value('PATIENT_REG_NO');?> : </div>
        <div style="float:left;width:50%;font-size:small;line-height:17px;"> <?php echo $form_data['patient_code']; ?></div>
      </div>

      <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Patient Name :</div>
        <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['patient_name']; ?></div>
      </div>

      <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Gender/Age :</div>
        <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php 
        $gender = array('0'=>'Female','1'=>'Male');
        echo $gender[$form_data['gender']]; 
        ?>
        / 
        <?php 
        $age = "";
        if($form_data['age_y']>0)
        {
          $year = 'Years';
          if($form_data['age_y']==1)
          {
            $year = 'Year';
          }
          $age .= $form_data['age_y']." ".$year;
        }

        echo $age; 
        $appointment_date='-';
        $booking_date='';

        if(!empty($form_data['appointment_date']) && $form_data['appointment_date']!='0000-00-00')
        {
          $appointment_date = date('d-m-Y',strtotime($form_data['appointment_date']));
        }

        if(!empty($form_data['booking_date']) && $form_data['booking_date']!='0000-00-00')
        {
          $booking_date = date('d-m-Y',strtotime($form_data['booking_date']));
        }

        ?> </div>
      </div>

      <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Gravida :</div>
        <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $partograph_data[0]->gravida; ?></div>
      </div>

      <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Para :</div>
        <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $partograph_data[0]->para; ?></div>
      </div>

    </div>



    <div style="float:right;width:32%;min-height:100px;">
      <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">OPD NO. :</div>
        <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['booking_code']; ?></div>
      </div>

      <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Mobile No. : </div>
        <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['mobile_no']; ?> </div>
      </div>


      <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Date & Time of Admission : </div>
        <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php  echo date('d-m-Y',strtotime($form_data['booking_date'])).' '.date('h:i A',strtotime($form_data['booking_time'])); ?> </div>
      </div>

      <?php 

      $booking_time ='';
      if(!empty($partograph_data[0]->booking_time) && $partograph_data[0]->booking_time!='00:00:00' && strtotime($partograph_data[0]->booking_time)>0)
      {
        $booking_time = date('h:i A', strtotime($partograph_data[0]->booking_time));    
      }


      ?>

      <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Doctor Name :</div>
        <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo get_doctor_name($form_data['attended_doctor']); ?></div>
      </div>

      <?php 

      $rel_simulation = get_simulation_name($form_data['relation_simulation_id']);



      ?>
      <div style="float:left;width:100%;margin-bottom:2px;">
        <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;"><?php echo $form_data['relation']; ?> :</div>
        <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $rel_simulation.' '.$form_data['relation_name']; ?></div>
      </div>

    </div>
  </div>
  <hr>
  <canvas height="11vh" width="40vw"  id="chartContainer1"></canvas>
  <div class="row"> 
  <br>
    <div>Aminoitic Fluid<table><tr id="val_am"></tr><tr id="time_am"></tr></table></div>
  </div>
  <canvas height="8vh" width="40vw" id="chartContainer3"></canvas>
  <canvas height="9vh" width="40vw" id="chartContainer4"></canvas>
   <br>
  <div class="row"> 
    <div>Drugs And LV fluid given<table><tr id="val_drug"></tr><tr id="time_drug"></tr></table></div>
  </div>
  <br>
  <canvas height="10vh" width="40vw" id="chartContainer6"></canvas>
   <br>
  <div class="row"> 
    <div>D) Tempreture (&deg;C)<table><tr id="val_temp"></tr><tr id="time_temp"></tr></table></div>
  </div>
  <div style="display:flex;justify-content:space-between;text-align:center;color:#fff;margin-top:5px;">
    <div style="width:44%;padding:10px;background:#333;border-radius:5px;">Initiate plotting on alert line</div>
    <div style="width:44%;padding:10px;background:#333;border-radius:5px;">Refer to FRU when ALERT LINE is crossed</div>
  </div>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>Chart.min.js"></script>

<script>
$(document).ready(function () {
    var parto_id='<?php echo $this->uri->segment(3); ?>';
    var branch_id='<?php echo $this->uri->segment(4); ?>';
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('partograph/chart_one');?>",
            data: {parto_id: parto_id, branch_id:branch_id},
            success: function(result) 
            {
              var data=JSON.parse(result);
              var score = { 
                TeamA : [] ,
              };
             var len = data.length; 
             glength= data.length; 
             for (var i = 0; i < len; i++) 
             { 
              score.TeamA.push(data[i].value);
             }
              var label = { 
                TeamB : []
              };
              for (var i = 0; i <= 12; i++) { 
                if(data[i] !=undefined)
                label.TeamB.push((i)+';'+data[i].time);
                else
                label.TeamB.push((i)+';');
              }

              var ctx = $("#chartContainer1").css("font-size","30px");
               
              var data = {
                labels : label.TeamB,
                datasets : [
                  {
                    label : "",
                    data : score.TeamA,
                    backgroundColor : "blue",
                    borderColor : "black",
                    fill : false,
                    pointRadius : 5,
                     xAxisID:'xAxis1'
                  }
                  
                ]
              };

              var options = {
                responsive: true,
                  scales: {
                    yAxes: [{
                         scaleLabel: {
                            display: true,
                            labelString: 'Foetal heart rate'
                          },
                        ticks: {
                            min: 80,   // minimum value will be 0.
                            max: 200,
                            stepSize: 10,
                        }
                    }],
                    xAxes: [{
                            id:'xAxis1',
                            ticks:{
                              callback:function(label){
                                var num1 = label.split(";")[0];
                                var time = label.split(";")[1];
                                return num1;
                              }
                            }
                          },
                          {
                            id:'xAxis2',
                            gridLines: {
                              drawOnChartArea: false,
                            },
                            ticks:{
                              callback:function(label){
                                var num1 = label.split(";")[0];
                                var tim = label.split(";")[1];    
                                  return tim;
                              }
                            }
                          }
                        ]
                },
                title : {
                  display : true,
                  position : "top",
                  text : "A) Foetal Condition",
                  fontSize : 20,
                  fontColor : "#111"
                },
                
                legend : {
                  display : false,
                  position : "bottom"
                }
              };

              var chart = new Chart( ctx, {
                type : "line",
                data : data,
                options : options
              } );

              chart.render();
            }
        });

            $.ajax({
            type: "POST",
            url: "<?php echo base_url('partograph/chart_three');?>",
            data: {parto_id: parto_id, branch_id:branch_id},
            success: function(result3) 
            {
              var data=JSON.parse(result3);
               var score = { 
                TeamA : [] ,
              };
              var len = data.length; 
               for (var i = 0; i < len; i++) 
               { 
                score.TeamA.push({'x' : data[i].value_x,'y' : data[i].value});
               }
              
              var ctx = document.getElementById("chartContainer3");
              var myChart = new Chart(ctx, {
                type: 'scatter',
                data: {
                  datasets: [
                    {
                      label: 'Chart 1',
                      data: [{x: 4, y: 4}, {x: 5, y: 5}, {x: 6, y: 6},{x: 7, y: 7},{x: 8, y: 8},{x: 9, y: 9},{x: 10, y: 10}],
                      showLine: true,
                      fill: false,
                      borderColor: 'rgb(128, 128, 128)'
                    },
                    {
                      label: 'Chart 2',
                      data: [{x: 0, y: 4}, {x: 1, y: 5}, {x: 2, y: 6}, {x: 3, y: 7}, {x: 4, y: 8}, {x: 5, y: 9}, {x: 6, y: 10}],
                      showLine: true,
                      fill: false,
                      borderColor: 'rgba(200, 0, 0, 1)'
                    },
                    {
                      type:'bubble',
                      data: score.TeamA,
                      fill: true,
                      backgroundColor: 'rgb(0,0,0)',
                    }

                  ]
                },
                options: {
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                  },
                  hover: {
                    mode: 'nearest',
                    intersect: false
                  },
                  scales: {
                    yAxes: [{
                       scaleLabel: {
                            display: true,
                            labelString: 'Cervic(cm)(Plot X)'
                          },
                       ticks: {
                                min: 4, 
                                max: 10,
                                stepSize: 1,
                              }
                    }],
                    xAxes: [{
                            id:'xAxis1',
                            ticks:{
                               min: 0, 
                                max: 12,
                                stepSize: 1,
                            }
                          }],
                  },
                title : {
                  display : true,
                  position : "top",
                  text : "B) Labour",
                  fontSize : 20,
                  fontColor : "#111"
                },
                legend : {
                  display : false,
                }
                }
              });
            }
        });

           $.ajax({
            type: "POST",
            url: "<?php echo base_url('partograph/chart_four');?>",
            data: {parto_id: parto_id, branch_id:branch_id},
            success: function(result) 
            {
              var data=JSON.parse(result);
              var score = { 
                TeamA : [] ,
              };
             var len = data.length; 
             var myColors=[]; 
             for (var i = 0; i < len; i++) 
             { 
              score.TeamA.push(data[i].value);
               if(data[i].value>2 && data[i].value<=3)
                 {
                    myColors[i]="yellow";
                 }
                 else if(data[i].value>3 && data[i].value<=4)
                 {
                    myColors[i]="lightgreen";
                 }
                 else
                 {
                    myColors[i]="green";
                 }
             }

              var label = { 
                TeamB : []
              };
              for (var i = 0; i <= 12; i++) { 
                if(data[i] !=undefined)
                label.TeamB.push((i)+';'+data[i].time);
                else
                label.TeamB.push((i)+';');
              }

              var ctx = $("#chartContainer4").css("font-size","30px");
               
              var data = {
                labels : label.TeamB,
                datasets : [
                  {
                    label : "",
                    data : score.TeamA,
                    backgroundColor: myColors,
                    borderColor : "black",
                    fill : false,
                    pointRadius : 5,
                    xAxisID:'xAxis1'
                  }
                  
                ]
              };

              var options = {
                responsive: true,
                scales: {
                    yAxes: [{
                         scaleLabel: {
                            display: true,
                            labelString: 'Contraction per 10 min'
                          },
                        ticks: {
                            min: 1,   // minimum value will be 0.
                            max: 5,
                            stepSize: 1,
                        }
                    }],
                    xAxes: [{
                            id:'xAxis1',
                            ticks:{
                              callback:function(label){
                                var num1 = label.split(";")[0];
                                var time = label.split(";")[1];
                                return num1;
                              }
                            }
                          },
                          {
                            id:'xAxis2',
                            gridLines: {
                              drawOnChartArea: false,
                            },
                            ticks:{
                              callback:function(label){
                                var num1 = label.split(";")[0];
                                var tim = label.split(";")[1];    
                                  return tim;
                              }
                            }
                          }
                        ]
                },
                title : {
                  display : true,
                  position : "top",
                  text : "",
                  fontSize : 20,
                  fontColor : "#111"
                },
                legend : {
                  display : false,
                  position : "bottom"
                }
              };

              var chart = new Chart( ctx, {
                type : "bar",
                data : data,
                options : options
              } );

              chart.render();
            }
        });

        

             $.ajax({
            type: "POST",
            url: "<?php echo base_url('partograph/chart_six');?>",
            data: {parto_id: parto_id, branch_id:branch_id},
            success: function(result) 
            {
              var data=JSON.parse(result);
              console.log(data);
               var score = { 
                TeamA : [] ,
              };
              var range = { 
                minRvalue : [] ,
                maxRvalue : [] ,
              };
              var ry=[];
                var len = data.length;   

             


              for (var i = 0; i < len; i++) 
               { 
                score.TeamA.push({'x' : data[i].value_x,'y' : data[i].value});
                ry.push(data[i].value);
               }
             /*  var xn=0.5;
               range.minRvalue.push({'x' : xn,'y' : (Math.min.apply(Math,ry)-10)},{'x' : xn,'y' : (Math.max.apply(Math,ry)+10)});*/
                var rd=[
                    {
                      data: score.TeamA,
                      showLine: true,
                      fill: false,
                      borderColor: 'rgb(169,169,169)'
                    }
                  ];
               for (var i = 0; i < len; i++) 
               { 
                if(data[i].value_low_bp !=0)
                {
                  var range1 = { 
                    minRvalue : []
                  };
                  range1.minRvalue.push({'x' : data[i].value_x,'y' : data[i].value_low_bp },{'x' : data[i].value_x ,'y' : data[i].value_high_bp});
                  rd.push({
                      data: range1.minRvalue,
                      fill: true,
                      borderColor: 'rgba(0,0,0)',
                      pointRadius : 5,
                    });               
                }
               }
              var ctx = $("#chartContainer6").css("font-size","30px");
              var myChart = new Chart(ctx, {
                type: 'scatter',
                lineAtIndex: 1,
                data: {
                  datasets:rd,
                },
                options: {
                   responsive: true,
                title : {
                  display : true,
                  position : "top",
                  text : "C) Material Condition",
                  fontSize : 20,
                  fontColor : "#111"
                },
                legend : {
                  display : false,
                  position : "bottom"
                },
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                  },
                  hover: {
                    mode: 'nearest',
                    intersect: false
                  },
                  scales: {
                    yAxes: [{
                       scaleLabel: {
                            display: true,
                            labelString: 'Pulse and BP'
                          },
                       ticks: {
                                min: 60, 
                                max: 180,
                                stepSize: 10,
                              }
                    }],
                    xAxes: [{
                            id:'xAxis1',
                            ticks:{
                               min: 0, 
                                max: 24,
                                stepSize: 1,
                            }
                          }],
                  },
                  legend : {
                            display : false,
                          }
                }
              });
                             
  /*            var data = {
                labels : label.TeamB,
                datasets : [
                  {
                    label : "",
                    data : score.TeamA,
                    backgroundColor : "blue",
                    borderColor : "black",
                    fill : false,
                    pointRadius : 5,
                    xAxisID:'xAxis1'
                  }
                  
                ]
              };

              var options = {
                responsive: true,
                scales: {
                    yAxes: [{
                         scaleLabel: {
                            display: true,
                            labelString: 'Pulse and BP'
                          },
                        ticks: {
                            min: 60,   // minimum value will be 0.
                            max: 180,
                            stepSize: 10,
                        }
                    }],
                   xAxes: [{
                            id:'xAxis1',
                            ticks:{
                              callback:function(label){
                                var num1 = label.split(";")[0];
                                var time = label.split(";")[1];
                                return num1;
                              }
                            }
                          },
                          {
                            id:'xAxis2',
                            gridLines: {
                              drawOnChartArea: false,
                            },
                            ticks:{
                              callback:function(label){
                                var num1 = label.split(";")[0];
                                var tim = label.split(";")[1];    
                                  return tim;
                              }
                            }
                          }
                        ]
                },
                title : {
                  display : true,
                  position : "top",
                  text : "C) Material Condition",
                  fontSize : 20,
                  fontColor : "#111"
                },
                legend : {
                  display : false,
                  position : "bottom"
                }
              };

              var chart = new Chart( ctx, {
                type : "line",
                data : data,
                options : options
              } );

              chart.render();*/
            }
        });


         $.ajax({
            type: "POST",
            url: "<?php echo base_url('partograph/chart_two');?>",
            data: {parto_id: parto_id, branch_id:branch_id},
            success: function(result) 
            {
              var data=JSON.parse(result);
             $.each(data, function (key, val) {
              $('#val_am').append('<td>'+val.value+'</td>');
              $('#time_am').append('<td>'+val.time+'</td>');
             });
            }
        });         

         $.ajax({
            type: "POST",
            url: "<?php echo base_url('partograph/chart_five');?>",
            data: {parto_id: parto_id, branch_id:branch_id},
            success: function(result) 
            {
              var data=JSON.parse(result);
             $.each(data, function (key, val) {
              $('#val_drug').append('<td>'+val.value+'</td>');
              $('#time_drug').append('<td>'+val.time+'</td>');
             });
            }
        });         
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('partograph/chart_seven');?>",
            data: {parto_id: parto_id, branch_id:branch_id},
            success: function(result) 
            {
              var data=JSON.parse(result);
             $.each(data, function (key, val) {
              $('#val_temp').append('<td>'+val.value+'</td>');
              $('#time_temp').append('<td>'+val.time+'</td>');
             });
            }
        });  
});
</script>

</page>

</body>
</html>