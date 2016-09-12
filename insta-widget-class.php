<?php

class InstaWidget extends WP_Widget {
  function __construct(){
    parent::__construct(
      'InstaWidget',
      __('Insta Widget'),
      array('description' => __(''))
    );
  }

  public function widget( $args, $instance ) {

    $title = apply_filters( 'widget_title', $instance['title'] );
    $layout = (isset($instance['layout'])) ? intval($instance['layout']) : 6;
    $token = $instance['token'];
    $data = call_user_implicit($token, 'media');

    echo $args['before_widget'];
    if ( ! empty( $title ) )
    echo $args['before_title'] . $title . $args['after_title'];
    ?>
    <div class="insta-shows">
      <?php
      for($i = 0; $i < $layout; $i++){
        $imgUrl = $data[$i]['images']['standard_resolution']['url'];
        $imgLink = $data[$i]['link'];
        $imgTitle = $data[$i]['caption']['text'];
        $qtdWords = nthstrpos($imgTitle, ' ', 7);
        $imgTitle = ($qtdWords > 0) ? substr($imgTitle, 0, $qtdWords) : $imgTitle;

        echo sprintf('<a href="%s" target="_blank" ><figure><img src="%s" alt="%s" /><figcaption>%s</figcaption></figure></a>', $imgLink, $imgUrl, $imgTitle, $imgTitle);
      }
       ?>
    </div>

    <?php
    echo $args['after_widget'];
  }

  // Widget Backend
  public function form( $instance ) {
    $client_id = '6481fc20c37e463785ecdb1772da49d1';
    $client_secure = 'c2c725c801b54dbf937170341cf0f38a';
    $redirect_url = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    $scope = array('basic', 'public_content', 'comments', 'relationships', 'likes', 'follower_list');
    $scopes = implode("+", $scope);
    $loginUrl = "https://api.instagram.com/oauth/authorize?client_id={$client_id}&client_secret={$client_secure}&redirect_uri={$redirect_url}&scope={$scopes}&response_type=token";

    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
      $layout = (isset($instance['layout'])) ? intval($instance['layout']) : 6;
      $token = $instance['token'];
      $user = $instance['user'];
      $insta = call_user_implicit($token);
    }
    else {
      $title = __('InstaWidget');
      $layout = 6;
    }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
      <label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:'); ?></label>
      <select class="widefat" name="<?php echo $this->get_field_name('layout') ?>" id="<?php echo $this->get_field_id('layout')?>">
        <option value="2" <?php selected( $layout, 2 ); ?>>2 <?php _e('images'); ?></option>
        <option value="4" <?php selected( $layout, 4 ); ?>>4 <?php _e('images'); ?></option>
        <option value="6" <?php selected( $layout, 6 ); ?>>6 <?php _e('images'); ?></option>
        <option value="8" <?php selected( $layout, 8 ); ?>>8 <?php _e('images'); ?></option>
      </select>
      <input type="hidden" id="loginUrl" value="<?php echo $loginUrl; ?>" />
      <input type="hidden" id="<?php echo $this->get_field_id( 'token' ); ?>" name="<?php echo $this->get_field_name( 'token' ); ?>" value="<?php echo esc_attr( $token ); ?>"/>
      <input type="hidden" id="<?php echo $this->get_field_id( 'user' ); ?>" name="<?php echo $this->get_field_name( 'user' ); ?>" value="<?php echo esc_attr( $user ); ?>">
    </p>
    <p class="insta-inputs">
      <?php if( ! empty( $instance[ 'user' ] ) ) : ?>
          <label for="insta-username"><b>Username:</b> <?php echo $insta['username'] ?></label>
          <a href="javascript:void(0)" class="insta-remove">Remover</a>
      <?php else: ?>
        <a class="insta-login" href="<?php echo $loginUrl; ?>">Login</a>
      <?php endif; ?>
    </p>
    <?php
  }

  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['layout'] = intval($new_instance['layout']);
    $instance['token'] = ( ! empty( $new_instance['token'] ) ) ? strip_tags( $new_instance['token'] ) : '';
    $instance['user'] = ( ! empty( $new_instance['user'] ) ) ? strip_tags( $new_instance['user'] ) : '';
    return $instance;
  }
}
