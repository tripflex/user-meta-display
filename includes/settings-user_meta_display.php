        <div class="wrap">
            <h2><?php echo __( 'User Meta Display', 'user-meta-display'); ?></h2>
            <?php
            	if( !empty( $_GET['settings-updated'] ) && $screen->parent_base != 'options-general' ){
					echo '<div class="updated settings-error" id="setting-error-settings_updated">';
					echo '<p><strong>' . __('Settings saved.', 'user-meta-display') . '</strong></p></div>';
				}
            ?>            
            <p><?php echo __("Browse and display user meta fields","user-meta-display"); ?></p>
            <form method="post" action="options.php" class="user-meta-display-options-form">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'user_meta_display' );
                do_settings_sections( 'user_meta_display' );
