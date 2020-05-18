<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($pages as $row)
        <url>
            <loc>{{ url($row->slug) }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($row->timestamp)->timezone('UTC')->toAtomString() }}</lastmod>
            <priority>0.4</priority>
        </url>
    @endforeach
    @foreach($categories as $row)
        <url>
            <loc>{{ url('category/'.$row->slug) }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($row->timestamp)->timezone('UTC')->toAtomString() }}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($posts as $row)
        <url>
            <loc>{{ url('post/'.$row->slug_url) }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($row->timestamp)->timezone('UTC')->toAtomString() }}</lastmod>
            <priority>0.9</priority>
        </url>
    @endforeach
    @foreach($tags as $row)
        <url>
            <loc>{{ url('tag/'.$row->slug) }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($row->timestamp)->timezone('UTC')->toAtomString() }}</lastmod>
            <priority>0.7</priority>
        </url>
    @endforeach
    
    <!--AMP-->
        <url>
            <loc>{{ url('/amp') }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse(date('Y-m-d H:i:s'))->timezone('UTC')->toAtomString() }}</lastmod>
            <priority>0.9</priority>
        </url>
    @foreach($categories as $row)
        <url>
            <loc>{{ url('amp/cateory/'.$row->slug) }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($row->timestamp)->timezone('UTC')->toAtomString() }}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($posts as $row)
        <url>
            <loc>{{ url('amp/post/'.$row->slug_url) }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($row->timestamp)->timezone('UTC')->toAtomString() }}</lastmod>
            <priority>0.9</priority>
        </url>
    @endforeach
    @foreach($tags as $row)
        <url>
            <loc>{{ url('amp/tag/'.$row->slug) }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($row->timestamp)->timezone('UTC')->toAtomString() }}</lastmod>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>