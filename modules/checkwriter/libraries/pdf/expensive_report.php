<?php defined('BASEPATH') or exit('No direct script access allowed');

include_once(LIBSPATH . 'pdf/App_pdf.php');

class Expensive_report extends App_pdf
{
    protected $expensive;

    public function __construct($expensive)
    {
        $GLOBALS['expensive_pdf'] = $expensive;

        parent::__construct();

        $this->expensive = $expensive;
    }

    public function prepare()
    {
        $this->set_view_vars([
            'expensive' => $this->expensive,
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'expensive_document';
    }

    protected function file_path()
    {
        $actualPath = module_views_path(CHECKWRITER_MODULE) . 'expensive_report.php';
        
        return $actualPath;
    }
}
