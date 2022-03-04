var blockui_spinner = `<div class="d-flex align-items-center text-primary">
  <strong>Processing...</strong>
  <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
</div>`;

$(document).ajaxStart(function() { Pace.restart(); });

var days_remaining = $('.project-duration_remaining').val();

$('.days_remaining').typed({
  strings: [days_remaining],

  // typing speed
   typeSpeed: 50,
   // time before typing starts
   startDelay: 1,
   // backspacing speed
   backSpeed: 1,
   // shuffle the strings
   shuffle: false,
   // time before backspacing
   backDelay: 500,
   // Fade out instead of backspace
   fadeOut: false,
   fadeOutClass: 'typed-fade-out',
   fadeOutDelay: 500, // milliseconds

   // loop
   loop: true,

   // false = infinite
   loopCount: false,

   // show cursor
   showCursor: false,

   // character for cursor
   cursorChar: "|",

   // attribute to type (null == text)
   attr: null,

   // either html or text
   contentType: 'html'
});

$('.activity-ticker').vTicker({
   speed: 500,
   pause: 3000,
   showItems: 1,
   animation: 'fade',
   mousePause: false,
   height: 0,
   direction: 'up'
});

var ajax_call_timer_activity_logs = function() {
  $.ajax({
      url: 'controllers/data/TimerActivityLogs.php',
      method: 'POST',
      tryCount : 0,
      retryLimit : 3,

      beforeSend: function()
      {
          $('.activity-ticker').html("...");
      },
      success: function(data)
      {
        $('.activity-ticker').html(data);
        $('.activity-ticker').vTicker({
           speed: 500,
           pause: 3000,
           showItems: 1,
           animation: 'fade',
           mousePause: false,
           height: 0,
           direction: 'up'
        });
      },
      error: function(xhr)
      {
        $('.activity-ticker').html("<p class='text-danger'>Failed to load recent activity logs. Retrying...</p>");
        this.tryCount++;
            if (this.tryCount <= this.retryLimit) {
                //try again
                $.ajax(this);
                return;
            }
      }
  });
};

var interval = 1000 * 60 * 1; // call every minute

setInterval(ajax_call_timer_activity_logs, interval);


function LoadDatatables()
{
  $('#user-profile-sign-in-logs-table').DataTable({
        destroy: true,
    });
    $('#departmental-objectives-table').DataTable({
      destroy: true,
    });
    $('#risks-management-table').DataTable({
          destroy: true,
      });
      $('#risks-management-opportunity-table').DataTable({
            destroy: true,

      });
      $('#risks-management-information-table').DataTable({
        destroy: true,
      });
      $('#update-risks-management-information-table').DataTable({
        destroy: true,
      });
      $('#delegations-table').DataTable({
        destroy: true,
      });
      $('#emerging-trends-table').DataTable({
        destroy: true,
      });
      $('#lessons-learnt-table').DataTable({
        destroy: true,
      });
      $('#lessons-learnt-strategies-that-did-not-work-table').DataTable({
        destroy: true,
      });
      $('#lessons-learnt-near-misses-table').DataTable({
        destroy: true,
      });
      $('#incident-reports-table').DataTable({
        destroy: true,
      });
      $('#new-risk-approval-table').DataTable({
        destroy: true,
      });
      $('#quarterly-updates-risk-approval-table').DataTable({
        destroy: true,
      });
      $('#new-risk-approved-table').DataTable({
        destroy: true,
      });
      $('#quarterly-updates-risk-approved-table').DataTable({
        destroy: true,
      });
      $('#new-risk-rejected-table').DataTable({
        destroy: true,
      });
      $('#quarterly-updates-risk-rejected-table').DataTable({
        destroy: true,
      });
      $('#corporate-risks-heatmap-table').DataTable({
        destroy: true,
      });
      $('#dashboard-overdue-tasks-table').DataTable({
        destroy: true,
         "pageLength": 5
      });

      $('#dashboard-active-stocks-table').DataTable({
        destroy: true,
         "pageLength": 5
      });

      $('#dashboard-overdue-tasks-table2').DataTable({
        destroy: true,
         "pageLength": 5
      });

      $('#dashboard-overdue-tasks-table3').DataTable({
        destroy: true,
         "pageLength": 5
      });
      $('#ldap-users-table').DataTable({
        destroy: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                title:'LDAP USERS',
                messageTop: 'Capital Markets Authority',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                title:'LDAP USERS',
                messageTop: 'Capital Markets Authority',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                title:'LDAP USERS',
                messageTop: 'Capital Markets Authority',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title:'LDAP USERS',
                messageTop: 'Capital Markets Authority',
                exportOptions: {
                    columns: ':visible'
                }
            },
             {
                extend: 'print',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title:'LDAP USERS',
                messageTop: 'Capital Markets Authority',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis','colvisRestore'
            ],
            columnDefs: [ {
                visible: false,


            } ],

        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="select2 form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                } );
            } );
        }

        });

      $('#timeline-table').DataTable({
        destroy: true,
      });
      $('#user-profile-navigations-table').DataTable({
        destroy: true,
      });
      $('#admin-logs-navigations-table').DataTable({
        destroy: true,
      });
      $('#admin-logs-activity-table').DataTable({
        destroy: true,
      });
      $('#database-backup-table').DataTable({
        destroy: true,
      });
      $('#system-backup-table').DataTable({
        destroy: true,
      });
      $('#feedback-receiver-table').DataTable({
        destroy: true,
      });
      $('#user-feedback-table').DataTable({
        destroy: true,
      });

      //start dataTable reports
      var table = $('#all_unique_risks_under_strategic_objectives_table').DataTable({
        destroy: true
      });
      var table = $('#all_unique_risks_under_directorate_table').DataTable({
        destroy: true
      });
      var table = $('#all_unique_risks_under_departments_table').DataTable({
        destroy: true
      });
      var table = $('#all_activities_without_related_risks_table').DataTable({
        destroy: true
      });
      var table = $('#all_activities_with_related_risks_table').DataTable({
        destroy: true
      });
      var table = $('#detailed_activities_related_risks_table').DataTable({
        destroy: true
      });
      var table = $('#detailed_corporate_activities_related_risks_table').DataTable({
        destroy: true
      });
      var table = $('#corporate-risks-heatmap-table').DataTable({
        destroy: true
      });
      var table = $('#risks-heatmap-table').DataTable({
        destroy: true
      });
      var table = $('#detailed-analysis-table').DataTable({
        destroy: true
      });
      var table = $('#risks-heatmap-opportunities-table').DataTable({
        destroy: true
      });
      var table = $('#detailed_analysis_opportunities_table').DataTable({
        destroy: true
      });
      var table = $('#directorate_risks_with_activities_table').DataTable({
        destroy:true
      });
      var table = $('#invoice-received-table').DataTable({
        destroy:true
      });
      var table = $('#stock-list-table').DataTable({
        destroy:true
      });
      //end dataTable reports

      // Invoice  payments table

      $('#invoice-payments-list-table').DataTable({

         dom: 'Bfrtip',
         buttons: [

             {
                 extend: 'excel',
                 footer: true ,
                 exportOptions: {
                     columns: [0,1,2,3,4,5]
                 }
             },
             {
                 extend: 'pdf',
                 footer: true ,
                 orientation: 'potrait',
                 pageSize: 'LEGAL',
                 exportOptions: {
                     columns: [0,1,2,3,4,5]
                 }
             },

                 {
                 extend: 'print',
                 orientation: 'potrait',
                 pageSize: 'LEGAL',
                 title:'Payment Schedule',
                 messageTop: 'Project Milestone Payments',
                 exportOptions: {
                     columns: ':visible'
                 }
             },
           ],
      "footerCallback": function ( row, data, start, end, display ) {
             var api = this.api(), data;

             // converting to interger to find total
             var intVal = function ( i ) {
                 return typeof i === 'string' ?
                     i.replace(/[\$,]/g, '')*1 :
                     typeof i === 'number' ?
                         i : 0;
             };
                 // computing column Total of the complete result


                     var payment_due = api
                     .column( 3)
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );



                 // Update footer by showing the total with the reference of the column index

                 $( api.column( 0).footer() ).html('Totals');
               $( api.column( 3 ).footer() ).html(payment_due);

             },

          "pageLength": 5
      });

      //Total stoc



//Total individual stock
$(document).on('change', '.stock_unit_price', function() {


var unit_price = $('.stock_unit_price').val();
  var previous_stock_remaining = $('.previous_stock_remaining').val();
var qtt = $('.stock_qtt').val();


var total = unit_price * qtt;
  var new_stock_remaining = previous_stock_remaining - qtt;

  $('.stock_total').val(total.toFixed(2));
  $('.new_stock_remaining').val(new_stock_remaining.toFixed(0));

  var order_level_qtt = $('.order_level_qtt').val();
  var order_level_color = $('.order_level_color').val();
  var new_stock_remaining = $('.new_stock_remaining').val();

  if(new_stock_remaining < order_level_qtt)
  {
  $('.order_level_color').val(one);
  }
  if(new_stock_remaining > order_level_qtt)
  {
  $('.order_level_color').val(two);
  }



/*if ($('.stock_qtt').val() > $('.previous_stock_remaining').val())
  {
    Toast.fire({
          icon: 'error',
          title: 'No stock available. Kindly Raise an LPO'
      });

    }
    else {
    */

    //  $('.new_stock_remaining').val(new_stock_remaining.toFixed(0));
    //  $('.stock_total').val(total.toFixed(2));
    //}

});

$(document).on('change', '.stock_qtt', function() {

  var previous = $('.previous_stock_remaining').val();
  var current = $('.stock_qtt').val();
    if (previous< current) {
    $('#quantity_help').html('Not Possible to Deliver more than Production').css('color', 'red');
  }
  else {

  }



var unit_price = $('.stock_unit_price').val();
var qtt = $('.stock_qtt').val();
  var previous_stock_remaining = $('.previous_stock_remaining').val();

var total = unit_price * qtt;
var new_stock_remaining = previous_stock_remaining - qtt;

/*if ($('.stock_qtt').val() > $('.previous_stock_remaining').val()) {
  Toast.fire({
        icon: 'error',
        title: 'No stock available. Kindly Raise an LPO'
    });
}
else
{
*/

  $('.stock_total').val(total.toFixed(2));
  $('.new_stock_remaining').val(new_stock_remaining.toFixed(0));

//  }

});

// calculation for stock request
$(document).on('change', '.stock_qtt234', function() {

var previous = $('.previous_stock_remaining234').val();
var current = $('.stock_qtt234').val();
  if (previous< current) {
  $('#quantity_help').html('Quantity Requested is below Available Quantity').css('color', 'red');

}
else {

}


var unit_price234 = $('.stock_unit_price234').val();
var qtt234 = $('.stock_qtt234').val();
  var previous_stock_remaining234 = $('.previous_stock_remaining234').val();

var total234 = unit_price234 * qtt234;
var new_stock_remaining234 = previous_stock_remaining234 - qtt234;

/*if ($('.stock_qtt').val() > $('.previous_stock_remaining').val()) {
  Toast.fire({
        icon: 'error',
        title: 'No stock available. Kindly Raise an LPO'
    });
}
else
{
*/

  $('.stock_total234').val(total234.toFixed(2));
  $('.new_stock_remaining234').val(new_stock_remaining234.toFixed(0));

//  }

});

// calculation for stock Returns
$(document).on('change', '.stock_qtt2345', function() {

/*var previous = $('.new_request').val();
var current = $('.stock_qtt2345').val();
  if (previous < current) {
  $('#quantity_help1').html('Quantity Returned is below quantity Previously requested').css('color', 'red');
}
else {

}
*/
var unit_price234 = $('.stock_unit_price234').val();
var qtt234 = $('.stock_qtt2345').val();
  var previous_stock_remaining234 = $('.previous_stock_remaining234').val();
    var previous_stock_requested = $('.previous_stock_requested').val();

var total234 = unit_price234 * qtt234;
var new_stock_remaining234 = +previous_stock_remaining234 + +qtt234;

var new_request = previous_stock_requested - qtt234;


/*if ($('.stock_qtt').val() > $('.previous_stock_remaining').val()) {
  Toast.fire({
        icon: 'error',
        title: 'No stock available. Kindly Raise an LPO'
    });
}
else
{
*/

  $('.stock_total234').val(total234.toFixed(2));
  $('.new_stock_remaining234').val(new_stock_remaining234.toFixed(0));
    $('.new_request').val(new_request.toFixed(0));

//  }

});

// calculation for deliveries Returns

$(document).on('change', '.stock_qtt23456', function() {

/*var previous = $('.new_request').val();
var current = $('.stock_qtt2345').val();
  if (previous < current) {
  $('#quantity_help12').html('Quantity Returned is below quantity Previously Delivered').css('color', 'red');
}
else {

}
*/

var unit_price234 = $('.stock_unit_price234').val();
var qtt234 = $('.stock_qtt2345').val();
  var previous_stock_remaining234 = $('.previous_stock_remaining234').val();
    var previous_stock_requested = $('.previous_stock_requested').val();

var total234 = unit_price234 * qtt234;
var new_stock_remaining234 = +previous_stock_remaining234 + +qtt234;

var new_request = previous_stock_requested - qtt234;


/*if ($('.stock_qtt').val() > $('.previous_stock_remaining').val()) {
  Toast.fire({
        icon: 'error',
        title: 'No stock available. Kindly Raise an LPO'
    });
}
else
{
*/

  $('.stock_total234').val(total234.toFixed(2));
  $('.new_stock_remaining234').val(new_stock_remaining234.toFixed(0));
    $('.new_request').val(new_request.toFixed(0));

//  }

});


$(document).on('change', '.stock_total', function() {

  var unit_price = $('.stock_unit_price').val();
  var qtt = $('.stock_qtt').val();
    var previous_stock_remaining = $('.previous_stock_remaining').val();

  var total = unit_price * qtt;
  var new_stock_remaining = previous_stock_remaining - qtt;

  /*if ($('.stock_qtt').val() > $('.previous_stock_remaining').val()) {
    Toast.fire({
          icon: 'error',
          title: 'No stock available. Kindly Raise an LPO'
      });
  }
  else
  {
  */

    $('.stock_total').val(total.toFixed(2));
    $('.new_stock_remaining').val(new_stock_remaining.toFixed(0));

  //  }


});



$(document).on('change', '.btn_submit_total', function() {

  var unit_price = $('.stock_unit_price').val();
  var qtt = $('.stock_qtt').val();
    var previous_stock_remaining = $('.previous_stock_remaining').val();

  var total = unit_price * qtt;
  var new_stock_remaining = previous_stock_remaining - qtt;

/*  if ($('.stock_qtt').val() > $('.previous_stock_remaining').val()) {
    Toast.fire({
          icon: 'error',
          title: 'No stock available. Kindly Raise an LPO'
      });
  }
  else
  {
  */

    $('.stock_total').val(total.toFixed(2));
    $('.new_stock_remaining').val(new_stock_remaining.toFixed(0));

    //}


});

//Total for updating Stock qtt
//Total individual stock
$(document).on('change', '.stock_unit_price2', function() {


var unit_price = $('.stock_unit_price2').val();
  var previous_stock_remaining = $('.previous_stock_remaining2').val();
var qtt = $('.stock_qtt2').val();


var total = unit_price * qtt;
  var new_stock_remaining = +previous_stock_remaining + +qtt;

  $('.stock_total2').val(total.toFixed(2));
  $('.new_stock_remaining2').val(new_stock_remaining.toFixed(0));

  var order_level_qtt = $('.order_level_qtt').val();
  var order_level_color = $('.order_level_color').val();
  var new_stock_remaining = $('.new_stock_remaining2').val();

  if(new_stock_remaining < order_level_qtt)
  {
  $('.order_level_color').val(one);
  }
  if(new_stock_remaining > order_level_qtt)
  {
  $('.order_level_color').val(two);
  }



/*if ($('.stock_qtt').val() > $('.previous_stock_remaining').val())
  {
    Toast.fire({
          icon: 'error',
          title: 'No stock available. Kindly Raise an LPO'
      });

    }
    else {
    */

    //  $('.new_stock_remaining').val(new_stock_remaining.toFixed(0));
    //  $('.stock_total').val(total.toFixed(2));
    //}


});

$(document).on('change', '.stock_qtt2', function() {


var unit_price = $('.stock_unit_price2').val();
var qtt = $('.stock_qtt2').val();
  var previous_stock_remaining = $('.previous_stock_remaining2').val();

var total = unit_price * qtt;
var new_stock_remaining = +previous_stock_remaining + +qtt;

/*if ($('.stock_qtt').val() > $('.previous_stock_remaining').val()) {
  Toast.fire({
        icon: 'error',
        title: 'No stock available. Kindly Raise an LPO'
    });
}
else
{
*/

  $('.stock_total2').val(total.toFixed(2));
  $('.new_stock_remaining2').val(new_stock_remaining.toFixed(0));

//  }

});

$(document).on('change', '.stock_total2', function() {

  var unit_price = $('.stock_unit_price2').val();
  var qtt = $('.stock_qtt2').val();
    var previous_stock_remaining = $('.previous_stock_remaining2').val();

  var total = unit_price * qtt;
  var new_stock_remaining = +previous_stock_remaining + +qtt;

  /*if ($('.stock_qtt').val() > $('.previous_stock_remaining').val()) {
    Toast.fire({
          icon: 'error',
          title: 'No stock available. Kindly Raise an LPO'
      });
  }
  else
  {
  */

    $('.stock_total2').val(total.toFixed(2));
    $('.new_stock_remaining2').val(new_stock_remaining.toFixed(0));

  //  }


});

$(document).on('change', '.btn_submit_total', function() {

  var unit_price = $('.stock_unit_price2').val();
  var qtt = $('.stock_qtt2').val();
    var previous_stock_remaining = $('.previous_stock_remaining2').val();

  var total = unit_price * qtt;
  var new_stock_remaining = +previous_stock_remaining + +qtt;

/*  if ($('.stock_qtt').val() > $('.previous_stock_remaining').val()) {
    Toast.fire({
          icon: 'error',
          title: 'No stock available. Kindly Raise an LPO'
      });
  }
  else
  {
  */

    $('.stock_total2').val(total.toFixed(2));
    $('.new_stock_remaining2').val(new_stock_remaining.toFixed(0));

    //}


});
// tOTAL end for updating stock qtt


$(document).on('change', '.unit_price', function() {

  var unit_price = $('.unit_price').val();
  var qtt = $('.qtt').val();

  var total = unit_price * qtt;

  $('.total').val(total.toFixed(2));


});

$(document).on('change', '.qtt', function() {

  var unit_price = $('.unit_price').val();
  var qtt = $('.qtt').val();

  var total = unit_price * qtt;

  $('.total').val(total.toFixed(2));


});

$(document).on('change', '.total', function() {

  var unit_price = $('.unit_price').val();
  var qtt = $('.qtt').val();

  var total = unit_price * qtt;

  $('.total').val(total.toFixed(2));


});

// tOTAL VALUE FOR ADD additional Stock VALUE


$(document).on('change', '.unit_price2', function() {

  var unit_price2 = $('.unit_price2').val();
    var previous_stock_remaining2 = $('.previous_stock_remaining2').val();
  var qtt2 = $('.qtt2').val();

var total2 = unit_price2 * qtt2;
  var new_stock_remaining2 = previous_stock_remaining2 + qtt2;

  $('.total2').val(total2.toFixed(2));
  $('.new_stock_remaining2').val(new_stock_remaining2.toFixed(2));


});

$(document).on('change', '.qtt2', function() {

  var unit_price = $('.unit_price2').val();
    var previous_stock_remaining2 = $('.previous_stock_remaining2').val();
  var qtt = $('.qtt2').val();

var total2 = unit_price2 * qtt2;
  var new_stock_remaining2 = previous_stock_remaining2 + qtt2;

  $('.total2').val(total2.toFixed(2));
  $('.new_stock_remaining2').val(new_stock_remaining2.toFixed(2));
});

$(document).on('change', '.total2', function() {

  var unit_price2 = $('.unit_price2').val();
    var previous_stock_remaining2 = $('.previous_stock_remaining2').val();
  var qtt2 = $('.qtt2').val();

var total2 = unit_price2 * qtt2;
  var new_stock_remaining2 = previous_stock_remaining2 + qtt2;

  $('.total2').val(total2.toFixed(2));
  $('.new_stock_remaining2').val(new_stock_remaining2.toFixed(2));
});


      //all risks table
    $('#all_risks').DataTable({
    destroy:true,
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'copy',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title:'RISK REGISTER',
            messageTop: 'Capital Markets Authority',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'csv',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title:'RISK REGISTER',
            messageTop: 'Capital Markets Authority',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'excel',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title:'RISK REGISTER',
            messageTop: 'Capital Markets Authority',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'pdf',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title:'RISK REGISTER',
            messageTop: 'Capital Markets Authority',
            exportOptions: {
                columns: ':visible'
            }
        },
         {
            extend: 'print',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title:'RISK REGISTER',
            messageTop: 'Capital Markets Authority',
            exportOptions: {
                columns: ':visible'
            }
        },
        'colvis','colvisRestore'
        ],
        columnDefs: [ {
            visible: false,


        } ],

    initComplete: function () {
        this.api().columns().every( function () {
            var column = this;
            var select = $('<select class="select2 form-control"><option value=""></option></select>')
                .appendTo( $(column.footer()).empty() )
                .on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                } );

            column.data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>' );
            } );
        } );
    }

});

// project milestone payments

$('#project-milestone-payments-table').DataTable({

   dom: 'Bfrtip',
   buttons: [

       {
           extend: 'excel',
           footer: true ,
           exportOptions: {
               columns: [0,1,2,3,4,5,6,7]
           }
       },
       {
           extend: 'pdf',
           footer: true ,
           orientation: 'landscape',
           pageSize: 'LEGAL',
           exportOptions: {
               columns: [0,1,2,3,4,5,6,7]
           }
       },

           {
           extend: 'print',
           orientation: 'landscape',
           pageSize: 'LEGAL',
           title:'Payment Schedule',
           messageTop: 'Project Milestone Payments',
           exportOptions: {
               columns: ':visible'
           }
       },
     ],
"footerCallback": function ( row, data, start, end, display ) {
       var api = this.api(), data;

       // converting to interger to find total
       var intVal = function ( i ) {
           return typeof i === 'string' ?
               i.replace(/[\$,]/g, '')*1 :
               typeof i === 'number' ?
                   i : 0;
       };
           // computing column Total of the complete result


               var payment_due = api
               .column( 1)
               .data()
               .reduce( function (a, b) {
                   return intVal(a) + intVal(b);
               }, 0 );

               var costKsh = api
               .column( 4)
               .data()
               .reduce( function (a, b) {
                   return intVal(a) + intVal(b);
               }, 0 );

               var costUsd = api
               .column( 5)
               .data()
               .reduce( function (a, b) {
                   return intVal(a) + intVal(b);
               }, 0 );

               var kshInvoice = api
               .column( 6)
               .data()
               .reduce( function (a, b) {
                   return intVal(a) + intVal(b);
               }, 0 );

               var usdInvoice = api
               .column( 7)
               .data()
               .reduce( function (a, b) {
                   return intVal(a) + intVal(b);
               }, 0 );


           // Update footer by showing the total with the reference of the column index

           $( api.column( 0).footer() ).html('Totals');
         $( api.column( 1 ).footer() ).html(payment_due);
       $( api.column( 4 ).footer() ).html(costKsh);
       $( api.column( 5 ).footer() ).html(costUsd);
       $( api.column( 6 ).footer() ).html(kshInvoice);
       $( api.column( 7 ).footer() ).html(usdInvoice);

       },

    "pageLength": 10
});

$('#all_risks tfoot tr').insertAfter($('#all_risks thead tr'));
//end all risks table

          //staff users table
        $('#staff-users-table').DataTable({
        destroy:true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':visible'
                }
            },
             {
                extend: 'print',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title:'PPRMIS USERS',
                messageTop: 'Capital Markets Authority',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis','colvisRestore'
            ],
            columnDefs: [ {
                visible: false,


            } ],

        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="select2 form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                } );
            } );
        }

    });
    $('#staff-users-table tfoot tr').insertAfter($('#staff-users-table thead tr'));
    //end of staff users table

    //start sign in logs table
    $('#sign-in-logs-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':visible'
                }
            },
             {
                extend: 'print',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title:'PPRMIS SIGN IN LOGS',
                messageTop: 'Capital Markets Authority',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis','colvisRestore'
            ],
            columnDefs: [ {
                visible: false,


            } ],

        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="select2 form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                } );
            } );
        }

    });
    $('#sign-in-logs-table tfoot tr').insertAfter($('#sign-in-logs-table thead tr'));
    //end sign in logs table


    //start mail logs table
    $('#mail-logs-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':visible'
                }
            },
             {
                extend: 'print',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title:'PPRMIS MAIL LOGS',
                messageTop: 'Capital Markets Authority',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis','colvisRestore'
            ],
            columnDefs: [ {
                visible: false,


            } ],

        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="select2 form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                } );
            } );
        }

    });
    $('#mail-logs-table tfoot tr').insertAfter($('#mail-logs-table thead tr'));

    //end mail logs table



      //analysis tables
      $('#analysis-all-activities-table').DataTable({
        destroy: true,
      });
      $('#analysis-all-risks-table').DataTable({
        destroy: true,
      });
      $('#new-risk-approved-table').DataTable({
        destroy: true,
      });
      $('#analysis-closed-activities-table').DataTable({
        destroy: true,
      });
      $('#analysis-closed-risks-table').DataTable({
        destroy: true,
      });
      $('#analysis-downgraded-risks-table').DataTable({
        destroy: true,
      });
      $('#analysis-new-risks-table').DataTable({
        destroy: true,
      });
      $('#analysis-static-activities-table').DataTable({
        destroy: true,
      });
      $('#analysis-static-risks-table').DataTable({
        destroy: true,
      });
      $('#analysis-upgraded-activities-table').DataTable({
        destroy: true,
      });
      $('#analysis-upgraded-risks-table').DataTable({
        destroy: true,
      });
      //end analysis tables

      var table = $('#all_unique_risks_under_strategic_objectives_table').DataTable({
        destroy: true
      });
      var table = $('#all_unique_risks_under_directorate_table').DataTable({
        destroy: true
      });
      var table = $('#all_unique_risks_under_departments_table').DataTable({
        destroy: true
      });
      var table = $('#all_activities_without_related_risks_table').DataTable({
        destroy: true
      });
      var table = $('#all_activities_with_related_risks_table').DataTable({
        destroy: true
      });
      var table = $('#detailed_activities_related_risks_table').DataTable({
        destroy: true
      });
      var table = $('#detailed_corporate_activities_related_risks_table').DataTable({
        destroy: true
      });
      var table = $('#corporate-risks-heatmap-table').DataTable({
        destroy: true
      });
      var table = $('#risks-heatmap-table').DataTable({
        destroy: true
      });
      var table = $('#detailed-analysis-table').DataTable({
        destroy: true
      });
      var table = $('#risks-heatmap-opportunities-table').DataTable({
        destroy: true
      });
      var table = $('#detailed_analysis_opportunities_table').DataTable({
        destroy: true
      });
      var table = $('#directorate_risks_with_activities_table').DataTable({
        destroy:true
      });
      var table = $('#end-product-table').DataTable({
        destroy:true
      });

      // invoice payment table for debits
      $('.invoice-payment-table').DataTable({
      destroy: true,
      dom: 'Bfrtip',
      buttons: [

          {
              extend: 'excel',
              footer: true ,
              exportOptions: {
                  columns: [0,1,2,3,4,5]
              }
          },
          {
              extend: 'pdf',
              footer: true ,
              orientation: 'potrait',
              pageSize: 'LEGAL',
              exportOptions: {
                  columns: [0,1,2,3,4,5]
              }
          },
        ],
        "footerCallback": function ( row, data, start, end, display ) {
              var api = this.api(), data;

              // converting to interger to find total
              var intVal = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
              };

              // computing column Total of the complete result



        var total_debit= api
                  .column( 3 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );


              // Update footer by showing the total with the reference of the column index
        $( api.column( 1 ).footer() ).html('TOTAL');

              $( api.column( 3 ).footer() ).html(total_debit);

          },
  });

  // supplier delivery gooods invoice_doc
    $('.supplier-delivery-table').DataTable({
    destroy: true,
    dom: 'Bfrtip',
    buttons: [

        {
            extend: 'excel',
            footer: true ,
            exportOptions: {
                columns: [0,1,2,3,4,5]
            }
        },
        {
            extend: 'pdf',
            footer: true ,
            orientation: 'potrait',
            pageSize: 'LEGAL',
            exportOptions: {
                columns: [0,1,2,3,4,5]
            }
        },
      ],
      "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // computing column Total of the complete result



      var qtt = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                var total= api
                          .column( 5 )
                          .data()
                          .reduce( function (a, b) {
                              return intVal(a) + intVal(b);
                          }, 0 );


            // Update footer by showing the total with the reference of the column index
      $( api.column( 1 ).footer() ).html('TOTAL');

            $( api.column( 3).footer() ).html(qtt);
            $( api.column( 5 ).footer() ).html(total);

        },
});


  // Stock list grand total computation

  $('#stock-list-computation-table').DataTable({
  destroy: true,
  dom: 'Bfrtip',
  buttons: [
       {
           extend: 'colvis',
           text: 'Filter Columns',
           collectionLayout: 'fixed two-column',
           exportOptions: {
               columns: ':visible'
           },
       },
           ],
    "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api(), data;

          // converting to interger to find total
          var intVal = function ( i ) {
              return typeof i === 'string' ?
                  i.replace(/[\$,]/g, '')*1 :
                  typeof i === 'number' ?
                      i : 0;
          };

          // computing column Total of the complete result

    var total_qtt= api
              .column( 5 )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );


              var total_price= api
                        .column( 8 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );


          // Update footer by showing the total with the reference of the column index


          // Update footer

    $( api.column( 1 ).footer() ).html('TOTAL');

          $( api.column( 5 ).footer() ).html(total_qtt);
          $( api.column( 8 ).footer() ).html('Ksh '+total_price);
        // var formatted = $.fn.dataTable.render.number(',','.','2','$').display(total_price)




      },
});

// REPORTS Datatables
$('#stock-list-computation-table2').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [
     {
         extend: 'colvis',
         text: 'Filter Columns',
         collectionLayout: 'fixed two-column',
         exportOptions: {
             columns: ':visible'
         },
     },
     {
         extend: 'excel',
         footer: true ,
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8]
         }
     },
     {
         extend: 'pdf',
         footer: true ,
         orientation: 'potrait',
         pageSize: 'LEGAL',
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8]
         }
     },
         ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result

  var total_qtt= api
            .column( 5 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );




                                var total_price2= api
                                          .column( 8 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );

        // Update footer by showing the total with the reference of the column index


        // Update footer

  $( api.column( 1 ).footer() ).html('TOTAL');

        $( api.column( 5 ).footer() ).html(total_qtt);

        $( api.column( 8 ).footer() ).html('Ksh '+total_price2);
      // var formatted = $.fn.dataTable.render.number(',','.','2','$').display(total_price)




    },
});

$('#all_end_products_reports').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [
     {
         extend: 'colvis',
         text: 'Filter Columns',
         collectionLayout: 'fixed two-column',
         exportOptions: {
             columns: ':visible'
         },
     },
     {
         extend: 'excel',
         footer: true ,
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8,9,10]
         }
     },
     {
         extend: 'pdf',
         footer: true ,
         orientation: 'potrait',
         pageSize: 'LEGAL',
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8,9,10]
         }
     },
         ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result

  var total_qtt= api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

                                var total_price= api
                                          .column( 4 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );
                                          var total_qtt2= api
                                                    .column( 5 )
                                                    .data()
                                                    .reduce( function (a, b) {
                                                        return intVal(a) + intVal(b);
                                                    }, 0 );

                                                                        var total_price2= api
                                                                                  .column( 6 )
                                                                                  .data()
                                                                                  .reduce( function (a, b) {
                                                                                      return intVal(a) + intVal(b);
                                                                                  }, 0 );
        // Update footer by showing the total with the reference of the column index


        // Update footer

  $( api.column( 1 ).footer() ).html('TOTAL');

        $( api.column( 3 ).footer() ).html(total_qtt);
        $( api.column( 4 ).footer() ).html(total_price);
        $( api.column( 5 ).footer() ).html(total_qtt2);

        $( api.column( 8 ).footer() ).html('Ksh '+total_price2);
      // var formatted = $.fn.dataTable.render.number(',','.','2','$').display(total_price)




    },
});

$('#pending_invoices_report').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [
     {
         extend: 'colvis',
         text: 'Filter Columns',
         collectionLayout: 'fixed two-column',
         exportOptions: {
             columns: ':visible'
         },
     },
     {
         extend: 'excel',
         footer: true ,
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8]
         }
     },
     {
         extend: 'pdf',
         footer: true ,
         orientation: 'potrait',
         pageSize: 'LEGAL',
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8]
         }
     },
         ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result

  var total_qtt= api
            .column( 5 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

                                var total_price= api
                                          .column( 6 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );
                                          var total_qtt2= api
                                                    .column( 7 )
                                                    .data()
                                                    .reduce( function (a, b) {
                                                        return intVal(a) + intVal(b);
                                                    }, 0 );

                                                                        var total_price2= api
                                                                                  .column( 8 )
                                                                                  .data()
                                                                                  .reduce( function (a, b) {
                                                                                      return intVal(a) + intVal(b);
                                                                                  }, 0 );
        // Update footer by showing the total with the reference of the column index


        // Update footer

  $( api.column( 1 ).footer() ).html('TOTAL');

        $( api.column( 3 ).footer() ).html(total_qtt);
        $( api.column( 4 ).footer() ).html(total_price);
        $( api.column( 5 ).footer() ).html(total_qtt2);

        $( api.column( 8 ).footer() ).html('Ksh '+total_price2);
      // var formatted = $.fn.dataTable.render.number(',','.','2','$').display(total_price)




    },
});

$('#paid_invoices_report').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [
     {
         extend: 'colvis',
         text: 'Filter Columns',
         collectionLayout: 'fixed two-column',
         exportOptions: {
             columns: ':visible'
         },
     },
     {
         extend: 'excel',
         footer: true ,
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8]
         }
     },
     {
         extend: 'pdf',
         footer: true ,
         orientation: 'potrait',
         pageSize: 'LEGAL',
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8]
         }
     },
         ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result

  var total_qtt= api
            .column( 5 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

                                var total_price= api
                                          .column( 6 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );
                                          var total_qtt2= api
                                                    .column( 7 )
                                                    .data()
                                                    .reduce( function (a, b) {
                                                        return intVal(a) + intVal(b);
                                                    }, 0 );

                                                                        var total_price2= api
                                                                                  .column( 8 )
                                                                                  .data()
                                                                                  .reduce( function (a, b) {
                                                                                      return intVal(a) + intVal(b);
                                                                                  }, 0 );
        // Update footer by showing the total with the reference of the column index


        // Update footer

  $( api.column( 1 ).footer() ).html('TOTAL');

        $( api.column( 3 ).footer() ).html(total_qtt);
        $( api.column( 4 ).footer() ).html(total_price);
        $( api.column( 5 ).footer() ).html(total_qtt2);

        $( api.column( 8 ).footer() ).html('Ksh '+total_price2);
      // var formatted = $.fn.dataTable.render.number(',','.','2','$').display(total_price)




    },
});

$('#stocks-in-production-report2').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [
     {
         extend: 'colvis',
         text: 'Filter Columns',
         collectionLayout: 'fixed two-column',
         exportOptions: {
             columns: ':visible'
         },
     },
     {
         extend: 'excel',
         footer: true ,
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7]
         }
     },
     {
         extend: 'pdf',
         footer: true ,
         orientation: 'potrait',
         pageSize: 'LEGAL',
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7]
         }
     },
         ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result

            var total_qtt2 = api
                      .column( 3)
                      .data()
                      .reduce( function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0 );

                                var total_price2= api
                                          .column( 4 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );

        // Update footer by showing the total with the reference of the column index


        // Update footer

  $( api.column( 1 ).footer() ).html('TOTAL');

          $( api.column( 3 ).footer() ).html(total_qtt2);

        $( api.column( 4 ).footer() ).html('Ksh '+total_price2);
      // var formatted = $.fn.dataTable.render.number(',','.','2','$').display(total_price)




    },
});

$('#stocks-in-production-report').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [
     {
         extend: 'colvis',
         text: 'Filter Columns',
         collectionLayout: 'fixed two-column',
         exportOptions: {
             columns: ':visible'
         },
     },
     {
         extend: 'excel',
         footer: true ,
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8,9]
         }
     },
     {
         extend: 'pdf',
         footer: true ,
         orientation: 'potrait',
         pageSize: 'LEGAL',
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8,9]
         }
     },
         ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result

  var total_qtt= api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
            var total_qtt2 = api
                      .column( 4)
                      .data()
                      .reduce( function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0 );

                                var total_price2= api
                                          .column( 5 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );

        // Update footer by showing the total with the reference of the column index


        // Update footer

  $( api.column( 1 ).footer() ).html('TOTAL');

        $( api.column( 3 ).footer() ).html(total_qtt);
          $( api.column( 4 ).footer() ).html(total_qtt2);

        $( api.column( 5 ).footer() ).html('Ksh '+total_price2);
      // var formatted = $.fn.dataTable.render.number(',','.','2','$').display(total_price)




    },
});

$('#zero-stocks-in-production-report').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [
     {
         extend: 'colvis',
         text: 'Filter Columns',
         collectionLayout: 'fixed two-column',
         exportOptions: {
             columns: ':visible'
         },
     },
     {
         extend: 'excel',
         footer: true ,
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8]
         }
     },
     {
         extend: 'pdf',
         footer: true ,
         orientation: 'potrait',
         pageSize: 'LEGAL',
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8]
         }
     },
         ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result

  var total_qtt= api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
            var total_qtt2 = api
                      .column( 5)
                      .data()
                      .reduce( function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0 );

                                var total_price2= api
                                          .column( 6 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );

        // Update footer by showing the total with the reference of the column index


        // Update footer

  $( api.column( 1 ).footer() ).html('TOTAL');

        $( api.column( 3 ).footer() ).html(total_qtt);
          $( api.column( 5 ).footer() ).html(total_qtt2);

        $( api.column( 6 ).footer() ).html('Ksh '+total_price2);
      // var formatted = $.fn.dataTable.render.number(',','.','2','$').display(total_price)




    },
});



  // invoice payment table for debits
  $('.stock-util-table').DataTable({
  destroy: true,
  dom: 'Bfrtip',
  buttons: [

      {
          extend: 'excel',
          footer: true ,
          exportOptions: {
              columns: [0,1,2,3,4,5,6]
          }
      },
      {
          extend: 'pdf',
          footer: true ,
          orientation: 'potrait',
          pageSize: 'LEGAL',
          exportOptions: {
              columns: [0,1,2,3,4,5,6]
          }
      },
    ],
    "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api(), data;

          // converting to interger to find total
          var intVal = function ( i ) {
              return typeof i === 'string' ?
                  i.replace(/[\$,]/g, '')*1 :
                  typeof i === 'number' ?
                      i : 0;
          };

          // computing column Total of the complete result



    var qtt= api
              .column( 2 )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

              var unit_price= api
                        .column( 3 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                        var total = api
                                  .column( 4 )
                                  .data()
                                  .reduce( function (a, b) {
                                      return intVal(a) + intVal(b);
                                  }, 0 );


          // Update footer by showing the total with the reference of the column index
    $( api.column( 1).footer() ).html('TOTAL');

          $( api.column( 2 ).footer() ).html(qtt);
          $( api.column( 3 ).footer() ).html(unit_price);
          $( api.column( 4 ).footer() ).html(total);

      },
});


// dashboard deliveries
$('.delivery-util-table').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [

    {
        extend: 'excel',
        footer: true ,
        exportOptions: {
            columns: [0,1,2,3,4,5,6]
        }
    },
    {
        extend: 'pdf',
        footer: true ,
        orientation: 'potrait',
        pageSize: 'LEGAL',
        exportOptions: {
            columns: [0,1,2,3,4,5,6]
        }
    },
  ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result



  var qttR= api
            .column( 2 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            var qttD= api
                      .column( 3 )
                      .data()
                      .reduce( function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0 );

                      var qttRem = api
                                .column( 4 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                var qttRem2 = api
                                          .column( 5 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );



        // Update footer by showing the total with the reference of the column index
  $( api.column( 1).footer() ).html('TOTAL');

        $( api.column( 2 ).footer() ).html(qttR);
        $( api.column( 3 ).footer() ).html(qttD);
        $( api.column( 4 ).footer() ).html(qttRem);
        $( api.column( 5 ).footer() ).html(qttRem2);

    },
    "pageLength": 5
});

// deliveru util 2
// dashboard deliveries
$('.delivery-util-table2').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [

    {
        extend: 'excel',
        footer: true ,
        exportOptions: {
            columns: [0,1,2,3,4,5,6]
        }
    },
    {
        extend: 'pdf',
        footer: true ,
        orientation: 'potrait',
        pageSize: 'LEGAL',
        exportOptions: {
            columns: [0,1,2,3,4,5,6]
        }
    },
  ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result





            var qttD= api
                      .column( 3 )
                      .data()
                      .reduce( function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0 );

                      var qttRem = api
                                .column( 4 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                var qttRem2 = api
                                          .column( 5 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );
                                          var qttR= api
                                                    .column( 6 )
                                                    .data()
                                                    .reduce( function (a, b) {
                                                        return intVal(a) + intVal(b);
                                                    }, 0 );



        // Update footer by showing the total with the reference of the column index
  $( api.column( 1).footer() ).html('TOTAL');


        $( api.column( 3 ).footer() ).html(qttD);
        $( api.column( 4 ).footer() ).html(qttRem);
        $( api.column( 5 ).footer() ).html(qttRem2);
          $( api.column( 6 ).footer() ).html(qttR);

    },
});



//dashboard stock costKsh

$('.profit-loss-table').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [

    {
        extend: 'excel',
        footer: true ,
        exportOptions: {
            columns: [0,1,2,3,4,5,6]
        }
    },
    {
        extend: 'pdf',
        footer: true ,
        orientation: 'potrait',
        pageSize: 'LEGAL',
        exportOptions: {
            columns: [0,1,2,3,4,5,6]
        }
    },
  ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result



  var qttR= api
            .column( 2 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            var qttD= api
                      .column( 3 )
                      .data()
                      .reduce( function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0 );

                      var qttRem = api
                                .column( 4 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                var qttD2= api
                                          .column( 5 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );

                                          var qttRem2 = api
                                                    .column( 6)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                        return intVal(a) + intVal(b);
                                                    }, 0 );



        // Update footer by showing the total with the reference of the column index
  $( api.column( 1).footer() ).html('TOTAL');

        $( api.column( 2 ).footer() ).html(qttR);
        $( api.column( 3 ).footer() ).html(qttD);
        $( api.column( 4 ).footer() ).html(qttRem);
        $( api.column( 5 ).footer() ).html(qttD2);
        $( api.column( 6 ).footer() ).html(qttRem2);

    },
    "pageLength": 5
});

$('.profit-loss-table2').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [

    {
        extend: 'excel',
        footer: true ,
        exportOptions: {
            columns: [0,1,2,3,4,5,6]
        }
    },
    {
        extend: 'pdf',
        footer: true ,
        orientation: 'potrait',
        pageSize: 'LEGAL',
        exportOptions: {
            columns: [0,1,2,3,4,5,6]
        }
    },
  ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result


            var qttD= api
                      .column( 3 )
                      .data()
                      .reduce( function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0 );

                      var qttRem = api
                                .column( 4 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                var qttD2= api
                                          .column( 5 )
                                          .data()
                                          .reduce( function (a, b) {
                                              return intVal(a) + intVal(b);
                                          }, 0 );

                                          var qttRem2 = api
                                                    .column( 6)
                                                    .data()
                                                    .reduce( function (a, b) {
                                                        return intVal(a) + intVal(b);
                                                    }, 0 );



        // Update footer by showing the total with the reference of the column index
  $( api.column( 1).footer() ).html('TOTAL');

          $( api.column( 3 ).footer() ).html(qttD);
        $( api.column( 4 ).footer() ).html(qttRem);
        $( api.column( 5 ).footer() ).html(qttD2);
        $( api.column( 6 ).footer() ).html(qttRem2);

    },
"pageLength": 5
});


// dashboard deliveries
$('#dashboard-invoice-payments-table').DataTable({
destroy: true,
dom: 'Bfrtip',
buttons: [

    {
        extend: 'excel',
        footer: true ,
        exportOptions: {
            columns: [0,1,2,3,4,5]
        }
    },
    {
        extend: 'pdf',
        footer: true ,
        orientation: 'potrait',
        pageSize: 'LEGAL',
        exportOptions: {
            columns: [0,1,2,3,4,5]
        }
    },
  ],
  "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // computing column Total of the complete result



  var qttR= api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            var qttD= api
                      .column( 4 )
                      .data()
                      .reduce( function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0 );

                      var qttRem = api
                                .column( 5 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );


        // Update footer by showing the total with the reference of the column index
  $( api.column( 1).footer() ).html('TOTAL');

        $( api.column( 3 ).footer() ).html(qttR);
        $( api.column( 4 ).footer() ).html(qttD);
        $( api.column( 5 ).footer() ).html(qttRem);

    },
    "pageLength": 5
});

      var table = $('#departmental-workplan-table').DataTable({
        destroy: true,

        dom: 'Bfrtip',
        buttons: [
             {
                 extend: 'colvis',
                 text: 'Filter Columns',
                 collectionLayout: 'fixed two-column',
                 exportOptions: {
                     columns: ':visible'
                 },
             },
             {
                 extend: 'pdf',
                 orientation: 'landscape',
                 pageSize: 'LEGAL',
                 exportOptions: {
                     columns: ':visible'
                 }
             },
             {
                 extend: 'excel',
                 orientation: 'landscape',
                 pageSize: 'LEGAL',
                 exportOptions: {
                     columns: ':visible',

                 }
             }

         ]
      });

      //risks update monitoring table
      $('#risks-update-monitoring-table').DataTable({
      destroy: true,
      dom: 'Bfrtip',
      buttons: [

          {
              extend: 'excel',
              footer: true ,
              exportOptions: {
                  columns: [0,1,2,3,4,5]
              }
          },
          {
              extend: 'pdf',
              footer: true ,
              orientation: 'landscape',
              pageSize: 'LEGAL',
              exportOptions: {
                  columns: [0,1,2,3,4,5]
              }
          },
        ],
        "footerCallback": function ( row, data, start, end, display ) {
              var api = this.api(), data;

              // converting to interger to find total
              var intVal = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
              };

              // computing column Total of the complete result

        var total_risks = api
                  .column( 2 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );

        var total_risks_pending_update = api
                  .column( 3 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );


         var total_risks_pending_approval = api
                  .column( 5 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );


              // Update footer by showing the total with the reference of the column index
        $( api.column( 0 ).footer() ).html('TOTAL');
              $( api.column( 2 ).footer() ).html(total_risks);
              $( api.column( 3 ).footer() ).html(total_risks_pending_update);
              $( api.column( 5 ).footer() ).html(total_risks_pending_approval);
          },
  });

  //risks update monitoring table for standard users
      $('#risks-update-monitoring-table2').DataTable({
      dom: 'Bfrtip',
      buttons: [

        ],
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="select2 form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    });

  //activities update monitoring table
      $('#activities-update-monitoring-table').DataTable({
      destroy: true,
      dom: 'Bfrtip',
      buttons: [

          {
              extend: 'excel',
              footer: true ,
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'pdf',
              footer: true ,
              orientation: 'landscape',
              pageSize: 'LEGAL',
              exportOptions: {
                  columns: ':visible'
              }
          },
        ],


                  "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;

                        // converting to interger to find total
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };

                        // computing column Total of the complete result

                  var total_activities = api
                            .column( 2 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                  var total_activities_pending_update = api
                            .column( 3 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );


                        // Update footer by showing the total with the reference of the column index
                  $( api.column( 0 ).footer() ).html('TOTAL');
                        $( api.column( 2 ).footer() ).html(total_activities);
                        $( api.column( 3 ).footer() ).html(total_activities_pending_update);
                    },
  });

  //risks update monitoring table for standard users
      $('#activities-update-monitoring-table2').DataTable({
      destroy: true,
      dom: 'Bfrtip',
      buttons: [

        ],
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="select2 form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    });


    // get department for selected resource Name

    function getResDepartment() {
      $("#res_department").html('');
            var str='';
            var val=document.getElementById('EmpNo');
            for (i=0;i< val.length;i++) {
                if(val[i].selected){
                    str += val[i].value + ',';
                }
            }
            var str=str.slice(0,str.length -1);

    	$.ajax({
        type: "GET",
        url: "controllers/project-management/fetch-resource-dep.php",
        data:'EmpNo='+str,
            	success: function(data){
            		$("#res_department").html(data);
            	},
              error: function(xhr)
              {
                Toast.fire({
                          icon: 'error',
                          title: 'Your request could not be completed. Please try again: '+xhr.status
                  });
              }
    	});
    }


    //start project management tables

    $('#projects-list-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                orientation: 'potrait',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':visible'
                }
            },
             {
                extend: 'print',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title:'Project List',
                messageTop: 'Capital Markets Authority',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis','colvisRestore'
            ],
            columnDefs: [ {
                visible: false,


            } ],

        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="select2 form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                } );
            } );
        }

    });
    var table = $('#milestone-management-table').DataTable({
      destroy: true
    });

    var table = $('#project-risk-table').DataTable({
      destroy: true
    });

    var table = $('#project-lessons-learnt-table').DataTable({
      destroy: true
    });

    var table = $('#project-issue-logs-table').DataTable({
      destroy: true,
      dom: 'Bfrtip',
      buttons: [
           {
               extend: 'colvis',
               text: 'Filter Columns',
               collectionLayout: 'fixed two-column',
               exportOptions: {
                   columns: ':visible'
               },
           },
           {
               extend: 'pdf',
               orientation: 'landscape',
               pageSize: 'LEGAL',
               exportOptions: {
                   columns: ':visible'
               }
           },
           {
               extend: 'excel',
               orientation: 'landscape',
               pageSize: 'LEGAL',
               exportOptions: {
                   columns: ':visible',

               }
           }

       ]
    });

    var table = $('#project-payments-table').DataTable({
      destroy: true
    });

    var table = $('#project-task-list-table').DataTable({
      destroy: true
    });
    var table = $('#project-resource-list-table').DataTable({
      destroy: true
    });
    //end project management tables
}

$(document).on("mouseenter","select", function(evt){
  //function initiateSelect2() {
  $('.select2').select2({
    theme:'bootstrap4'
  });
});

$(document).on("select2:select","select", function(evt){
//$("select").on("select2:select", function (evt) {
  var element = evt.params.data.element;
  var $element = $(element);

  $element.detach();
  $(this).append($element);
  $(this).trigger("change");
});

// Restrict e, -, + for html input number
$(document).on('keypress', ':input[type="number"]', function (e) {
    if (isNaN(e.key)) {
        return false;
    }
});

$(document).on('cut copy paste', ':input[type="number"]', function (e) {
     e.preventDefault();
});

//project datepicker
function humanise(total_days)
{
    //var total_days = 1001;
    var date_current = new Date();
    var utime_target = date_current.getTime() + total_days*86400*1000;
    var date_target = new Date(utime_target);

    var diff_year  = parseInt(date_target.getUTCFullYear() - date_current.getUTCFullYear());
    var diff_month = parseInt(date_target.getUTCMonth() - date_current.getUTCMonth());
    var diff_day   = parseInt(date_target.getUTCDate() - date_current.getUTCDate());

    var days_in_month = [31, (date_target.getUTCFullYear()%4?29:28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    var date_string = "";
    while(true)
    {
        date_string = "";
        date_string += (diff_year>0?diff_year + " Years ":" ");

        if(diff_month<0){diff_year -= 1; diff_month += 12; continue;}
        date_string += (diff_month>0?diff_month + " Months ":" ");

        if(diff_day<0){diff_month -= 1; diff_day += days_in_month[((11+date_target.getUTCMonth())%12)]; continue;}
        date_string += (diff_day>0?diff_day + " Days ":" ");
        break;
    }
    return date_string;
}


$(document).on('mousedown', '.project_start_date', function () {
  $('.project_start_date').datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      //minDate: 0,
  }).on('changeDate', function (ev) {
    $('.project_end_date').datepicker({
        format: "dd-M-yyyy",
        todayHighlight:'TRUE',
        startDate:$(".project_start_date").val(),
        autoclose: true

          //$('.project_end_date').datepicker('setStartDate', $(".project_start_date").val());
    });
  });
});

$(document).on('mousedown', '.project_end_date', function () {
  $('.project_end_date').datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      //minDate: '0',
  }).on('changeDate', function (ev) {
          var start = $(".project_start_date").val();
          var startD = new Date(start);
          var end = $(".project_end_date").val();
          var endD = new Date(end);
          var diff = parseInt((endD.getTime()-startD.getTime())/(24*3600*1000));

          var formated_date = humanise(diff);

         $(".project-duration").val(formated_date);
         $(".project-duration-in-days").val(diff);

  });
});

//start date picker for project modification
function ChangeStartDate(str)
{
  $('#project_start_date-'+str).datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      //minDate: 0,
  }).on('changeDate', function (ev) {
    $('#project_end_date-'+str).datepicker({
        format: "dd-M-yyyy",
        todayHighlight:'TRUE',
        startDate:$('#project_start_date-'+str).val(),
        autoclose: true

          //$('.project_end_date').datepicker('setStartDate', $(".project_start_date").val());

    });
  });
}
function ChangeEndDate(str)
{
  $('#project_end_date-'+str).datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      //minDate: '0',
  }).on('changeDate', function (ev) {
          var start = $('#project_start_date-'+str).val();
          var startD = new Date(start);
          var end = $('#project_end_date-'+str).val();
          var endD = new Date(end);
          var diff = parseInt((endD.getTime()-startD.getTime())/(24*3600*1000));

          var formated_date = humanise(diff);

         $("#project-duration-"+str).val(formated_date);
         $("#duration-project-duration-in-days-"+str).val(diff);

  });
}


//end datepicker for project modification

//start funding agency modification
function ChangeFundingAgency(str)
{
  var funding_agency = $('#funding-agency-'+str).val();

  if(funding_agency == 'CMA')
  {
    $('#internal-budget-'+str).removeClass('d-none');
    $("#internal-currency-"+str).prop('required',true);
    $("#internal-budget-value-"+str).prop('required',true);

    $('#external-budget-'+str).addClass('d-none');
    $("#external-currency-"+str).prop('required',false);
    $("#external-budget-value-"+str).prop('required',false);


    $("#internal-currency-"+str).prop('disabled',false);
    $("#internal-budget-value-"+str).prop('disabled',false);
    $("#external-currency-"+str).prop('disabled',true);
    $("#external-budget-value-"+str).prop('disabled',true);

    $("#external-currency-"+str).val();
    $("#external-budget-value-"+str).val();

    $('#internal-'+str).prop("disabled", false);
    $('#external-'+str).prop("disabled", true);

  }
  else if (funding_agency == 'FSSP')
  {
    $('#external-budget-'+str).removeClass('d-none');
    $("#external-currency-"+str).prop('required',true);
    $("#external-budget-value-"+str).prop('required',true);


    $('#internal-budget-'+str).addClass('d-none');
    $("#internal-currency-"+str).prop('required',false);
    $("#internal-budget-value-"+str).prop('required',false);

    $("#internal-currency-"+str).prop('disabled',true);
    $("#internal-budget-value-"+str).prop('disabled',true);
    $("#external-currency-"+str).prop('disabled',false);
    $("#external-budget-value-"+str).prop('disabled',false);

    $("#internal-currency-"+str).val();
    $("#internal-budget-value-"+str).val();

    $('#internal-'+str).prop("disabled", true);
    $('#external-'+str).prop("disabled", false);
  }
  else if (funding_agency == 'CMA & FSSP')
  {
    $('#internal-budget-'+str).removeClass('d-none');
    $('#external-budget-'+str).removeClass('d-none');
    $("#internal-currency-"+str).prop('required',true);
    $("#external-currency-"+str).prop('required',true);
    $("#internal-budget-value-"+str).prop('required',true);
    $("#external-budget-value-"+str).prop('required',true);

    $("#internal-currency-"+str).prop('disabled',false);
    $("#internal-budget-value-"+str).prop('disabled',false);
    $("#external-currency-"+str).prop('disabled',false);
    $("#external-budget-value-"+str).prop('disabled',false);

    $('#internal-'+str).prop("disabled", false);
    $('#external-'+str).prop("disabled", false)

  }
  else
  {
    $('#internal-budget-'+str).addClass('d-none');
    $('#external-budget-'+str).addClass('d-none');
    $("#internal-currency-"+str).prop('required',false);
    $("#external-currency-"+str).prop('required',false);
    $("#internal-budget-value-"+str).prop('required',false);
    $("#external-budget-value-"+str).prop('required',false);

    $("#external-currency-"+str).val();
    $("#external-budget-value-"+str).val();
    $("#internal-currency-"+str).val();
    $("#internal-budget-value-"+str).val();

    $("#internal-currency-"+str).prop('disabled',true);
    $("#internal-budget-value-"+str).prop('disabled',true);
    $("#external-currency-"+str).prop('disabled',true);
    $("#external-budget-value-"+str).prop('disabled',true);

    $('#internal-'+str).prop("disabled", false);
    $('#external-'+str).prop("disabled", false)
  }
}


//end funding agency modification

//start file picker
$(document).on('change', '.project-file :file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

$(document).on('fileselect', '.project-file :file', function(event, numFiles, label) {
  var input = $(this).parents('.input-group').find(':text'),
      log = numFiles > 1 ? numFiles + ' files selected' : label;

  if( input.length ) {
      input.val(log);
  } else {
      if( log )
      {
        console.log(log);
      }
  }
});

//project file change



/*$(document).on("mouseout",".external-budget-value", function(e){
      $('.select-currency').html('Select Currency Type');
      $('.external-currency').focus();

});
*/
//project funding agency on change
$(document).on("change",".funding-agency", function(e){
  var funding_agency = $('.funding-agency').val();

  if(funding_agency == 'CMA')
  {
    $('.internal-budget').removeClass('d-none');
    $(".internal-currency").prop('required',true);
    $(".internal-budget-value").prop('required',true);

    $('.external-budget').addClass('d-none');
    $(".external-currency").prop('required',false);
    $(".external-budget-value").prop('required',false);

    $(".internal-currency").prop('disabled',false);
    $(".internal-budget-value").prop('disabled',false);
    $(".external-currency").prop('disabled',true);
    $(".external-budget-value").prop('disabled',true);

    $('.internal').prop("disabled", false);
    $('.external').prop("disabled", true);
  }
  else if (funding_agency == 'FSSP')
  {

    $('.external-budget').removeClass('d-none');
    $(".external-currency").prop('required',true);
    $(".external-budget-value").prop('required',true);

    $('.internal-budget').addClass('d-none');
    $(".internal-currency").prop('required',false);
    $(".internal-budget-value").prop('required',false);

    $(".internal-currency").prop('disabled',true);
    $(".internal-budget-value").prop('disabled',true);
    $(".external-currency").prop('disabled',false);
    $(".external-budget-value").prop('disabled',false);

    $('.internal').prop("disabled", true);
    $('.external').prop("disabled", false);
  }
  else if (funding_agency == 'CMA & FSSP')
  {
    $('.internal-budget').removeClass('d-none');
    $('.external-budget').removeClass('d-none');
    $(".internal-currency").prop('required',true);
    $(".external-currency").prop('required',true);
    $(".internal-budget-value").prop('required',true);
    $(".external-budget-value").prop('required',true);

    $(".internal-currency").prop('disabled',false);
    $(".internal-budget-value").prop('disabled',false);
    $(".external-currency").prop('disabled',false);
    $(".external-budget-value").prop('disabled',false);

    $('.internal').prop("disabled", false);
    $('.external').prop("disabled", false);
  }
  else
  {

    $('.internal-budget').addClass('d-none');
    $('.external-budget').addClass('d-none');
    $(".internal-currency").prop('required',false);
    $(".external-currency").prop('required',false);
    $(".internal-budget-value").prop('required',false);
    $(".external-budget-value").prop('required',false);

    $(".external-currency").val();
    $(".external-budget-value").val();
    $(".internal-currency").val();
    $(".internal-budget-value").val();

    $(".internal-currency").prop('disabled',true);
    $(".internal-budget-value").prop('disabled',true);
    $(".external-currency").prop('disabled',true);
    $(".external-budget-value").prop('disabled',true);


    $('.internal').prop("disabled", false);
    $('.external').prop("disabled", false);


  }

});


//START MILESTONE FUNCTIONALITIES
$(document).on('mousedown', '.milestone-start-date', function () {
  $('.activity-start-dates').removeClass('d-none');
  $('.milestone-start-date').datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      startDate:$("#project_start_date").val(),
      endDate:$("#project_end_date").val()
      //minDate: 0,
  }).on('changeDate', function (ev) {
    $('.milestone-end-date').datepicker({
        format: "dd-M-yyyy",
        todayHighlight:'TRUE',
        startDate:$(".milestone-start-date").val(),
        endDate:$("#project_end_date").val(),
        autoclose: true

          //$('.project_end_date').datepicker('setStartDate', $(".project_start_date").val());
    });
  });
});

$(document).on('mousedown', '.milestone-end-date', function () {
  $('.activity-end-dates').removeClass('d-none');
  $('.milestone-end-date').datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      startDate:$("#project_start_date").val(),
      endDate:$("#project_end_date").val()
      //minDate: '0',
  }).on('changeDate', function (ev) {
          var start = $(".milestone-start-date").val();
          var startD = new Date(start);
          var end = $(".milestone-end-date").val();
          var endD = new Date(end);
          var diff = parseInt((endD.getTime()-startD.getTime())/(24*3600*1000));

          var formated_date = humanise(diff);

         $(".milestone-duration").val(formated_date);
         $(".milestone-duration-in-days").val(diff);

  });
});

//start date picker for activities
$(document).on('mousedown', '.activity-start-date', function () {
  $('.activity-start-date').datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      startDate:$(".milestone-start-date").val(),
      endDate:$(".milestone-end-date").val()
      //minDate: 0,
  }).on('changeDate', function (ev) {
    $('.activity-end-date').datepicker({
        format: "dd-M-yyyy",
        todayHighlight:'TRUE',
        startDate:$(".activity-start-date").closest(".activity-start-date").val(),
        endDate:$(".milestone-end-date").val(),
        autoclose: true

          //$('.project_end_date').datepicker('setStartDate', $(".project_start_date").val());
    });
  });
});

$(document).on('mousedown', '.activity-end-date', function () {
  $('.activity-end-date').datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      startDate:$(".milestone-start-date").val(),
      endDate:$(".milestone-end-date").val()
      //minDate: '0',
  }).on('changeDate', function (ev) {
          var start = $(".activity-start-date").closest(".activity-start-date").val();
          var startD = new Date(start);
          var end = $(".activity-end-date").closest(".activity-end-date").val();
          var endD = new Date(end);
          var diff = parseInt((endD.getTime()-startD.getTime())/(24*3600*1000));

          var formated_date = humanise(diff);

         //$(".activity-duration").closest(".activity-duration").val(formated_date);
         //$(".activity-duration-in-days").closest(".activity-duration-in-days").val(diff);

  });
});

function ChangeActivityStartDate(str)
{
  $('#activity-start-date-'+str).datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      startDate:$(".msd").children(".msd").val(),
      endDate:$(".med").closest(".med").val()
      //minDate: 0,
  }).on('changeDate', function (ev) {
    $('#activity-end-date-'+str).datepicker({
        format: "dd-M-yyyy",
        todayHighlight:'TRUE',
        startDate:$("#activity-start-date-"+str).val(),
        endDate:$(".med").val(),
        autoclose: true

          //$('.project_end_date').datepicker('setStartDate', $(".project_start_date").val());
    });
  });
}
function ChangeActivityEndDate(str)
{
  $('#activity-end-date-'+str).datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      startDate:$(".msd").closest(".msd").val(),
      endDate:$(".med").closest(".med").val()
      //minDate: '0',
  }).on('changeDate', function (ev) {
          var start = $("#activity-start-date-"+str).val();
          var startD = new Date(start);
          var end = $("#activity-end-date-"+str).val();
          var endD = new Date(end);
          var diff = parseInt((endD.getTime()-startD.getTime())/(24*3600*1000));

          var formated_date = humanise(diff);

         //$(".activity-duration").closest(".activity-duration").val(formated_date);
         //$(".activity-duration-in-days").closest(".activity-duration-in-days").val(diff);
         $("#activity-duration-in-days-"+str).val(diff);
         $("#activity-duration-in-days-"+str).click();

  });
}
//end datepicker for activities


//start date picker for milestone project modification
function ChangeMilestoneStartDate(str)
{
  $('#milestone-start-date-'+str).datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      startDate:$("#project_milestone_start_date-"+str).val(),
      endDate:$("#project_milestone_end_date-"+str).val()
      //minDate: 0,
  }).on('changeDate', function (ev) {
    $('#milestone-end-date-'+str).datepicker({
        format: "dd-M-yyyy",
        todayHighlight:'TRUE',
        startDate:$('#milestone-start-date-'+str).val(),
        endDate:$('#project_milestone_end_date-'+str).val(),
        autoclose: true

          //$('.project_end_date').datepicker('setStartDate', $(".project_start_date").val());

    });
  });
}
function ChangeMilestoneEndDate(str)
{
  $('#milestone-end-date-'+str).datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      startDate:$("#project_milestone_start_date-"+str).val(),
      endDate:$("#project_milestone_end_date-"+str).val()
      //minDate: '0',
  }).on('changeDate', function (ev) {
          var start = $('#milestone-start-date-'+str).val();
          var startD = new Date(start);
          var end = $('#milestone-end-date-'+str).val();
          var endD = new Date(end);
          var diff = parseInt((endD.getTime()-startD.getTime())/(24*3600*1000));

          var formated_date = humanise(diff);

         $("#milestone-duration-"+str).val(formated_date);
         $("#duration-milestone-duration-in-days-"+str).val(diff);

  });
}
$('.milestone-tasks-div').hide();
$('#submit-milestone-button').hide();
$(document).on("click","#add-milestone-tasks", function(){
     $('.milestone-tasks-div').toggle();
     $('#submit-milestone-button').toggle();
});

//end datepicker for milestone modification

//END MILESTONE FUNCTIONALITIES

//START PROJECT RISK FUNCTIONALITIES
function ChangeProjectRiskImpactScore(str)
{
  var overall = $('#project-risk-edit-impact-score-'+str).val() * $('#project-risk-edit-likelihood-score-'+str).val();
  $('#project-risk-edit-overall-score-'+str).val(overall);

   //color coding
   if(overall < 3)
   {
     $('.project-risk-edit-overall-score-prefix-'+str).removeClass('two three four five');
     $('.project-risk-edit-overall-score-prefix-'+str).addClass('one');
     $('#project-risk-color_rating-'+str).val('one');
   }
   if(overall < 5 && overall >= 3)
   {
     $('.project-risk-edit-overall-score-prefix-'+str).removeClass('one three four five');
     $('.project-risk-edit-overall-score-prefix-'+str).addClass('two');
     $('#project-risk-color_rating-'+str).val('two');
   }
   if(overall < 10 && overall >= 5)
   {
     $('.project-risk-edit-overall-score-prefix-'+str).removeClass('one two four five');
     $('.project-risk-edit-overall-score-prefix-'+str).addClass('three');
     $('#project-risk-color_rating-'+str).val('three');
   }
   if(overall < 17 && overall >= 10)
   {
     $('.project-risk-edit-overall-score-prefix-'+str).removeClass('one two three five');
     $('.project-risk-edit-overall-score-prefix-'+str).addClass('four');
     $('#project-risk-color_rating-'+str).val('four');
   }
   if(overall > 16)
   {
     $('.project-risk-edit-overall-score-prefix-'+str).removeClass('one two three four');
     $('.project-risk-edit-overall-score-prefix-'+str).addClass('five');
     $('#project-risk-color_rating-'+str).val('five');
   }
}
function ChangeProjectRiskLikelihoodScore(str)
{
  var overall = $('#project-risk-edit-impact-score-'+str).val() * $('#project-risk-edit-likelihood-score-'+str).val();
  $('#project-risk-edit-overall-score-'+str).val(overall);

    //color coding
   if(overall < 3)
   {
     $('.project-risk-edit-overall-score-prefix-'+str).removeClass('two three four five');
     $('.project-risk-edit-overall-score-prefix-'+str).addClass('one');
     $('#project-risk-color_rating-'+str).val('one');
   }
   if(overall < 5 && overall >= 3)
   {
     $('.project-risk-edit-overall-score-prefix-'+str).removeClass('one three four five');
     $('.project-risk-edit-overall-score-prefix-'+str).addClass('two');
     $('#project-risk-color_rating-'+str).val('two');
   }
   if(overall < 10 && overall >= 5)
   {
     $('.project-risk-edit-overall-score-prefix-'+str).removeClass('one two four five');
     $('.project-risk-edit-overall-score-prefix-'+str).addClass('three');
     $('#project-risk-color_rating-'+str).val('three');
   }
   if(overall < 17 && overall >= 10)
   {
     $('.project-risk-edit-overall-score-prefix-'+str).removeClass('one two three five');
     $('.project-risk-edit-overall-score-prefix-'+str).addClass('four');
     $('#project-risk-color_rating-'+str).val('four');
   }
   if(overall > 16)
   {
     $('.project-risk-edit-overall-score-prefix-'+str).removeClass('one two three four');
     $('.project-risk-edit-overall-score-prefix-'+str).addClass('five');
     $('#project-risk-color_rating-'+str).val('five');
   }
}


//END PROJECT RISK FUNCTIONALITIES

//START PROJECT LESSON LEARNT FUNCTIONALITIES
$(document).on('mousedown', '.project-lesson-date', function () {
  $('.project-lesson-date').datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      //minDate: 0,
  });
});

function ChangeProjectLessonDate(str)
{
  $('#project_lesson_date-'+str).datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      //minDate: 0,
  });
}

//END PROJECT LESSON LEARNT FUNCTIONALITIES

//START ISSUE LOGS FUNCTIONALITIES
$(document).on('mousedown', '.issue-date-raised', function () {
  $('.issue-date-raised').datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      //minDate: 0,
  }).on('changeDate', function (ev) {
    $('.issue-due-date').datepicker({
        format: "dd-M-yyyy",
        todayHighlight:'TRUE',
        startDate:$(".issue-date-raised").val(),
        autoclose: true

          //$('.project_end_date').datepicker('setStartDate', $(".project_start_date").val());
    });
  });
});

function ChangeIssueDate(str)
{
  $('#issue-date-raised-'+str).datepicker({
      format: "dd-M-yyyy",
      todayHighlight:'TRUE',
      autoclose: true,
      //minDate: 0,
  }).on('changeDate', function (ev) {
    $('#issue-due-date-'+str).datepicker({
        format: "dd-M-yyyy",
        todayHighlight:'TRUE',
        startDate:$("#issue-date-raised-"+str).val(),
        autoclose: true

          //$('.project_end_date').datepicker('setStartDate', $(".project_start_date").val());
    });
  });
}
//END ISSUE LOGS FUNCTIONALITIES
/*
$(document).on('mouseenter', '.project_start_date', function () {
   $(".project_start_date").datepicker({
      autoclose: true
  });

});
$(document).on('mouseenter', '.project_end_date', function () {
  $(".project_end_date").datepicker({
     autoclose: true
 });
});
*/













/*
initiateSelect2();
// when modal is open
$('.modal').on('shown.bs.modal', function () {
  initiateSelect2();
})

*/
/*
$('select').on('select2:open', function(e){
    $('.custom-dropdown').parent().css('z-index', 99999);
});*/


//autosave forms

//toggle comments
$(document).on("click","#see-comments-update", function(){
     $('.update-comments').toggle();
});

$(document).on("click","#see-comments-edit", function(){
     $('.edit-comments').toggle();
});



$(document).on("mouseenter","textarea", function(){
     //AUTOSIZE
     //$('textarea').autoResize();
     $('textarea').autosize();
});

//enable tooltip
$(document).on("mouseenter","[data-toggle='tooltip']", function(){
    $('[data-toggle="tooltip"]').tooltip();
});

$('.accordion-body').on('show.bs.collapse', function () {
    $(this).closest("table")
        .find(".collapse.in")
        .not(this)
        //.collapse('toggle')
})

//dynamic objectives
var html_objectives = `
<div class="row">
  <div class="col-lg-12 col-xs-12 form-group">
      <label for="new_departmental_sub_objective">Departmental Sub Objective</label>
      <textarea name="new_departmental_sub_objective[]" id="child_departmental_sub_objective" class="form-control" placeholder="optional"></textarea>
  </div>
  <a href="#" id="remove-objective"><i class="fa fa-minus text-danger" style="float: right;"></i></a>
</div>
`;

//add rows to form
$(document).on("click","#add-objective", function(e){
  e.preventDefault();
  $("#dynamic-form-objective").append(html_objectives);
});

//remove rows from the form
$(document).on('click','#remove-objective', function(e){
  $(this).parent('div').remove();
});
//end of dynamic objectives

//dynamic add risks drivers
//variables
var html = `
<div class="row border-bottom">
  <div class="col-lg-4 col-xs-12 form-group">
          <label for="risk_drivers"><span class="required">*</span>Risk Drivers/Causes</label>
          <textarea name="risk_drivers[]" id="child_risk_drivers" class="form-control" required></textarea>
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
          <label for="key_risk_indicator"><span class="required">*</span>Key Risk Indicator/s</label>
          <textarea name="key_risk_indicator[]" id="child_key_risk_indicator" class="form-control" required></textarea>
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
          <label for="current_kri_level"> <span class="required">*</span>KRI Threshold</label>
          <textarea name="kri_threshold[]"  id="child_kri_threshold" class="form-control" required></textarea>
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
          <a href="#" id="add"><i class="fa fa-plus" style="float: right;"></i></a>
          <label for="treatment_action"><span class="required">*</span>Treatment Action</label>
           <textarea name="treatment_action[]" id="child_treatment_action" class="form-control" required></textarea>
  </div>
  <a href="#" id="remove"><i class="fa fa-minus" style="float: right;"></i></a>
</div>
`;

var html_opp = `
<div class="row border-bottom">
  <div class="col-lg-4 col-xs-12 form-group">
          <label for="risk_drivers"><span class="required">*</span>Opportunity Drivers/Causes</label>
          <textarea name="risk_drivers[]" id="child_risk_drivers" class="form-control" required></textarea>
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
          <label for="key_risk_indicator"><span class="required">*</span>Key Opportunity Indicator/s</label>
          <textarea name="key_risk_indicator[]" id="child_key_risk_indicator" class="form-control" required></textarea>
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
          <label for="current_kri_level"> <span class="required">*</span>KOI Threshold</label>
          <textarea name="kri_threshold[]"  id="child_kri_threshold" class="form-control" required></textarea>
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
          <a href="#" id="add-opp"><i class="fa fa-plus" style="float: right;"></i></a>
          <label for="treatment_action"><span class="required">*</span>Treatment Action</label>
           <textarea name="treatment_action[]" id="child_treatment_action" class="form-control" required></textarea>
  </div>
  <a href="#" id="remove-opp"><i class="fa fa-minus" style="float: right;"></i></a>
</div>
`;

        $(document).on("click",'#add-milestone-activity', function(e){
          //destrory any active tooltips
          $('[data-toggle="tooltip"]').tooltip("dispose");

          e.preventDefault();
          $(".dynamic-form-activities").append(html_activities);

          });

        $(document).on("click",'#remove-activity', function(e){

          //destrory any active tooltips
          $('[data-toggle="tooltip"]').tooltip("dispose");

          e.preventDefault();
          $(this).parent('div').remove();

        });

//add rows to form for risk
$(document).on("click",'#add', function(e){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  e.preventDefault();
  $("#dynamic-form").append(html);
});

$(document).on("click",'#remove', function(e){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  e.preventDefault();
  $(this).parent('div').remove();
});


//add rows to form for opportunity
$(document).on("click",'#add-opp-edit', function(e){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  e.preventDefault();
  $("#dynamic-form").append(html_opp);
});

//add rows to form for opportunity
$(document).on("click",'#add-opp', function(e){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  e.preventDefault();
  $("#dynamic-form-opp").append(html_opp);
});

$(document).on("click",'#remove-opp', function(e){
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  e.preventDefault();
  $(this).parent('div').remove();
});




function EditRemoveDyamicForm(str)
{
  //destrory any active tooltips
  $('[data-toggle="tooltip"]').tooltip("dispose");

  var risk_driver = $("#risk_drivers-"+str).val();
  if (confirm("Remove The Following Risk Driver ? : "+risk_driver)) {
      $("#dynamic-div-"+str).remove();
  }
  else
  {
  //preserve driver
  }


}




//risk rating calculating
$(document).on("change","#add-likelihood-score", function(){
    var overall = $('#add-impact-score').val() * $('#add-likelihood-score').val();
    $('#add-overall-score').val(overall);

      //color coding
     if(overall < 3)
     {
       $('.add-overall-score-prefix').removeClass('two three four five');
       $('.add-overall-score-prefix').addClass('one');
       $('#color_rating').val('one');
     }
     if(overall < 5 && overall >= 3)
     {
       $('.add-overall-score-prefix').removeClass('one three four five');
       $('.add-overall-score-prefix').addClass('two');
       $('#color_rating').val('two');
     }
     if(overall < 10 && overall >= 5)
     {
       $('.add-overall-score-prefix').removeClass('one two four five');
       $('.add-overall-score-prefix').addClass('three');
       $('#color_rating').val('three');
     }
     if(overall < 17 && overall >= 10)
     {
       $('.add-overall-score-prefix').removeClass('one two three five');
       $('.add-overall-score-prefix').addClass('four');
       $('#color_rating').val('four');
     }
     if(overall > 16)
     {
       $('.add-overall-score-prefix').removeClass('one two three four');
       $('.add-overall-score-prefix').addClass('five');
       $('#color_rating').val('five');
     }
});
$(document).on("change","#add-impact-score", function(){
    var overall = $('#add-impact-score').val() * $('#add-likelihood-score').val();
    $('#add-overall-score').val(overall);

     //color coding
     if(overall < 3)
     {
       $('.add-overall-score-prefix').removeClass('two three four five');
       $('.add-overall-score-prefix').addClass('one');
       $('#color_rating').val('one');
     }
     if(overall < 5 && overall >= 3)
     {
       $('.add-overall-score-prefix').removeClass('one three four five');
       $('.add-overall-score-prefix').addClass('two');
       $('#color_rating').val('two');
     }
     if(overall < 10 && overall >= 5)
     {
       $('.add-overall-score-prefix').removeClass('one two four five');
       $('.add-overall-score-prefix').addClass('three');
       $('#color_rating').val('three');
     }
     if(overall < 17 && overall >= 10)
     {
       $('.add-overall-score-prefix').removeClass('one two three five');
       $('.add-overall-score-prefix').addClass('four');
       $('#color_rating').val('four');
     }
     if(overall > 16)
     {
       $('.add-overall-score-prefix').removeClass('one two three four');
       $('.add-overall-score-prefix').addClass('five');
       $('#color_rating').val('five');
     }
});

//opportunity rating calculation
    $(document).on("change","#add-likelihood-score-opp", function(){
    var overall = $('#add-impact-score-opp').val() * $('#add-likelihood-score-opp').val();
    $('#add-overall-score-opp').val(overall);

      //color coding
     if(overall < 3)
     {
       $('.add-overall-score-prefix-opp').removeClass('opp-two opp-three opp-four opp-five');
       $('.add-overall-score-prefix-opp').addClass('opp-one');
       $('#color_rating_opp').val('opp-one');
     }
     if(overall < 5 && overall >= 3)
     {
       $('.add-overall-score-prefix-opp').removeClass('opp-one opp-three opp-four opp-five');
       $('.add-overall-score-prefix-opp').addClass('opp-two');
       $('#color_rating_opp').val('opp-two');
     }
     if(overall < 10 && overall >= 5)
     {
       $('.add-overall-score-prefix-opp').removeClass('opp-one opp-two opp-four opp-five');
       $('.add-overall-score-prefix-opp').addClass('opp-three');
       $('#color_rating_opp').val('opp-three');
     }
     if(overall < 17 && overall >= 10)
     {
       $('.add-overall-score-prefix-opp').removeClass('opp-one opp-two opp-three opp-five');
       $('.add-overall-score-prefix-opp').addClass('opp-four');
       $('#color_rating_opp').val('opp-four');
     }
     if(overall > 16)
     {
       $('.add-overall-score-prefix-opp').removeClass('opp-one opp-two opp-three opp-four');
       $('.add-overall-score-prefix-opp').addClass('opp-five');
       $('#color_rating_opp').val('opp-five');
     }
});
$(document).on("change","#add-impact-score-opp", function(){
    var overall = $('#add-impact-score-opp').val() * $('#add-likelihood-score-opp').val();
    $('#add-overall-score-opp').val(overall);

     //color coding
     if(overall < 3)
     {
       $('.add-overall-score-prefix-opp').removeClass('opp-two opp-three opp-four opp-five');
       $('.add-overall-score-prefix-opp').addClass('opp-one');
       $('#color_rating_opp').val('opp-one');
     }
     if(overall < 5 && overall >= 3)
     {
       $('.add-overall-score-prefix-opp').removeClass('opp-one opp-three opp-four opp-five');
       $('.add-overall-score-prefix-opp').addClass('opp-two');
       $('#color_rating_opp').val('opp-two');
     }
     if(overall < 10 && overall >= 5)
     {
       $('.add-overall-score-prefix-opp').removeClass('opp-one opp-two opp-four opp-five');
       $('.add-overall-score-prefix-opp').addClass('opp-three');
       $('#color_rating_opp').val('opp-three');
     }
     if(overall < 17 && overall >= 10)
     {
       $('.add-overall-score-prefix-opp').removeClass('opp-one opp-two opp-three opp-five');
       $('.add-overall-score-prefix-opp').addClass('opp-four');
       $('#color_rating_opp').val('opp-four');
     }
     if(overall > 16)
     {
       $('.add-overall-score-prefix-opp').removeClass('opp-one opp-two opp-three opp-four');
       $('.add-overall-score-prefix-opp').addClass('opp-five');
       $('#color_rating_opp').val('opp-five');
     }
});

//start update Ratings

//risk rating calculating
$(document).on("change","#update-likelihood-score", function(){
    var overall = $('#update-impact-score').val() * $('#update-likelihood-score').val();
    $('#update-overall-score').val(overall);

      //color coding
     if(overall < 3)
     {
       $('.update-overall-score-prefix').removeClass('two three four five');
       $('.update-overall-score-prefix').addClass('one');
       $('#color_rating_update').val('one');
     }
     if(overall < 5 && overall >= 3)
     {
       $('.update-overall-score-prefix').removeClass('one three four five');
       $('.update-overall-score-prefix').addClass('two');
       $('#color_rating_update').val('two');
     }
     if(overall < 10 && overall >= 5)
     {
       $('.update-overall-score-prefix').removeClass('one two four five');
       $('.update-overall-score-prefix').addClass('three');
       $('#color_rating_update').val('three');
     }
     if(overall < 17 && overall >= 10)
     {
       $('.update-overall-score-prefix').removeClass('one two three five');
       $('.update-overall-score-prefix').addClass('four');
       $('#color_rating_update').val('four');
     }
     if(overall > 16)
     {
       $('.update-overall-score-prefix').removeClass('one two three four');
       $('.update-overall-score-prefix').addClass('five');
       $('#color_rating_update').val('five');
     }
});


$(document).on("change","#update-impact-score", function(){
    var overall = $('#update-impact-score').val() * $('#update-likelihood-score').val();
    $('#update-overall-score').val(overall);

     //color coding
     if(overall < 3)
     {
       $('.update-overall-score-prefix').removeClass('two three four five');
       $('.update-overall-score-prefix').addClass('one');
       $('#color_rating_update').val('one');
     }
     if(overall < 5 && overall >= 3)
     {
       $('.update-overall-score-prefix').removeClass('one three four five');
       $('.update-overall-score-prefix').addClass('two');
       $('#color_rating_update').val('two');
     }
     if(overall < 10 && overall >= 5)
     {
       $('.update-overall-score-prefix').removeClass('one two four five');
       $('.update-overall-score-prefix').addClass('three');
       $('#color_rating_update').val('three');
     }
     if(overall < 17 && overall >= 10)
     {
       $('.update-overall-score-prefix').removeClass('one two three five');
       $('.update-overall-score-prefix').addClass('four');
       $('#color_rating_update').val('four');
     }
     if(overall > 16)
     {
       $('.update-overall-score-prefix').removeClass('one two three four');
       $('.update-overall-score-prefix').addClass('five');
       $('#color_rating_update').val('five');
     }
});


// performance color coding

$(document).on("change","#update-impact-score", function(){
    var overall = $('#update-impact-score').val() * $('#update-likelihood-score').val();
    $('#update-overall-score').val(overall);

     //color coding
     if(overall < 3)
     {
       $('.update-overall-score-prefix').removeClass('two three four five');
       $('.update-overall-score-prefix').addClass('one');
       $('#color_rating_update').val('one');
     }
     if(overall < 5 && overall >= 3)
     {
       $('.update-overall-score-prefix').removeClass('one three four five');
       $('.update-overall-score-prefix').addClass('two');
       $('#color_rating_update').val('two');
     }
     if(overall < 10 && overall >= 5)
     {
       $('.update-overall-score-prefix').removeClass('one two four five');
       $('.update-overall-score-prefix').addClass('three');
       $('#color_rating_update').val('three');
     }
     if(overall < 17 && overall >= 10)
     {
       $('.update-overall-score-prefix').removeClass('one two three five');
       $('.update-overall-score-prefix').addClass('four');
       $('#color_rating_update').val('four');
     }
     if(overall > 16)
     {
       $('.update-overall-score-prefix').removeClass('one two three four');
       $('.update-overall-score-prefix').addClass('five');
       $('#color_rating_update').val('five');
     }
});


//opportunity rating calculation
    $(document).on("change","#update-likelihood-score-opp", function(){
    var overall = $('#update-impact-score-opp').val() * $('#update-likelihood-score-opp').val();
    $('#update-overall-score-opp').val(overall);

      //color coding
     if(overall < 3)
     {
       $('.update-overall-score-prefix-opp').removeClass('opp-two opp-three opp-four opp-five');
       $('.update-overall-score-prefix-opp').addClass('opp-one');
       $('#color_rating_update').val('opp-one');
     }
     if(overall < 5 && overall >= 3)
     {
       $('.update-overall-score-prefix-opp').removeClass('opp-one opp-three opp-four opp-five');
       $('.update-overall-score-prefix-opp').addClass('opp-two');
       $('#color_rating_update').val('opp-two');
     }
     if(overall < 10 && overall >= 5)
     {
       $('.update-overall-score-prefix-opp').removeClass('opp-one opp-two opp-four opp-five');
       $('.update-overall-score-prefix-opp').addClass('opp-three');
       $('#color_rating_update').val('opp-three');
     }
     if(overall < 17 && overall >= 10)
     {
       $('.update-overall-score-prefix-opp').removeClass('opp-one opp-two opp-three opp-five');
       $('.update-overall-score-prefix-opp').addClass('opp-four');
       $('#color_rating_update').val('opp-four');
     }
     if(overall > 16)
     {
       $('.update-overall-score-prefix-opp').removeClass('opp-one opp-two opp-three opp-four');
       $('.update-overall-score-prefix-opp').addClass('opp-five');
       $('#color_rating_update').val('opp-five');
     }
});
$(document).on("change","#update-impact-score-opp", function(){
    var overall = $('#update-impact-score-opp').val() * $('#update-likelihood-score-opp').val();
    $('#update-overall-score-opp').val(overall);

     //color coding
     if(overall < 3)
     {
       $('.update-overall-score-prefix-opp').removeClass('opp-two opp-three opp-four opp-five');
       $('.update-overall-score-prefix-opp').addClass('opp-one');
       $('#color_rating_update').val('opp-one');
     }
     if(overall < 5 && overall >= 3)
     {
       $('.update-overall-score-prefix-opp').removeClass('opp-one opp-three opp-four opp-five');
       $('.update-overall-score-prefix-opp').addClass('opp-two');
       $('#color_rating_update').val('opp-two');
     }
     if(overall < 10 && overall >= 5)
     {
       $('.update-overall-score-prefix-opp').removeClass('opp-one opp-two opp-four opp-five');
       $('.update-overall-score-prefix-opp').addClass('opp-three');
       $('#color_rating_update').val('opp-three');
     }
     if(overall < 17 && overall >= 10)
     {
       $('.update-overall-score-prefix-opp').removeClass('opp-one opp-two opp-three opp-five');
       $('.update-overall-score-prefix-opp').addClass('opp-four');
       $('#color_rating_update').val('opp-four');
     }
     if(overall > 16)
     {
       $('.update-overall-score-prefix-opp').removeClass('opp-one opp-two opp-three opp-four');
       $('.update-overall-score-prefix-opp').addClass('opp-five');
       $('#color_rating_update').val('opp-five');
     }
});


//end update ratings
function getSubobjectives() {
  $("#departmental_sub_objectives").html('');
        var str='';
        var val=document.getElementById('departmental_objective');
        for (i=0;i< val.length;i++) {
            if(val[i].selected){
                str += val[i].value + ',';
            }
        }
        var str=str.slice(0,str.length -1);

	$.ajax({
        	type: "GET",
        	url: "controllers/risk-management/fetch-departmental-sub-objective.php",
        	data:'departmental_objective='+str,
        	success: function(data){
        		$("#departmental_sub_objectives").html(data);
        	},
          error: function(xhr)
          {
            Toast.fire({
                      icon: 'error',
                      title: 'Your request could not be completed. Please try again: '+xhr.status
              });
          }
	});
}

// get department for selected resource Name

function getResDepartment() {
  $("#res_department").html('');
        var str='';
        var val=document.getElementById('EmpNo');
        for (i=0;i< val.length;i++) {
            if(val[i].selected){
                str += val[i].value + ',';
            }
        }
        var str=str.slice(0,str.length -1);

	$.ajax({
    type: "GET",
    url: "controllers/project-management/fetch-resource-dep.php",
    data:'EmpNo='+str,
        	success: function(data){
        		$("#res_department").html(data);
        	},
          error: function(xhr)
          {
            Toast.fire({
                      icon: 'error',
                      title: 'Your request could not be completed. Please try again: '+xhr.status
              });
          }
	});
}


function getSubobjectivesOpp() {
  $("#departmental_sub_objectives_opp").html('');
        var str='';
        var val=document.getElementById('departmental_objective_opp');
        for (i=0;i< val.length;i++) {
            if(val[i].selected){
                str += val[i].value + ',';
            }
        }
        var str=str.slice(0,str.length -1);

	$.ajax({
        	type: "GET",
        	url: "controllers/risk-management/fetch-departmental-sub-objective.php",
        	data:'departmental_objective_opp='+str,
        	success: function(data){
        		$("#departmental_sub_objectives_opp").html(data);
        	},
          error: function(xhr)
          {
            Toast.fire({
                      icon: 'error',
                      title: 'Your request could not be completed. Please try again: '+xhr.status
              });
          }
	});
}

function getDepobjectives() {
  $("#departmental_objectives").html('');
        var str='';
        var val=document.getElementById('department_code');
        for (i=0;i< val.length;i++) {
            if(val[i].selected){
                str += val[i].value + ',';
            }
        }
        var str=str.slice(0,str.length -1);

	$.ajax({
        	type: "GET",
        	url: "controllers/risk-management/fetch-departmental-objective.php",
        	data:'department_code='+str,
        	success: function(data){
        		$("#departmental_objectives").html(data);
        	},
          error: function(xhr)
          {
            Toast.fire({
                      icon: 'error',
                      title: 'Your request could not be completed. Please try again: '+xhr.status
              });
          }
	});
}



function getDepwithStrategicObjectives() {
  $("#department_code").html('');
        var str='';
        var val=document.getElementById('strategic_objectives');
        for (i=0;i< val.length;i++) {
            if(val[i].selected){
                str += val[i].value + ',';
            }
        }
        var str=str.slice(0,str.length -1);

	$.ajax({
        	type: "GET",
        	url: "controllers/risk-management/fetch-departments-with-strategic-objectives.php",
        	data:'strategic_objectives='+str,
        	success: function(data){
        		$("#department_code").html(data);
            console.log(data);
        	},
          error: function(xhr)
          {
            Toast.fire({
                      icon: 'error',
                      title: 'Your request could not be completed. Please try again: '+xhr.status
              });
          }
	});
}


function getOutcomesandKpis() {
  $('#strategic_kpis').html('');
        var str='';
        var val=document.getElementById('strategic_objective');
        for (i=0;i< val.length;i++) {
            if(val[i].selected){
                str += val[i].value + ',';
            }
        }
        var str=str.slice(0,str.length -1);

	$.ajax({
        	type: "GET",
        	url: "controllers/performance-management/fetch-strategic-outcomes.php",
        	data:'strategic_objective='+str,
        	success: function(data){
        		$("#strategic_outcomes").html(data);
        	},
          error: function(xhr)
          {
            Toast.fire({
                      icon: 'error',
                      title: 'Your request could not be completed. Please try again: '+xhr.status
              });
          }
	});
}

$('#strategic_outcomes').on('#strategic_outcomes:selecting', function(e) {

  console.log('Selecting: ' , e.params.args.data);

});

function getKpis() {
        var str='';
        var val=document.getElementById('strategic_outcomes');
        for (i=0;i< val.length;i++) {
            if(val[i].selected){
                str += val[i].value + ',';
            }
        }
        var str=str.slice(0,str.length -1);
        console.log(str);

  $.ajax({
          type: "GET",
          url: "controllers/performance-management/fetch-strategic.kpis.php",
          data:'strategic_outcomes='+str,
          success: function(data){
            $("#strategic_kpis").html(data);
          },
          error: function(xhr)
          {
            Toast.fire({
                      icon: 'error',
                      title: 'Your request could not be completed. Please try again: '+xhr.status
              });
          }
  });
}

function selectUpdatetype(str){
      var id = str;
      var month_picker = $('#month-picker-'+str);
      var quarter_picker = $('#quarter-picker-'+str);

      var type_of_update = $('#type-of-update-'+str).val();
      if(type_of_update === "monthly")
      {
        $(month_picker).removeClass('d-none');
        $(quarter_picker).addClass('d-none');
      }
      else if(type_of_update === "quarterly")
      {
        $(quarter_picker).removeClass('d-none');
        $(month_picker).addClass('d-none');
      }
}



//delegation datepicker
$(document).on("mouseenter","[id*=delegation_start_date]", function(){
  $("[id*=delegation_start_date]").datepicker({
      minDate: new Date(),
      onSelect: function (selected) {
          var dt = new Date(selected);
          dt.setDate(dt.getDate() + 1);
          $("[id*=delegation_end_date]").datepicker("option", "minDate", dt);
      }
  });
  $("[id*=delegation_end_date]").datepicker({
      onSelect: function (selected) {
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          $("[id*=delegation_start_date]").datepicker("option", "maxDate", dt);
      }
  });
});
$(document).on("mouseenter","[id*=delegation_end_date]", function(){
  $("[id*=delegation_end_date]").datepicker({
      onSelect: function (selected) {
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          $("[id*=delegation_start_date]").datepicker("option", "maxDate", dt);
      }
  });
  $("[id*=delegation_start_date]").datepicker({
      minDate: new Date(),
      onSelect: function (selected) {
          var dt = new Date(selected);
          dt.setDate(dt.getDate() + 1);
          $("[id*=delegation_end_date]").datepicker("option", "minDate", dt);
      }
    });
});

$(document).on("mouseenter",'.date', function(){
  $("#dpMonths").datepicker( {
      format: "mm-yyyy",
      startView: "months",
      minViewMode: "months",
      startDate: '-1m',
      //startDate: '0m',
      autoclose: true
  });
});

$(document).on("mouseenter",'.date', function(){
  $("#dpMonths").datepicker( {
      format: "mm-yyyy",
      startView: "months",
      minViewMode: "months",
      startDate: '-2m',
      //  startDate: '',
      autoclose: true
  });
  $(".dpMonths").datepicker( {
      format: "mm-yyyy",
      startView: "months",
      minViewMode: "months",
      startDate: '-2m',
    //  endDate: '-1m',
      autoclose: true
  });
});

$(document).on("mouseenter",'.deadline', function(){
  $(".deadline").datepicker( {
       format: "yyyy-mm-dd",
        startView: "days",
        minViewMode: "days",
        //startDate: '',
        startDate: '0m',
        autoclose: true
  });
});

//textarea limit counter
$(document).on("mouseenter", "#activity,#key_perfomance_indicator", function(){
  $('#activity,#key_perfomance_indicator').maxlength({
  showOnReady:false,
  alwaysShow:true,
  threshold: 0,
  warningClass:"small form-text text-muted",
  limitReachedClass:"small form-text text-danger",
  separator:" / ",
  preText:"",
  postText:"",
  showMaxLength:true,
  placement:"bottom-right-inside",
  message:null,
  showCharsTyped:true,
  validate:false,
  utf8:false,
  appendToParent:false,
  twoCharLinebreak:true,
  customMaxAttribute:null,
  allowOverMax:false
});

});


function triggerClick(str) {
  document.querySelector('#profileImage-'+str).click();
}
function displayImage(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e){
      document.querySelector('#profileDisplay-'+str).setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(e.files[0]);
  }
}

//START FETCHING REPORTS
$(document).on("submit","#all_unique_risks_under_strategic_objectives_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-all-unique-risks-under-strategic-objectives.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});



$(document).on("submit","#activities_without_related_risks_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-activities-without-related-risks.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});


$(document).on("submit","#activities_with_related_risks_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-activities-with-related-risks.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});


$(document).on("submit","#detailed_activities_with_related_risks_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-detailed-activities-with-related-risks.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});


$(document).on("submit","#all_unique_risks_under_departments_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-all-unique-risks-under-departments.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});


$(document).on("submit","#all_unique_risks_under_directorates_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-all-unique-risks-under-directorates.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

$(document).on("submit","#detailed_performance_and_risk_management_report_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/filter-department-risk-management-report.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

$(document).on("submit","#detailed_corporate_performance_and_risk_management_report_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/filter-corporate-risk-management-report.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

$(document).on("submit","#risks_without_activities_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-risks-without-activities.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

$(document).on("submit","#risks_with_without_activities_per_department_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-risks-with-without-activities-per-department.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

$(document).on("submit","#risks_without_activities_per_directorate_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-risks-without-activities-per-directorate.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

$(document).on("submit","#activities_without_related_risks_per_directorate_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-activities-without-related-risks-per-directorate.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

$(document).on("submit","#activities_with_risks_per_directorate_form", function (e){
e.preventDefault();

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-activities-with-related-risks-per-directorate.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

$(document).on("submit","#detailed_activities_with_risks_per_directorate_form", function (e){
e.preventDefault();
$('#directorate_tab').removeClass('d-none');

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-detailed-activities-with-related-risks-per-directorate.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#detailed_activities_with_risks_per_directorate_generated').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#detailed_activities_with_risks_per_directorate_generated').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

$(document).on("submit","#detailed_corporate_performance_and_risk_management_report_directorate_form", function (e){
e.preventDefault();
$('#directorate_tab').removeClass('hidden');

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-directorate-risk-management-report.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

$(document).on("submit","#pc_report_form", function (e){
e.preventDefault();
$('#directorate_tab').removeClass('hidden');

  var form_data = $(this).serializeArray();
  var target_url  = 'views/reports/fetch-pc-report.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#generated-report-data').html('');
      $.blockUI({ message: blockui_spinner });
    },
    success:function(data){
     $.unblockUI();
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#generated-report-data').html(data);
        LoadDatatables();
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});
$(document).on("click",".directorate_activities_without_directorate_risks_tab", function (){

  var loader =`<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;
  var year_id = $('#select_period').val();
  var quarter_id = $('#select_quarter').val();
  var directorate_id = $('#directorates').val();
  $('#detailed_activities_with_risks_per_directorate_generated').html(loader);
  $.post("views/reports/fetch-detailed-activities-with-related-risks-per-directorate.php",
  {
    select_period: year_id,
    select_quarter: quarter_id,
    directorates: directorate_id,
    activity_type: "all"
  },
  function(data,status){
    $('#detailed_activities_with_risks_per_directorate_generated').html(data);
    LoadDatatables();
    console.log(data);
  });
});
//fetch directorates risks--tab
$(document).on("click",".directorate_risks_tab", function (){
  var loader =`<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;
  var year_id = $('#select_period').val();
  var quarter_id = $('#select_quarter').val();
  var directorate_id = $('#directorates').val();
  $('#directorate_risks_with_activities_generated').html(loader);
  $.post("views/reports/fetch-directorate-risks.php",
  {
    year_id: year_id,
    quarter_id: quarter_id,
    directorate_id: directorate_id
  },
  function(data,status){
    $('#loader_directorate_risks').html('');
    $('#directorate_risks_with_activities_generated').html(data);
    LoadDatatables();
    console.log(data);
  });
});
//end fetch directorates risk tab

//START FETCH ACTIVITIES WITH CUMULATIVE RISKS
$(document).on("click",".detailed_activities_with_cumulative_risks_tab", function (){
  var loader =`<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;
  var year_id = $('#select_period').val();
  var quarter_id = $('#select_quarter').val();
  var directorate_id = $('#directorates').val();
  $('#detailed_activities_with_cumulative_risks_generated').html(loader);
  $.post("views/reports/fetch-detailed-activities-with-cumulative-risks.php",
  {
    year_id: year_id,
    quarter_id: quarter_id,
    directorate_id: directorate_id
  },
  function(data,status){
    $('#loader_directorate_risks').html('');
    $('#detailed_activities_with_cumulative_risks_generated').html(data);
    LoadDatatables();
    console.log(data);
  });
});

//START FETCH DIRECTORATE CUMULATIVE RISKS
$(document).on("click",".cumulative_risks_tab", function (){
  var loader =`<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;
  var year_id = $('#select_period').val();
  var quarter_id = $('#select_quarter').val();
  var directorate_id = $('#directorates').val();
  $('#cumulative_risks_tab_generated').html(loader);
  $.post("views/reports/fetch-directorate-cumulative-risks.php",
  {
    select_period: year_id,
    select_quarter: quarter_id,
    directorates: directorate_id
  },
  function(data,status){
    $('#cumulative_risks_tab_generated').html(data);
    LoadDatatables();
    console.log(data);
  });
});

function pcTimelineType(){
      var month_picker = $('#pc-month-picker');
      var quarter_picker = $('#pc-quarter-picker');

      var type_of_update = $('#pc-filter-type').val();
      if(type_of_update === "monthly")
      {
        $(month_picker).removeClass('d-none');
        $(quarter_picker).addClass('d-none');
      }
      else if(type_of_update === "quarterly")
      {
        $(quarter_picker).removeClass('d-none');
        $(month_picker).addClass('d-none');
      }
}


//END FETCHING REPORTS

//search user profile activity logs

$(document).on("keyup","#search", function(){

  var search_value = $("#search").val();
  var form_data = {
      search_value:search_value
  };
  var target_url  = 'views/user/TimeLinePaginated.php';
  var form_method = 'POST';

    $.ajax({
    data : form_data,
    url  : target_url,
    method : form_method,
    beforeSend: function()
    {
      $('#search-loader').html(`<div class="spinner-border" role="status">
        <span class="sr-only">Searching...</span>
      </div>`);
    },
    success:function(data){
     $('#search-loader').html('');
    if(data == 'failed')
    {
        Toast.fire({
              icon: 'error',
              title: 'Failed. Please try again'
          });
    }
    else
    {
        $('#timeline-data').html(data);
        console.log(data);
    }

    },
    error: function(xhr)
      {
        $.unblockUI();
        Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
          });
      }

  });
});

//idle time script
/*
 *1second               = 1000 milliseconds
 *60 seconds(1 min)     = 60000 milliseconds
 *300 seconds(5 min)    =300000 milliseconds
 */
var idleTime=0;
var timeout;
 timeout = 1800000; //30 minutes Timeout
//timeout = 60000;

//converting milliseconds to minutes
var min = timeout / 1000 / 60;
min = Math.floor(min) + " minutes";

//To be used during demonstration
var sec = timeout / 1000 ;
sec = Math.floor(sec) + " seconds";


var idleInterval = setInterval(timerIncrement, timeout);


//Zero the idle timer on mouse movement
$(document).mousemove(function(){
    idleTime = 0;
});
$(document).keypress(function(){
    idleTime = 0;
});

  function timerIncrement(){
    idleTime = idleTime + 1;
    if(idleTime > 1){ //1 minute

      Swal.fire({
      title: 'System Idle',
      text: "You have not interacted with the system for more than " +min+ " . Your session will be reset",
      icon: 'warning',
      allowOutsideClick: false,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'OK'
      }).then((result) => {
      if (result.value) {
        $('.log-out-link').click();
        setTimeout(function() {
            $('.login-link').click();
        }, 1000);
      }
      });
    }
}
//end idle time script




//make password visible
$(document).on("keydown",".pwd", function(){
  $('.password-reveal-icon').removeClass('d-none');
    $(".reveal").mousedown(function(){
      $(".pwd").replaceWith($('.pwd').clone().attr('type', 'text'));
    });
    $(".reveal").mouseup(function(){
      $(".pwd").replaceWith($('.pwd').clone().attr('type', 'password'));
    });
    $(".reveal").mouseout(function(){
      $(".pwd").replaceWith($('.pwd').clone().attr('type', 'password'));
    });
});


function CannotLogin(str){
      var id = str;

      var form_data = {
        user: id
      }

      $.ajax({
         type: "POST",
         url :"controllers/admin-portal/CannotLogin.php",
         data: form_data,
         beforeSend:function()
         {
           $.blockUI({ message: blockui_spinner });
         },
         success:function(data)
         {
           $.unblockUI();
           if(data == 'success')
           {
             Swal.fire({
                icon: 'success',
                title: 'Live Support Team is reviewing your request. You will get a response shortly'
              });

           }
           else
           {
             console.log(data);
             Toast.fire({
                icon: 'error',
                title: 'Failed. Please try again'
              });
           }
         },
        error: function(xhr)
        {
          $.unblockUI();
            Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
            });
        }
      });
}

/*
$(document).on("mousedown",".reveal", function(){
  $(".pwd").replaceWith($('.pwd').clone().attr('type', 'text'));
});
$(document).on("mouseup",".reveal", function(){
  $(".pwd").replaceWith($('.pwd').clone().attr('type', 'password'));
});
$(document).on("mouseout",".reveal", function(){
  $(".pwd").replaceWith($('.pwd').clone().attr('type', 'password'));
});
*/


  //caps lock notifier
  $(document).on("keyup","#password", function(){
    if (event.getModifierState("CapsLock")) {
        $('#caps-lock').removeClass('invisible');
      } else {
        $('#caps-lock').addClass('invisible');
      }
    });
