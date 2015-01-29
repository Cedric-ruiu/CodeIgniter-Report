<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * A librairie for CodeIgniter with manage error/report message, using
 * session or flashdata, stock into file logs
 *
 * @link http://github.com/Cedric-ruiu/CodeIgniter-Report
 * @copyright Copyright (c) 2015, Cédric Ruiu <http://opla-studio.com>
 * @version v1.2
 */

class Report
{

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */

    /**
     * Enable display time on report view.
     */
    protected $time       = FALSE;

    /**
     * The current request's view.
     */
    protected $template_default = 'twitter-bootstrap-3';
    
    /**
     * The temporary storage method of reports. Option available : session | flashdata | stack_array
     */
    protected $save_type  = 'session';

    /**
     * Reports are automatically clean after load for read. Functional only with session mode.
     */
    protected $auto_clean = TRUE;

    /**
     * Save report in CI log file.
     */
    protected $log        = FALSE;

    /**
     * Reports librarie use 4 types of message provide from twitter bootstrap model: error, success, warning and notice.
     * Check in config/config.php $config['log_threshold'] to see all message or not
     */
    public $log_auths = array(
        0 => array( //error code
            'writing' => TRUE,
            'code'    => 'error'
        ),
        1 => array( //success code
            'writing' => FALSE,
            'code'    => 'info'
        ),
        2 => array( //warning code
            'writing' => TRUE,
            'code'    => 'error'
        ),
        3 => array( //info code
            'writing' => FALSE,
            'code'    => 'info'
        )
    );


    /* --------------------------------------------------------------
     * VARIABLES NOT EDITABLE
     * ------------------------------------------------------------ */

    /**
     * Instance of CodeIgniter
     */
    protected $CI;

    protected $load_method  = '';
    protected $save_method  = '';
    protected $clean_method = '';

    protected $template;

    /**
     * Var Use for "array" temporary storage
     */
    protected $stack_report = array();
    
    /**
     * Initialise the librarie, get CodeIgniter instance, define method by temporary storage type
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->refresh_operations_methods();
        $this->set_template($this->template_default);
    }

    protected function check_template($template_to_check)
    {
        $template_path_file = APPPATH.'libraries/report/templates/'.$template_to_check.'.php';
        if(file_exists($template_path_file))
        {
            include_once($template_to_check);
            return TRUE;
        }
        else exit('Impossible to load Report template '.$template_to_check);
    }

    protected function refresh_operations_methods()
    {
        $this->load_method  = 'load_'.$this->save_type;
        $this->save_method  = 'save_'.$this->save_type;
        $this->clean_method = 'clean_'.$this->save_type;
    }

    
    /**
     * Load & clean
     */

    protected function load($clean = TRUE)
    {
        $reports = $this->{$this->load_method}($clean);
        if(!$reports) $reports = array();
        return $reports;
    }

    protected function load_session($clean)
    {
        $reports = $this->CI->session->userdata('reports');
        if($clean) $this->CI->session->unset_userdata('reports');
        return $reports;
        
    }

    protected function load_flashdata($clean)
    {
        return $this->CI->session->flashdata('reports');
    }

    protected function load_stack_array($clean)
    {
        $reports = $this->stack_report;
        if($clean) $this->clean_stack_array();
        return $reports;
    }

    
    /**
     * Save
     */

    protected function save($reports)
    {
        $this->{$this->save_method}($reports);
    }

    protected function save_session($reports)
    {
        $this->CI->session->set_userdata('reports', $reports);
    }

    protected function save_flashdata($reports)
    {
        $this->CI->session->set_flashdata('reports', $reports);
    }

    protected function save_stack_array($reports)
    {
        $this->stack_report = $reports;
    }


    /**
     * Clean
     */

    public function clean()
    {
        $this->{$this->clean_method}();
    }

    protected function clean_session()
    {
        $this->CI->session->unset_userdata('reports');
    }

    protected function clean_flashdata(){}

    protected function clean_stack_array()
    {
        $this->stack_report = array();
    }


    /**
     * Other private function
     */

    protected function make_report($code, $description)
    {
        return array($code, time(), $description);
    }

    protected function log($report)
    {
        if($this->log_auths[$report[0]]['writing'])
        {
            log_message($this->log_auths[$report[0]]['code'], $report[2]);
        }
    }

    protected function get_default_message($code)
    {
        return lang('report_'.$code);
    }

    protected function get_code($u_code, &$description)
    {
        if(is_string($u_code) && $description===NULL)
        {
            if(2 <= strlen($u_code))
            {
                $description = $u_code;
                return 0;
            }
        }
        return (int)$u_code;
    }


    /**
     * Public function
     */
    
    public function set_template($my_template)
    {
        if($this->check_template($my_template))
        {
            $t_name = 'Report_'.str_replace('-', '_', $my_template);
            $this->template = new $t_name;
            return $this;
        }
    }

    public function save_type($tmp_save_type)
    {
        $this->save_type = $tmp_save_type;
        $this->refresh_operations_methods();
        return $this;
    }

    public function enable_time()
    {
        $this->time = TRUE;
        return $this;
    }

    public function disable_time()
    {
        $this->time = FALSE;
        return $this;
    }

    public function enable_log()
    {
        $this->log = TRUE;
        return $this;
    }

    public function disable_log()
    {
        $this->log = FALSE;
        return $this;
    }

    public function __set($name, $data)
    {
        if($name === 'set')
        {
            if(is_array($data)) $this->set($data[0], $data[1]);
            else $this->set($data);
        } 
    }

    public function set($u_code, $description=NULL)
    {
        $code      = $this->get_code($u_code, $description);
        $reports   = $this->load(FALSE);
        $report    = $this->make_report($code, $description);
        $reports[] = $report;
        $this->save($reports);
        if($this->log) $this->log($report);
        return $this;
    }

    public function get_all()
    {
        $reports = $this->load($this->auto_clean);

        if(0 < count($reports))
        {

            $report_view  = '';
            $report_datas = array();

            foreach($reports as $key => $report)
            {
                $time    = ($this->time)? date($this->CI->config->item('log_date_format'), $report[1]): NULL;
                $code    = $this->template->types[$report[0]];
                $message = (isset($report[2])) ? $report[2] : $this->get_default_message($code);

                $report_view .= $this->template->get_template($code, $message, $time);
            }

            return $report_view;
        }
        return '';
    }
}


/* End of file Report.php */
/* Location: ./application/libraries/report/Report.php */