$(document).ready(function(){

    $(".images").click(function (){
        $(".checkFirst").click(function (){
            $(".checkFirst").prop("checked",false);
            $(this).prop("checked", true);
        });
    });

});