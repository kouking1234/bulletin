$(document).on('click','.post_process',function(){
  scroll_position = $(window).scrollTop();
  $('body').addClass('fixed').css({'top':-scroll_position});
  $('.post_window').fadeIn();
  $('.modal').fadeIn();
});

$(document).on('click','.cancel',function(){
  $('.post_window').fadeOut();
  $('.modal').fadeOut();
});

$(document).on('click','.post_button',function(){
  $('form').submit(function(){
     if($('#message').val()==''){
    $('.empty_message').text('メッセージを入力してください');
    return false;
  }
 
  });
}
);


$(document).on('click','#edit_btn',function(){
  $('.edit').fadeIn();
});

$(document).on('click','.cancel',function(){
  $('.edit').fadeOut();
  $('.modal').fadeOut();
});

// $('editing').click(function(){
//   window.location.reload(true);
// });
