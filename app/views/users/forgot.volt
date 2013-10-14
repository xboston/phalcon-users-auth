<h2>Forgot password</h2>

<div class="ui horizontal icon divider">
    <i class="circular heart icon"></i>
</div>

{{ content() }}

<div class="ui fluid form segment">
    <h3 class="ui header">Forgot password?</h3>
    {{ form('action': ['for':'forgot-password']) }}
    <div class="field">
        {{ form.render('email') }}
        {{ form.messages('email') }}
    </div>
    <div class="field">
        {{ form.render('Send') }}
    </div>

    {{ form.render('csrf', ['value': security.getToken()]) }}

    {{ end_form() }}
</div>
