<?php defined('BASEPATH') or exit('No direct script access allowed');

include_once(LIBSPATH . 'pdf/App_pdf.php');

class Discipline_report extends App_pdf
{
    protected $discipline;

    public function __construct($discipline)
    {
        $GLOBALS['expensive_pdf'] = $discipline;

        parent::__construct();

        $this->discipline = $discipline;
    }

    public function prepare()
    {
        $this->set_view_vars([
            'discipline' => $this->discipline,
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'expensive_document';
    }

    protected function file_path()
    {
        $actualPath = module_views_path(HR_PROFILE_MODULE_NAME) . 'discipline_report.php';
        
        return $actualPath;
    }
}
