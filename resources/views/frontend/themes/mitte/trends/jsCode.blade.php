    
    <script>
		$(function(){
			fetchTrends();
		});
		function fetchTrends(page_url){
            var page_url = page_url || "{{ url('/home/trends') }}";
            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
            $.ajax({
                url : page_url,
                type : "GET",
                dataType : "json",
                success : function(res) { 
                	if(!res.html.length) {
                    	$("#trend-webkit").remove();
                    } else {
                    	$("#trend-webkit").remove();
                    	$("#trend-container").append(res.html);                        
                    }
                }
            });
	    };
	</script>