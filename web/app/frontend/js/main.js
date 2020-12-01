$("#email").inputmask({
    mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
    greedy: false,
    onBeforePaste: function (pastedValue, opts) {
      pastedValue = pastedValue.toLowerCase();
      return pastedValue.replace("mailto:", "");
    },
    definitions: {
      '*': {
        validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
        casing: "lower"
      }
    }
  });
$(".chosen").chosen({
    disable_search_threshold: 10,
    no_results_text: "Oops, nothing found!",
    width: "250px"
});

$('.chosen').on('change', function(evt, params) {
   let code = $(this).attr('data-code');
   let dataArea = $(this).attr('data-area');

   if (dataArea == 'arrAreas') {
       areas = 'arrCities';
   }
   else if(dataArea == 'arrCities'){
       areas = ['arrCities','arrRegions'];
   }
   $.ajax({
        data:({ areas, code }),
        type: "GET",
        dataType: "html",
        success: function(data) {
           $( 'body' ).html( data );
        }
   });
});

