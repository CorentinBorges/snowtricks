$(document).ready(function(){
    let x=0;
    $(".images").click(function (){
        $(".checkFirst").click(function (){
            if ($(this).is(":checked")) {
                $(this).prop("checked", false);
                console.log('coucou')
            }
            else{
                $(".checkFirst").prop("checked", false);
                $(this).prop("checked", true);
            }
        })
    });

});