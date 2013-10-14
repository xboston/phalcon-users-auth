<h2>User Auth!</h2>

<div class="ui horizontal icon divider">
    <i class="circular heart icon"></i>
</div>

<?php echo $this->getContent(); ?>

Hello <?php echo ($authUser ? $authUser->name : 'Guest'); ?>
