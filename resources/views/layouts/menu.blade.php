@role('Lecturer')
<li><a href="{{ route('home') }}">HOME</a></li>
<li><a href="{{ route('units.lecturer') }}">VIEW UNITS</a></li>
@endrole

@role('Administrator')
<li><a href="{{ route('home') }}">HOME</a></li>
<li><a href="{{ route('units.index') }}">MANAGE UNITS</a></li>
<li><a href="{{ route('units.index') }}">MANAGE STUDENTS</a></li>
@endrole
