<h2>Edit profile</h2>

<div class="ui horizontal icon divider">
    <i class="circular heart icon"></i>
</div>

{{ content() }}

<div class="ui fluid form segment">
    <h3 class="ui header">Profile data</h3>
    {{ form('action': ['for':'edit-profile']) }}
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
    <div class="field">
        <div class="ui checkbox">
            {{ form.render('banned-hidden') }}
            {{ form.render('banned') }}
            {{ form.label('banned') }}
        </div>
        {{ form.messages('banned') }}
    </div>
    <div class="field">
        <div class="ui checkbox">
            {{ form.render('suspended-hidden') }}
            {{ form.render('suspended') }}
            {{ form.label('suspended') }}
        </div>
        {{ form.messages('suspended') }}
    </div>

    <div class="field">
        <div class="ui buttons">
            <a class="ui button" href="{{ url(['for':'index']) }}">Cancel</a>
            <div class="or"></div>
            {{ form.render('save') }}
        </div>
    </div>

    {{ form.render('csrf', ['value': security.getToken()]) }}

    {{ end_form() }}
</div>
