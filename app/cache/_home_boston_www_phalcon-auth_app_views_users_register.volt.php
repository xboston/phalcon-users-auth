<h2>Register</h2>

<div class="ui horizontal icon divider">
    <i class="circular heart icon"></i>
</div>

<?php echo $this->getContent(); ?>

<div class="column">

    <div class="ui fluid form segment">
        <h3 class="ui header">Register</h3>
        <?php echo Phalcon\Tag::form(array('action' => array('for' => 'register'), 'method' => 'post', 'autocomplete' => 'off')); ?>
        <div class="field">
            <?php echo $form->label('name'); ?>
            <?php echo $form->render('name'); ?>
            <?php echo $form->messages('name'); ?>
        </div>
        <div class="field">
            <?php echo $form->label('email'); ?>
            <?php echo $form->render('email'); ?>
            <?php echo $form->messages('email'); ?>
        </div>
        <div class="two fields">
            <div class="field">
                <?php echo $form->label('password'); ?>
                <?php echo $form->render('password'); ?>
                <?php echo $form->messages('password'); ?>
            </div>
            <div class="field">
                <?php echo $form->label('confirmPassword'); ?>
                <?php echo $form->render('confirmPassword'); ?>
                <?php echo $form->messages('confirmPassword'); ?>
            </div>
        </div>
        <div class="inline field">
            <div class="ui checkbox">
                <?php echo $form->render('terms'); ?>
                <?php echo $form->label('terms'); ?>
            </div>
            <?php echo $form->messages('terms'); ?>
        </div>
        <?php echo $form->render('Sign Up'); ?>
    </div>
    <?php echo $form->render('csrf', array('value' => $this->security->getToken())); ?>
    <?php echo $form->messages('csrf'); ?>
    <?php echo Phalcon\Tag::endForm(); ?>
</div>
