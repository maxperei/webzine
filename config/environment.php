<?php

/*----------------------------------------------------*/
// Define environment type
/*----------------------------------------------------*/
return function() {
    if (getenv('WP_ENV') === 'local') {
        return 'local';
    }
    return 'production';
};
