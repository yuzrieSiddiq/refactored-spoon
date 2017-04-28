<div class="list-group">
    <a href="{{ route('home') }}" class="list-group-item
        @if(Request::is('/') || Request::is('home')) active @endif">
        HOME
    </a>

    @hasrole('Lecturer')
    <a href="{{ route('quizzes.create.upload') }}" class="list-group-item
        @if(Request::is('/quizzes/upload*')) active @endif">
        UPLOAD QUIZ
    </a>
    @endhasrole

    @hasrole('Administrator')
    <a href="{{ route('units.index') }}" class="list-group-item
        @if(Request::is('/units*')) active @endif">
            MANAGE UNITS
    </a>
    @endhasrole
</div>
