


    let nbPage=1
    for (i=1;i<=nbMessages;i++) {
    if (i % 10 === 0) {
    nbPage++
    }
    }
    let x=1;


    $(".list").hide();
        $(".list1").show();
        $(".page1").addClass("page-active")
        $(".page").click(function (){
            let page=$(this).val()
            $(".list"+(x)).hide();
            $('.list'+page).show();
            $(this).addClass("page-active");
            $(".page"+x).removeClass("page-active");
            x=Number(page);

        })

        $(".previous").click(function (){
            if (x>1) {
                let page=x-1;
                $(".list"+(x)).hide();
                $('.list'+page).show();
                x = page;

            }
        })
        $(".next").click(function (){
            if (x<nbPage) {

                let page=x+1;
                console.log(x+1);
                $(".list"+(x)).hide();
                $('.list'+page).show();
                x = page;

            }
        })



        /*$(".list1").hide();

        $(".page2").click(function() {
            $(".list0").hide();
            $('.list1').show();
        });
        $(".page1").click(function() {
            $(".list1").hide();
            $('.list0').show();
        });*/



