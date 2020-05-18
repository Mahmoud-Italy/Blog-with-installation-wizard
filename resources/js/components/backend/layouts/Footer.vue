<template>
    <div class="">
        
        <!-- Footer -->
		<footer class="u-footer d-md-flex align-items-md-center text-center text-md-left text-muted">
			
			<!-- Footer Menu -->
			<ul class="list-inline mb-3 mb-md-0">
				<li class="list-inline-item mr-4">
					Thank you for using <a class="text-muted" href="#">iBlog {{ row.version }}</a>
				</li>
			</ul>
			<!-- End Footer Menu -->

			<!-- Copyright -->
			<span class="text-muted ml-auto"> 
				<!-- <a class="text-muted" href="#" target="_blank">iBlog {{ row.version }}</a>
				&copy; {{ row.year }}  All Rights Reserved. -->
			</span>
			<!-- End Copyright -->

		</footer>
		<!-- End Footer -->

    </div>
</template>

<script>
    export default {
    	namme: 'Footer',
    	data(){
    		return {
	    		row: {
	    			year: '2020',
	    			version: 'v1.0',
	    		},
	    	}
    	},
        mounted() {},
    }
</script>
