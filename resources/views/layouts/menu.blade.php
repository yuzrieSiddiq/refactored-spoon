{{-- @role('Lecturer')
<li><a href="{{ route('home') }}">HOME</a></li>
<li><a href="{{ route('units.lecturer') }}">VIEW UNITS</a></li>
@endrole --}}

@role('Administrator')
<li><a href="{{ route('home') }}">MANAGE USERS</a></li>
<li><a href="{{ route('units.index') }}">MANAGE UNITS</a></li>
@endrole
