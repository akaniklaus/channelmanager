@if($children = $currentRoom->plans()->get())
    @if(count($children))
        <ul>
        @foreach($children as $child)
            <li>
                {{$child->name}}
                @include('bulk.children',['currentRoom'=>$child])
            </li>
        @endforeach
        </ul>
@endif
@endif