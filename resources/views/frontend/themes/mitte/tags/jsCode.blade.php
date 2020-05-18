    
    <script>
		$(function(){
			fetchData();

            $('#tag-loadmore').click(function(){
                $(this).addClass('disabled').html('Loading...');
                var page_url = $('#tag-loadmore').attr('data-next-page-url');
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
                    $('#tag-loadmore').removeClass('disabled').html('Load More');
                	if(res.pagination.total == 0) {
                    	$("#tag-webkit").remove();
                    	$("#tag-nofound").removeClass('hidden');
                    } else {
                    	$("#tag-webkit").remove();
                    	$("#tag-container").append(res.html);
                        
                        if(res.pagination.next_page_url) {
                            $("#tag-loadmore").removeClass('hidden')
                                .attr('data-next-page-url', res.pagination.next_page_url);
                        } else {
                            $("#tag-loadmore").addClass('hidden');
                        }
                        
                    }
                }
            });
	    };
	</script>