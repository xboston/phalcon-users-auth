<h2>User Auth!</h2>

<div class="ui horizontal icon divider">
    <i class="circular heart icon"></i>
</div>

{{ content() }}

Hello {{ authUser ? authUser.name : 'Guest' }}
