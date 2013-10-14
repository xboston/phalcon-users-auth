<h2>Login</h2>

<div class="ui horizontal icon divider">
    <i class="circular heart icon"></i>
</div>

{{ content() }}

<div class="ui two column middle aligned relaxed grid basic segment">
    <div class="column">
        {{ form('action': ['for':'login'],'method':'post') }}
        <div class="ui fluid form segment">
            <h3 class="ui header">Log-in</h3>

            <div class="field">
                {{ form.label('email') }}
                {{ form.render('email') }}
                {{ form.messages('email') }}
            </div>

            <div class="field">
                {{ form.label('password') }}
                {{ form.render('password') }}
                {{ form.messages('password') }}
            </div>

            <div class="inline field">
                <div class="ui checkbox">
                    {{ form.render('remember') }}
                    {{ form.label('remember') }}
                </div>
                {{ form.messages('remember') }}
            </div>

            {{ form.render('Login') }}
            {{ link_to(['for':'forgot-password'], "Forgot my password") }}

        </div>
        {{ form.render('csrf', ['value': security.getToken()]) }}
        {{ end_form() }}
    </div>
    <div class="ui vertical divider">
        Or
    </div>
    <div class="center aligned column">
        {{ link_to(['for':'register'], '<i class="signup icon"></i> Register','class':'huge green ui labeled icon button') }}
    </div>
</div>
