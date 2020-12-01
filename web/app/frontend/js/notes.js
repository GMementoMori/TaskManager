(function($) {

  /* left menu*/
  $('.js-nav-menu-toggle').on('click', function() {
    $(this).parents('.navigation-menu').toggleClass('navigation-menu--open');
  });
  
  $('html').on('click', function(e) {
    if(!$(e.target).closest('.js-nav-menu').length &&
      ($('.js-nav-menu').hasClass('navigation-menu--open'))) {
        $('.js-nav-menu').removeClass('navigation-menu--open');
    }
  });
  
  //main notes show
  let speed = 500;

  let listNotes = $('.block-note');

  for (var i = 0; i < listNotes.length; i++) {
    
    $('.block-note:eq('+i+')').animate({opacity: "0.9"}, speed);

    speed += 500; 

    if (speed > 2500){

        speed = 500;

    }
  }
  
  // show full note
  function showFullNote(params){

      if (params == 'add') {

         $('.full-conc-btn-0').html('').removeClass('remove-btn').attr('title', '');

         $(".block-full-note").show();
      }
      else if(params == 'more'){

          $('.full-conc-btn-0').html('<span>&times;</span>').addClass('remove-btn').attr('title', 'Remove note').on('click', function() {

               let noteId = $('.block-full-note').attr('data-note-id');
               
               $('.popup-delete').attr('data-note-id', noteId).show();

               $(".block-full-note").hide();

          });
          
          $(".block-full-note").show();
      }
      $('.for-detail').hide();
  }

  //ajax : save, add, delete

  function ajaxRequestNote(obj){
     var request = $.ajax({
            method: "POST",
            data: obj,
            dataType: "html"
     });
     request.done(function( data ) {
         
          // $( "body" ).html( data ); //for show errors from php

          window.location=window.location;

     });
  }

  //add notes
  $('#add-note').on('click', function() {

     $('.full-conc-btn-0').attr('data-note-id', '');

     $(".full-note-title").val('');

     $(".full-main-note-text").val('');

     $('.navigation-menu').toggleClass('navigation-menu--open');

     showFullNote('add');
  });

  //close popup delete
  $('#no-delete').on('click', function() {

     $('.popup-delete').attr('data-note-id', '');

     $('.popup-delete').hide();

     showFullNote('more');
  });

  //close full note
  $('#close-full-bth').on('click', function() {

      let noteId = $('.block-full-note').attr('data-note-id');

      $('.block-note[data-note-id='+noteId+']').show();

      $(".block-full-note").hide();

      $('.for-detail').show();
  });

  //save full note
  $('#save-full-btn').on('click', function() {

     let id = $('.block-full-note').attr('data-note-id');
     
     let title = $('.full-note-title').val();

     let text = $('.full-main-note-text').val();

     let resultForAjax = {
        'save': true,
        'id': id,
        'title': title,
        'text': text
     };
    
     ajaxRequestNote(resultForAjax);
    
  });

  //final delete
  $('#yes-delete').on('click', function() {
    
     let noteId = $('.popup-delete').attr('data-note-id');

     let resultForAjax = {
         'delete': true,
         'id': noteId
         };

     ajaxRequestNote(resultForAjax); //delete request

  });

  //select some note for whole show
  $('.for-detail').on('click', function() {
     
     $('input').val('');

     $('textarea').val('');

     let noteId = $(this).attr('data-note-id');

     let title = $('.note-title[data-note-id='+noteId+']').text();

     let text = $('.main-note-text[data-note-id='+noteId+']').text();

     $('.block-full-note').attr('data-note-id', noteId);

     $(".full-note-title").val(title);

     $(".full-main-note-text").val(text);

     $('.block-note[data-note-id='+noteId+']').hide();

     // $(".block-full-note").show();
     showFullNote('more');


  });
})(jQuery);

