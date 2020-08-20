$(document).ready(function(){
    let x=0;
    $(".images").click(function (){
        $(".checkFirst").click(function (){
                $(".checkFirst").prop("checked", false);
                $(this).prop("checked", true);
        })
    });

});