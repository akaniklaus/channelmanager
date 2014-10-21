@if($children = $currentRoom->children()->get())
    @if(count($children))
        <ul>
        @foreach($children as $child)
            <li>
                {{$child->name}} ({{$child->formula_type}} {{$child->formula_value}})
                @include('bulk.children',['currentRoom'=>$child])
            </li>
        @endforeach
        </ul>
@endif
@endif