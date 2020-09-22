$('#testing_form').submit(function(){
    
    if($(this).attr('validated')=='false'){
        var email = $('input[name="email"]').val();
        var test_id = $('#testing_form').attr('test_id');
        
        $.ajax({ 
            type: 'POST',
            url: 'index.php?r=Testing&action=check',
            data: 'email='+email+"&test_id="+test_id,
            success: function(answer){  
                if(answer == 'no'){
                    $('#testing_form').attr('validated',"true");
                    $('#testing_form').submit();
                } 
                if(answer == 'yes'){
                    alert('Такой пользователь уже отправлял ответы!');
                }
            }
     });
     return false;  
    }                            
   return true;
});


$('[type="radio"]').change(function() {
    name = $(this).attr('name');
    question_id = name.substr(0, name.indexOf('['));

    $('[type="radio"]').filter("[name*='"+question_id+"[']").not(this).prop('checked', false);
});

