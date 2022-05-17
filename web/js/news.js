$(function(){

   $("#news-image").on('change', function (){
      if(this.files.length){
         $("#img").attr("src",  URL.createObjectURL(this.files[0]));
      }
   });


});