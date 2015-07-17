# wordpress-featured-image-position-selector-plugin
adds a dropdown selector to the featured image position with 'top', 'center' and 'bottom' options to allow users to pick the css background-position for their featured images when used as a background-image.

example usage:   
    <div style="background-image: url(<?php the_featured_image_url(); ?>); background-position: center <?php the_featured_image_position(); ?>;">
        <?php the_title(); ?>
    </div>

based on: 
Featured_Image_Metabox_Customizer https://github.com/bradvin/Featured-Image-Metabox-Customizer-Class and 
voodoo_dropdown http://wordpress.stackexchange.com/a/77073/43501
