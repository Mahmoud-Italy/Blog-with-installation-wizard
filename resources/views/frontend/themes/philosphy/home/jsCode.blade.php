    
    <script>
		$(function(){
			fetchData();

            $('#home-loadmore').click(function(){
                $(this).addClass('disabled').html('Loading...');
                var page_url = $('#home-loadmore').attr('data-next-page-url');
                fetchData(page_url);
            });
		});

        function clMasonryFolio(){
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
            var page_url = page_url || "{{ url('/home/render') }}";
            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
            $.ajax({
                url : page_url,
                type : "GET",
                dataType : "json",
                success : function(res) { 
                    $('#home-loadmore').removeClass('disabled').html('Load More');
                	if(res.pagination.total == 0) {
                    	$("#home-webkit").remove();
                    	$("#home-nofound").removeClass('hidden');
                    } else {
                         clMasonryFolio();
                    	$("#home-webkit").remove();
                    	$("#home-container").append(res.html).each(function(){
                            $('.masonry').masonry('reloadItems');
                        });

                        setTimeout(function(){
                            $('.masonry__brick').removeClass('opacity-0');
                        },500);
                       

                        if(res.pagination.next_page_url) {
                            $("#home-loadmore").removeClass('hidden')
                                .attr('data-next-page-url', res.pagination.next_page_url);
                        } else {
                            $("#home-loadmore").addClass('hidden');
                        }
                        
                    }
                }
            });
	    };
	</script>