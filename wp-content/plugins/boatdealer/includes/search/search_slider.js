jQuery(document).ready(function($) {
    
  var max =  $("#meta_price_max").val();
  var choice_price_min = $("#choice_price_min").val();
  var choice_price_max = $("#choice_price_max").val();  
  if( typeof choice_price_min === 'undefined' || typeof choice_price_min === 'undefined' )
     {
       choice_price_min = 0;
       choice_price_max = max;      
     }
  $("#boatdealer_meta_price").slider({
     range: true,
     step: 100, 
     min: 0, 
     max: max, 
     values: [choice_price_min, choice_price_max], 
     slide: function(event, ui) {
        $("#meta_price").val(ui.values[0] + " - " + ui.values[1]);}
  });
  var $sliderMax  = $("#meta_price_max2");
  var $choiceMin = $("#choice_price_min2")
  var $choiceMax = $("#choice_price_max2")
  var maxVal =  $sliderMax .val();
  var choiceMinVal = $choiceMin.val();
  var choiceMaxVal = $choiceMax.val(); 
  if( typeof choiceMinVal === 'undefined' || typeof choiceMaxVal === 'undefined' )
     {
       choiceMinVal = 0;
       choiceMaxVal = maxVal;      
     }
  $("#boatdealer_meta_price2").slider({
     range: true,
     step: 100, 
     min: 0, 
     max: maxVal, 
     values: [choiceMinVal, choiceMaxVal], 
     slide: function(event, ui) {
        $("#meta_price2").val(ui.values[0] + " - " + ui.values[1]);}
  }); 
  
  
  $(window).bind("load resize scroll", function(e) { 
  
  
  /* 2018 */
  
  
      if ( $("#boatdealer_meta_price").lenght  )
      {
  
          var boatdealerlabelprice = $(".boatdealerlabelprice")
          var offset = boatdealerlabelprice.offset();
          // console.log(offset.top);
          var boatdealer_meta_price = $("#boatdealer_meta_price")
          var offset2 = boatdealer_meta_price.offset();
          // console.log(offset2.top);
          
    
          
          var distance = offset2.top - offset.top
          var boatsellerwidth = document.body.offsetWidth;
          
    
            
            
          
          /*
          console.log(distance);
          console.log(boatsellerwidth);
          */
          
          
          
          if (boatsellerwidth < 783)
          {
             var deveria = 60;
          }
          else
          {
            var deveria = 30; 
          }
          
          
    
          
          if( distance != deveria)
          {
             // console.log(distance);
             var missing = (deveria - distance)
             // console.log('missing '+missing);
             var marginTop = parseInt( boatdealer_meta_price.css("marginTop")); 
             // console.log('top '+marginTop);
             var tofix = (marginTop + missing);
             boatdealer_meta_price.css("margin-top", tofix);
          }
      
      }
      
        
      if ( $("#boatdealer_meta_price2").lenght  )
      {
            
          var boatdealerlabelprice = $(".boatdealerlabelprice2")
          var offset = boatdealerlabelprice.offset();
          var boatdealer_meta_price = $("#boatdealer_meta_price2")
          
          if (! boatdealer_meta_price.length)
            return;
          
          var offset2 = boatdealer_meta_price.offset();
          
          /*
          console.log(offset2.top);
          console.log(offset.top); 
          */
          deveria = 30;
          var distance = offset2.top - offset.top
          if( distance != deveria)
          {
             var missing = (deveria - distance)
             var marginTop = parseInt( boatdealer_meta_price.css("marginTop")); 
             var tofix = (marginTop + missing);
             boatdealer_meta_price.css("margin-top", tofix);
          } 
   }  
});  
 
});