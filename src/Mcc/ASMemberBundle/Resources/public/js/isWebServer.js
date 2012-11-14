$(document).ready(function()
{
    $("a.ajaxable").click(function(event){
        event.preventDefault(); 
        var $alink = $(this);
        var url = $alink.attr('href');
        $alink.html('checking <img src="'+ ajax +'" style="width:15px; height:auto" /> ');
        var data = {};
        $.post(
          url,
          data,
          function(returnData) {
            $alink.html(returnData.isWebServer); 
          },
          'json'
        );

      });
      
      $("a.checkAll").click(function(event){
          event.preventDefault(); 
            $("a.ajaxable").each(function(event){
                //event.preventDefault(); 
                var $alink = $(this);
                var url = $alink.attr('href');
                $alink.html('checking <img src="'+ ajax +'" style="width:15px; height:auto" /> ');
                var data = {};
                $.post(
                  url,
                  data,
                  function(returnData) {
                    $alink.html(returnData.isWebServer); 
                  },
                  'json'
                );

              });

      });
      
});