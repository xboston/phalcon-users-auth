<h2>Forgot password</h2>

<div class="ui horizontal icon divider">
    <i class="circular heart icon"></i>
</div>

<?php echo $this->getContent(); ?>

<div class="ui fluid form segment">
    <h3 class="ui header">Forgot password?</h3>
    <?php echo Phalcon\Tag::form(array('action' => array('for' => 'forgot-password'))); ?>
    <div class="field">
        <?php echo $form->render('email'); ?>
        <?php echo $form->messages('email'); ?>
    </div>
    <div class="field">
        <?php echo $form->render('Send'); ?>
    </div>

    <?php echo $form->render('csrf', array('value' => $this->security->getToken())); ?>

    <?php echo Phalcon\Tag::endForm(); ?>
</div>
