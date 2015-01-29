<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report_twitter_bootstrap_3
{
    /**
     * Default types from twitter bootstrap
     * The four types of reports. You can edit that but respect the order, edit variable $log_types, and key of lang file used.
     * error code  : 0, '0', FALSE
     * success code: 1, '1', TRUE
     * warning code: 2, '2'
     * notice code : 3, '3'
     */
    public $types = array('danger', 'success', 'warning', 'info');

    public function get_template($code, $message, $time)
    {
        ?>
        <div class="alert alert-<?php echo $code; ?> fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php if(isset($time)){ ?>
            <strong><?php echo $time.' : '; ?></strong><?php
            } 
            echo $message; ?>
        </div>
        <?php
    }
}

