@foreach($childs as $child)
    <li><a href="{{ url('category/'.$child->slug) }}" title="{{ $child->name }}">{{ $child->name }}</a></li>
@endforeach
