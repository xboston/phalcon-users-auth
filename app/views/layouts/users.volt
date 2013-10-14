<div class="ui pointing icon-- menu">
    <a class="active item" href="{{ url(['for':'index']) }}">
        <i class="home icon"></i> Home
    </a>
    <a class="item" href="{{ url(['for':'register']) }}">
        <i class="add sign icon"></i> Register
    </a>

    <a class="item" href="{{ url(['for':'forgot-password']) }}">
        <i class="question icon"></i> Forgot password
    </a>

    <div class="right menu">
        {% if authUser %}
            <div class="ui dropdown item">
                Login as <strong>{{ auth.getUser().name }}</strong><i class="dropdown icon"></i>

                <div class="menu">
                    {{ link_to(['for':'edit-profile'], '<i class="edit icon"></i> Profile Edit','class':'item') }}
                    {{ link_to(['for':'change-password'], '<i class="edit sign icon"></i> Change password','class':'item') }}
                    {{ link_to(['for':'logout'], '<i class="off icon"></i> Logout','class':'item') }}
                </div>
            </div>
        {% else %}
            <a class="item" href="{{ url(['for':'login']) }}">
                <i class="user icon"></i> Login
            </a>
        {% endif %}
    </div>

</div>

{{ content() }}
