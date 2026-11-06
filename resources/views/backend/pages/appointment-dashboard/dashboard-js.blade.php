<script>
    /*===================================================================================================

 - TEMPLATE : {{ config('app.name') }}
 - DESCRIPTION : MODERN BOOTSTRAP 4 ADMIN TEMPLATE - FULLY RESPONSIVE
 - AUTHOR : {{ config('app.name') }}
 - VERSION : 1.0
 - FILE : DASHBAORD JS

 ===================================================================================================*/
    (function($)
    {
        "use strict";
        $(document).ready(function () {


            //---------------------------------------------------------------------------------------------
            // - CHART ACTIVITY ---------------------------------------------------------------------------
            //---------------------------------------------------------------------------------------------
            var line_chart_data =  {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Appointments',
                        backgroundColor: "rgba(239,43,65,0.2)",
                        borderColor: "#ef2b41",
                        borderWidth: 2,
                        yAxisID: 'y-appointments',
                        data: [
                            <?php
                            foreach($data['appointmentMlist'] as $item){ ?>
                             <?php echo $item.','?>
                         <?php }?>
                        ]
                    }
                    <?php if(!empty($data['monthlyRevenue'])) { ?>,
                    {
                        label: 'Revenue ($)',
                        backgroundColor: "rgba(40,167,69,0.2)",
                        borderColor: "#28a745",
                        borderWidth: 2,
                        yAxisID: 'y-revenue',
                        data: [
                            <?php
                            foreach($data['monthlyRevenue'] as $rev){ ?>
                             <?php echo $rev.','?>
                         <?php }?>
                        ]
                    }
                    <?php } ?>
                ]};

            var line_chart_config = {
                type: 'line',
                data: line_chart_data,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Practice Activity & Collections'
                    },
                    scales: {
                        yAxes: [
                            {
                                id: 'y-appointments',
                                type: 'linear',
                                position: 'left',
                                ticks: { beginAtZero: true, precision: 0 }
                            },
                            {
                                id: 'y-revenue',
                                type: 'linear',
                                position: 'right',
                                gridLines: { drawOnChartArea: false },
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(val) { return '$' + val; }
                                }
                            }
                        ]
                    }
                }
            };

            var line_chart_ctx = document.getElementById("chart-line-activity").getContext("2d");

            new Chart(line_chart_ctx, line_chart_config);
            //---------------------------------------------------------------------------------------------
            // - Bibric CALENDAR ------------------------------------------------------------------------------
            //---------------------------------------------------------------------------------------------

            var today = new Date(),
                month = today.getMonth() + 1,
                year = today.getFullYear(),
                notes =  [
                    { "date": year + "-" + month + "-12", "time" : "15:45 AM", "content": "New Year" },
                    { "date": year + "-" + month + "-25", "time" : "10:30 AM" , "content": "Christmas" }
                ];

            $("#calendar_dark").prototipoCalendar({notes: notes,theme: "light",backgroundColor : "#444"});

            //---------------------------------------------------------------------------------------------
            // - NOTIFICATION -----------------------------------------------------------------------------
            //---------------------------------------------------------------------------------------------

            $.notify("Hi {{Auth::user()->name}} !!  ", {align:"center", verticalAlign:"top",color: "#FFFFFF", background: "#4b5066"});
        });
    })(jQuery);


</script>
