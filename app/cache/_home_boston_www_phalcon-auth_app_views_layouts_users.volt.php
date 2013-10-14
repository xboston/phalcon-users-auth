<div class="ui pointing icon-- menu">
    <a class="active item" href="<?php echo $this->url->get(array('for' => 'index')); ?>">
        <i class="home icon"></i> Home
    </a>
    <a class="item" href="<?php echo $this->url->get(array('for' => 'register')); ?>">
        <i class="add sign icon"></i> Register
    </a>

    <a class="item" href="<?php echo $this->url->get(array('for' => 'forgot-password')); ?>">
        <i class="question icon"></i> Forgot password
    </a>

    <div class="right menu">
        <?php if ($authUser) { ?>
            <div class="ui dropdown item">
                Login as <strong><?php echo $this->auth->getUser()->name; ?></strong><i class="dropdown icon"></i>

                <div class="menu">
                    <?php echo Phalcon\Tag::linkTo(array(array('for' => 'edit-profile'), '<i class="edit icon"></i> Profile Edit', 'class' => 'item')); ?>
                    <?php echo Phalcon\Tag::linkTo(array(array('for' => 'change-password'), '<i class="edit sign icon"></i> Change password', 'class' => 'item')); ?>
                    <?php echo Phalcon\Tag::linkTo(array(array('for' => 'logout'), '<i class="off icon"></i> Logout', 'class' => 'item')); ?>
                </div>
            </div>
        <?php } else { ?>
            <a class="item" href="<?php echo $this->url->get(array('for' => 'login')); ?>">
                <i class="user icon"></i> Login
            </a>
        <?php } ?>
    </div>

</div>

<?php echo $this->getContent(); ?>
