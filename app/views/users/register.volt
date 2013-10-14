<h2>Register</h2>

<div class="ui horizontal icon divider">
    <i class="circular heart icon"></i>
</div>

{{ content() }}

<div class="column">

    <div class="ui fluid form segment">
        <h3 class="ui header">Register</h3>
        {{ form('action': ['for':'register'],'method':'post','autocomplete':'off') }}
        <div class="field">
            {{ form.label('name') }}
            {{ form.render('name') }}
            {{ form.messages('name') }}
        </div>
        <div class="field">
            {{ form.label('email') }}
            {{ form.render('email') }}
            {{ form.messages('email') }}
        </div>
        <div class="two fields">
            <div class="field">
                {{ form.label('password') }}
                {{ form.render('password') }}
                {{ form.messages('password') }}
            </div>
            <div class="field">
                {{ form.label('confirmPassword') }}
                {{ form.render('confirmPassword') }}
                {{ form.messages('confirmPassword') }}
            </div>
        </div>
        <div class="inline field">
            <div class="ui checkbox">
                {{ form.render('terms') }}
                {{ form.label('terms') }}
            </div>
            {{ form.messages('terms') }}
        </div>
        {{ form.render('Sign Up') }}
    </div>
    {{ form.render('csrf', ['value': security.getToken()]) }}
    {{ form.messages('csrf') }}
    {{ end_form() }}
</div>
