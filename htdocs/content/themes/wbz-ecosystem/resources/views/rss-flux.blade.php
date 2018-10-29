<ul>
	@foreach($data as $item)
		<li>
			<a class='rss-retarget' target='_blank' href={{ $item['link'] }}>{!! $item['title'] !!}</a>
			{!! $item['summary'] !!}
		</li>
	@endforeach
</ul>