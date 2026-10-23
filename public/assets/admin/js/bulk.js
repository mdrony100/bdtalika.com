(function($) {
    "use strict"; 
    
    let ids = [];
    let id_add = false;
    var deleteUrl = $("#bulk_apply").attr("data-href");

    $("#headercheck").click(function(){
        if(this.checked){
            $("#bulk_apply").attr("data-target"," ");

            $('.columnCheck').each(function(){
                $(".columnCheck").prop('checked', true);
            })

            ids = $('.columnCheck:checked').map(function(){
                return $(this).val();
            }).get();

            if(id_add == false){
                var href = deleteUrl+'?bulk_id='+ids;
                $("#bulk_apply").attr("data-href",href);
            }

            if($("#bulk_option").val() != ''){
                $("#bulk_apply").attr("data-target","#deleteModal");
            }
        }else{
            $("#bulk_apply").attr("data-href",deleteUrl);
            $("#bulk_apply").attr("data-target","");
            $('.columnCheck').each(function(){
                $(".columnCheck").prop('checked', false);
            })
        }
    });

    $(document).on('click','.columnCheck',function(){

        ids = $('.columnCheck:checked').map(function(){
            return $(this).val();
        }).get();

        var href = deleteUrl+'?bulk_id='+ids;
        $("#bulk_apply").attr("data-href",href);

        if($("#bulk_option").val() != ''){
                $("#bulk_apply").attr("data-target","#deleteModal");
            }

        if(ids.length == 0){
            $("#bulk_apply").attr("data-href",deleteUrl);
        }
    })

    $(document).on('change','#bulk_option',function(){
        var option = $(this).val();
        if(option == "delete"){
            var isChecked = $('.columnCheck').is(':checked')
            if(isChecked){
                $("#bulk_apply").attr("data-target","#deleteModal");
            }

        }else{
            $("#bulk_apply").attr("data-target","");
        }
    })
})(jQuery);