<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-social_icons_vt" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-social_icons_vt" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-facebook"><?php echo $text_facebook_vt; ?></label>
            <div class="col-sm-10">
                <input type="text" name="facebook_vt" value="<?=$facebook_vt ?>" placeholder="<?=$facebook_vt ?>" id="input-facebook" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-google"><?php echo $text_viber; ?></label>
            <div class="col-sm-10">
                <input type="text" name="viber" value="<?=$viber ?>" placeholder="<?=$viber ?>" id="input-google" class="form-control" />
            </div>
          </div>  
           <div class="form-group">
            <label class="col-sm-2 control-label" for="input-google"><?php echo $text_whatsapp; ?></label>
            <div class="col-sm-10">
                <input type="text" name="whatsapp" value="<?=$whatsapp ?>" placeholder="<?=$whatsapp ?>" id="input-google" class="form-control" />
            </div>
          </div>  
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-youtube"><?php echo $text_youtube_vt; ?></label>
            <div class="col-sm-10">
                <input type="text" name="youtube_vt" value="<?=$youtube_vt ?>" placeholder="<?=$youtube_vt ?>" id="input-google" class="form-control" />
            </div>
          </div>        
         <div class="form-group">
            <label class="col-sm-2 control-label" for="input-instagram"><?php echo $text_instagram_vt; ?></label>
            <div class="col-sm-10">
                <input type="text" name="instagram_vt" value="<?=$instagram_vt ?>" placeholder="<?=$instagram_vt ?>" id="input-google" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-twitter"><?php echo $text_twitter_vt; ?></label>
            <div class="col-sm-10">
                <input type="text" name="twitter_vt" value="<?=$twitter_vt ?>" placeholder="<?=$twitter_vt ?>" id="input-google" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-vk"><?php echo $text_vk_vt; ?></label>
            <div class="col-sm-10">
                <input type="text" name="vk_vt" value="<?=$vk_vt ?>" placeholder="<?=$vk_vt ?>" id="input-google" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-6">
              <b><?php echo $text_position; ?>(px)</b>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="right"><?php echo $text_right; ?></label>
                <div class="col-sm-10">
                    <input type="number" name="right" value="<?=$right ?>" placeholder="<?=$right ?>" id="right" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="bottom"><?php echo $text_bottom; ?></label>
                <div class="col-sm-10">
                    <input type="number" name="bottom" value="<?=$bottom ?>" placeholder="<?=$bottom ?>" id="bottom" class="form-control" />
                </div>
              </div>
          </div>
          <div class="col-sm-6">
              <b><?php echo $text_color_select; ?>(HEX)</b>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="bg_color"><?php echo $text_bg_color; ?></label>
                <div class="col-sm-10">
                    <input type="color" name="bg_color" value="<?=$bg_color ?>" placeholder="<?=$bg_color ?>" id="bg_color" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="icon_color"><?php echo $text_icon_color; ?></label>
                <div class="col-sm-10">
                    <input type="color" name="icon_color" value="<?=$icon_color ?>" placeholder="<?=$icon_color ?>" id="icon_color" class="form-control" />
                </div>
              </div>
          </div>
       </div>
        </form>
      </div>
    </div>
      <p><small>&copy; <a style="text-align: center" href="https://kwork.ru/user/web-helper" target="_blank">Web-helper</a></small></p>
  </div>
</div>
<?php echo $footer; ?>