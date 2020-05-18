    
    <script>
		$(function(){
			fetchData();

            $('#search-loadmore').click(function(){
                $(this).addClass('disabled').html('Loading...');
                var page_url = $('#search-loadmore').attr('data-next-page-url');
                fetchData(page_url);
            });
		});

        function clMasonryFolio(argument) {
            var containerBricks=$('.masonry');
            containerBricks.imagesLoaded(function(){
                containerBricks.masonry({
                    itemSelector:'.masonry__brick',
                    percentPosition:true,
                    resize:true
                });
            });
            containerBricks.imagesLoaded().progress(function(){
                containerBricks.masonry('layout');
            });
        };

		function fetchData(page_url){
	    	var page_url = page_url || "{{ url()->current().'/render?q='.$_GET['q'] }}";
            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
            $.ajax({
                url : page_url,
                type : "GET",
                dataType : "json",
                success : function(res) { 
                    $('#search-loadmore').removeClass('disabled').html('Load More');
                	if(res.pagination.total == 0) {
                    	$("#search-webkit").remove();
                    	$("#search-nofound").removeClass('hidden');
                    } else {
                        clMasonryFolio();
                    	$("#search-webkit").remove();
                    	$("#search-container").append(res.html).each(function(){
                            $('.masonry').masonry('reloadItems');
                        });

                        setTimeout(function(){
                            $('.masonry__brick').removeClass('opacity-0');
                        },500);
                        
                        if(res.pagination.next_page_url) {
                            $("#search-loadmore").removeClass('hidden')
                                .attr('data-next-page-url', res.pagination.next_page_url);
                        } else {
                            $("#search-loadmore").addClass('hidden');
                        }
                        
                    }
                }
            });
	    };
	</script>