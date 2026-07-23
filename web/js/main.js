$(document).ready(function(){
	
  $(document).on("click", '.tranForm', function(e) { 
      
      e.preventDefault();
      var target = $(this).attr("href");
      $("#form .modal-dialog .modal-content" ).load(target, function() { 
           $('#form').modal('show'); 
      });

  });  

  function pdfViz($url){
    let html = '<object data="'+ $url +'" type="application/pdf" width="100%" height="100%" >';
                 html +='<p>Paymet Request<a id="pdf-viz-li-k" href="'+ $url +'" >to the PDF!</a></p>';
        html +='</object>'
       $('#pdf-viz-modal .modal-dialog .modal-content .modal-body').html(html);
       $('#pdf-viz-modal').modal('show');
}

 $('body').on("click", 'a.document', function(event) { 
       event.preventDefault();
      var $url = $(this).attr("href");
      console.log($url);
      pdfViz($url);
  });

  $(document).on("click", '.charForm', function(e) { 
      e.preventDefault();
      var target = $(this).attr("href");
      $("#charge-form .modal-dialog .modal-content" ).load(target, function() { 
           $('#charge-form').modal('show'); 
      });
  });

  $('body').on("click", 'a.open-request', function(e) { 
      e.preventDefault();
      var target = $(this).attr("href");
      // load the url and show modal on success
      $("#request-form .modal-dialog .modal-content ").load(target, function() { 
           $('#request-form').modal('show'); 
      });
  });


  $(document).on('click', '#requestForPayment', function(event) {

      event.preventDefault();
      
      var selection = [];
      
      $("input[name^='Transaction']:checked").each(function(index, el) {
          selection[index] = $(this).val();
      });

        $( "#request" ).dialog({
           width: 600,
           height: 500,
        });

        var html = '<div class="text-success" ><h6>loading</h6><img src="/web/img/ajax-loader.gif" /></div>';
         $('#request div div').html(html);
      
        $url ='/web/transaction/request';
        html='';
        var redirect = true;
        $.ajax({
          url: $url,
          method: 'POST',
          data: {'data': selection },
        }).done(function(result){
          result.status.forEach(function(element, index){

            var clsStatus = element.success ? 'alert-success' : 'alert-danger';
            html +='<div class="alert  '+clsStatus+' stautsTimbrado col-md-12" >';
            html +='<h6> Bill: '+element.tran_number+'</h6>';
            html +='<ul>';
              element.status.forEach(function(el){
                html +='<li>'+el+'</li>';
              });
            html +='</ul>';
            html +='</div>'  
            html +='</div>'

          })

            html += result.success ? '<div class="alert alert-warning col-md-12 stautsTimbrado"  ><h6>Loading Document</h6><img src="/web/img/ajax-loader.gif" /></div></div>':'';

            $('#request div div').html(html);

            if(result.success){
                setTimeout(
                  function (){ window.location.href = '/web/payment-request/?id='+ result.request_id },
                  3000
                );
             }
          $.pjax.reload({container:'#w0-pjax'});
        });
  });

  $(function() {
    $(document).tooltip();
  });

});
