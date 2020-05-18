    
    <script>
		$(function(){
			fetchData();

            $('#category-loadmore').click(function(){
                $(this).addClass('disabled').html('Loading...');
                var page_url = $('#category-loadmore').attr('data-next-page-url');
                fetchData(page_url);
            });
		});
		function fetchData(page_url){
	    	var page_url = page_url || "{{ url()->current().'/render' }}";
            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
            $.ajax({
                url : page_url,
                type : "GET",
                dataType : "json",
                success : function(res) { 
                    $('#category-loadmore').removeClass('disabled').html('Load More');
                	if(res.pagination.total == 0) {
                    	$("#category-webkit").remove();
                    	$("#category-nofound").removeClass('hidden');
                    } else {
                    	$("#category-webkit").remove();
                    	$("#category-container").append(res.html);
                        
                        if(res.pagination.next_page_url) {
                            $("#category-loadmore").removeClass('hidden')
                                .attr('data-next-page-url', res.pagination.next_page_url);
                        } else {
                            $("#category-loadmore").addClass('hidden');
                        }
                        
                    }
                }
            });
	    };
	</script>