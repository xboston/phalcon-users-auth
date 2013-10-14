<h2>Login</h2>

<div class="ui horizontal icon divider">
    <i class="circular heart icon"></i>
</div>

<?php echo $this->getContent(); ?>

<div class="ui two column middle aligned relaxed grid basic segment">
    <div class="column">
        <?php echo Phalcon\Tag::form(array('action' => array('for' => 'login'), 'method' => 'post')); ?>
        <div class="ui fluid form segment">
            <h3 class="ui header">Log-in</h3>

            <div class="field">
                <?php echo $form->label('email'); ?>
                <?php echo $form->render('email'); ?>
                <?php echo $form->messages('email'); ?>
            </div>

            <div class="field">
                <?php echo $form->label('password'); ?>
                <?php echo $form->render('password'); ?>
                <?php echo $form->messages('password'); ?>
            </div>

            <div class="inline field">
                <div class="ui checkbox">
                    <?php echo $form->render('remember'); ?>
                    <?php echo $form->label('remember'); ?>
                </div>
                <?php echo $form->messages('remember'); ?>
            </div>

            <?php echo $form->render('Login'); ?>
            <?php echo Phalcon\Tag::linkTo(array(array('for' => 'forgot-password'), 'Forgot my password')); ?>

        </div>
        <?php echo $form->render('csrf', array('value' => $this->security->getToken())); ?>
        <?php echo Phalcon\Tag::endForm(); ?>
    </div>
    <div class="ui vertical divider">
        Or
    </div>
    <div class="center aligned column">
        <?php echo Phalcon\Tag::linkTo(array(array('for' => 'register'), '<i class="signup icon"></i> Register', 'class' => 'huge green ui labeled icon button')); ?>
    </div>
</div>
