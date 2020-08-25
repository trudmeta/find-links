<?php

if(!empty($_POST)){
    $nofollow = isset($_POST['nofollow'])? (bool)$_POST['nofollow'] : false;
    $target = isset($_POST['target'])? (bool)$_POST['target'] : false;
    $postTypes = isset($_POST['postTypes'])? (array)$_POST['postTypes'] : [];
    update_option('wplink_nofollow', $nofollow);
    update_option('wplink_target', $target);
    update_option('wplink_postTypes', $postTypes);
}

$nofollow = (bool)get_option('wplink_nofollow', false);
$target = (bool)get_option('wplink_target', false);
$postTypes = (array)get_option('wplink_postTypes', []);

$post_types = get_post_types([
    'public'   => true,
]);

?>

<div class="container">
    <div class="row">
        <div class="col-12 text-left">
            <h2><?php echo get_admin_page_title() ?></h2>
        </div>
        <div class="col-12 mt-4">
            <form method="post" action="">
                <div class="form-group">
                    <label for="noFollow" title="<?php echo __('Add attribute nofollow', 'wplinks') ?>">noFollow</label>
                    <input type="checkbox" name="nofollow" <?php if($nofollow) echo 'checked'; ?> class="form-control" id="noFollow">
                </div>
                <div class="form-group">
                    <label for="targetBlank" title="<?php echo __('Add attribute target', 'wplinks') ?>">target blank</label>
                    <input type="checkbox" name="target" <?php if($target) echo 'checked'; ?> class="form-control" id="targetBlank">
                </div>
                <div class="form-group">
                    <label>target blank</label>
                    <?php
                    $n = 0;
                    foreach($post_types as $type){ ?>
                        <div class="col-12">
                            <label for="postType_<?php echo $type;?>" title="<?php echo __('For post type:', 'wplinks'). ' ' . $type; ?>"><?php echo $type;?></label>
                            <input type="checkbox" name="postTypes[]" <?php echo (!empty($postTypes) && in_array($type, $postTypes))? 'checked="checked"' : ''; ?> id="postType_<?php echo $type;?>" value="<?php echo $type; ?>">
                        </div>
                    <?php $n++; }
                    ?>
                </div>

                <button type="submit" class="btn btn-primary"><?php echo __('Submit', 'wplinks') ?></button>
            </form>
        </div>
    </div>
</div>
