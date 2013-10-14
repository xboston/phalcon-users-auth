<h2>Edit profile</h2>

<div class="ui horizontal icon divider">
    <i class="circular heart icon"></i>
</div>

<?php echo $this->getContent(); ?>

<div class="ui fluid form segment">
    <h3 class="ui header">Profile data</h3>
    <?php echo Phalcon\Tag::form(array('action' => array('for' => 'edit-profile'))); ?>
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
    <div class="field">
        <div class="ui checkbox">
            <?php echo $form->render('banned-hidden'); ?>
            <?php echo $form->render('banned'); ?>
            <?php echo $form->label('banned'); ?>
        </div>
        <?php echo $form->messages('banned'); ?>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <?php echo $form->render('suspended-hidden'); ?>
            <?php echo $form->render('suspended'); ?>
            <?php echo $form->label('suspended'); ?>
        </div>
        <?php echo $form->messages('suspended'); ?>
    </div>

    <div class="field">
        <div class="ui buttons">
            <a class="ui button" href="<?php echo $this->url->get(array('for' => 'index')); ?>">Cancel</a>
            <div class="or"></div>
            <?php echo $form->render('save'); ?>
        </div>
    </div>

    <?php echo $form->render('csrf', array('value' => $this->security->getToken())); ?>

    <?php echo Phalcon\Tag::endForm(); ?>
</div>
