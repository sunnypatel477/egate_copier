<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Load the Google API PHP Client Library.
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Define a path to the credentials file.
define('CREDENTIALS_PATH', dirname(__DIR__) . '/config/credentials.json');
define('TOKEN_PATH', dirname(__DIR__) . '/config/token.json');


class Google_sheet extends AdminController
{
    private $client;
    private $service;

    public function __construct()
    {
        parent::__construct();

        if (staff_cant('setting', 'google_sheet') && staff_cant('view', 'google_sheet') && staff_cant('create', 'google_sheet') && staff_cant('edit', 'google_sheet') && staff_cant('delete', 'google_sheet')) {
            access_denied('google_sheet');
        }

        $this->load->model('google_sheet_model');
    }

    public function _create_client($type = '') {
        $this->client = new Google_Client();
        $this->client->setApplicationName('Google Sheets - ' . get_option('companyname'));
        if ($type == 'readonly') {
            $this->client->setScopes([
                Google_Service_Sheets::SPREADSHEETS_READONLY,
                Google_Service_Drive::DRIVE_METADATA_READONLY,
                'email',
                'profile',
            ]);
        } else {
            $this->client->setScopes([
                Google_Service_Sheets::SPREADSHEETS,
                Google_Service_Drive::DRIVE,
                'email',
                'profile',
            ]);
        }
        $this->client->setAccessType('offline'); 
        $this->client->setAuthConfig(CREDENTIALS_PATH);
    }

    public function _set_service() {
        session_start();

        if (file_exists(TOKEN_PATH)) {
            $accessToken = json_decode(file_get_contents(TOKEN_PATH), true);
            $_SESSION['access_token'] = $accessToken;
            $this->client->setAccessToken($accessToken);
        }

        if (!isset($_SESSION['access_token'])) {
            $auth_url = $this->client->createAuthUrl();
            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
            exit;
        }

        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                $_SESSION['access_token'] = $this->client->getAccessToken();
            } else {
                redirect($this->client->createAuthUrl());
                die;
            }
        }

        // Get a new instance of the Google Sheets service
        $this->service = new Google_Service_Sheets($this->client);
    }

    public function index()
    {
        if (staff_cant('setting', 'google_sheet') && staff_cant('view', 'google_sheet') && staff_cant('create', 'google_sheet') && staff_cant('edit', 'google_sheet') && staff_cant('delete', 'google_sheet')) {
            access_denied('google_sheet');
        }
		
		// modules\google_sheet\core\Apiinit::the_da_vinci_code(GOOGLE_SHEET_MODULE);
		// modules\google_sheet\core\Apiinit::ease_of_mind(GOOGLE_SHEET_MODULE);

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('google_sheet', 'tables/index'));
        }

        $data['title'] = _l('google_sheet');

        $this->load->view('index', $data);
    }

    public function save() {
        $this->_create_client();
        
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $description = $this->input->post('description');

        if ($title) {
            $this->_set_service();

            if ($id) {
                $google_sheet = $this->google_sheet_model->get($id);

                if ($google_sheet) {
                    // Update the spreadsheet properties
                    $requests = [
                        new Google_Service_Sheets_Request([
                            'updateSpreadsheetProperties' => [
                                'properties' => [
                                    'title' => $title,
                                ],
                                'fields' => 'title',
                            ],
                        ]),
                        new Google_Service_Sheets_Request([
                            'createDeveloperMetadata' => [
                                'developerMetadata' => [
                                    'metadataKey' => 'description',
                                    'metadataValue' => $description,
                                    'location' => [
                                        'spreadsheet' => true,
                                    ],
                                    'visibility' => 'DOCUMENT',
                                ],
                            ],
                        ]),
                    ];

                    $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                        'requests' => $requests,
                    ]);

                    $response = $this->service->spreadsheets->batchUpdate($google_sheet->sheetid, $batchUpdateRequest);

                    $this->google_sheet_model->update([
                        'title' => $title,
                        'description' => $description
                    ], $google_sheet->id);
                    
                    set_alert('success', _l('google_sheet_saved_successfully', _l('google_sheet')));
                }
            } else {
                // Create a new spreadsheet
                $spreadsheet = new Google_Service_Sheets_Spreadsheet([
                    'properties' => [
                        'title' => $title
                    ]
                ]);
        
                // Execute the request
                $spreadsheet = $this->service->spreadsheets->create($spreadsheet, [
                    'fields' => 'spreadsheetId'
                ]);
                // Get the spreadsheet ID
                $spreadsheetId = $spreadsheet->spreadsheetId;

                if ($description) {
                    // Add a new sheet to the spreadsheet
                    $requests = [
                        new Google_Service_Sheets_Request([
                            'createDeveloperMetadata' => [
                                'developerMetadata' => [
                                    'metadataKey' => 'description',
                                    'metadataValue' => $description,
                                    'location' => [
                                        'spreadsheet' => true
                                    ],
                                    'visibility' => 'DOCUMENT'
                                ]
                            ]
                        ])
                    ];
                    $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                        'requests' => $requests
                    ]);

                    $this->service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                }

                $this->google_sheet_model->add([
                    'staffid' => get_staff_user_id(),
                    'sheetid' => $spreadsheetId,
                    'title' => $title,
                    'description' => $description
                ]);
                
                set_alert('success', _l('google_sheet_created_successfully', _l('google_sheet')));
            }
        }

        redirect(admin_url('google_sheet'));
    }

    public function view($id) {
        if (staff_cant('view', 'google_sheet')) {
            access_denied('google_sheet');
        }

        $this->_create_client();
        $this->_set_service();
        
        $google_sheet = $this->google_sheet_model->get($id);

        if ($google_sheet) {
            $data['id']                     = $id;
            $data['title']                  = _l('google_sheet') . ' - ' . $google_sheet->title;
            $data['sheetid']                = $google_sheet->sheetid;
            $this->load->view('view', $data);
        } else {
            redirect(admin_url('google_sheet'));
        }
    }

    public function delete($id) {
        if (staff_cant('delete', 'google_sheet')) {
            access_denied('google_sheet');
        }

        $this->_create_client();
        $this->_set_service();

        $google_sheet = $this->google_sheet_model->get($id);
        $drive = new Google_Service_Drive($this->client);
        try {
            $drive->files->delete($google_sheet->sheetid);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        $this->google_sheet_model->delete($id);

        set_alert('success', _l('google_sheet_deleted_successfully', _l('google_sheet')));

        redirect(admin_url('google_sheet'));
    }

    public function update_sheet($sheetId, $range, $values) {
        if (staff_cant('view', 'google_sheet') || staff_cant('create', 'google_sheet') || staff_cant('edit', 'google_sheet')) {
            access_denied('google_sheet');
        }

        $this->_create_client();
        
        try {
            $body = new Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);
            $params = [
                'valueInputOption' => 'RAW'
            ];
            $result = $this->service->spreadsheets_values->update($sheetId, $range, $body, $params);
            return $result->getUpdatedCells();
        } catch (Exception $e) {
            echo 'Error writing to sheet: ', $e->getMessage(), "\n";
            return null;
        }
        
        set_alert('success', _l('google_sheet_updated_successfully', _l('google_sheet')));

        redirect(admin_url('google_sheet'));
    }

    public function integrate() {
        if (staff_cant('setting', 'google_sheet')) {
            access_denied('google_sheet');
        }

        foreach(['client_id', 'client_secret'] as $option) {
            $$option = $this->input->post($option);
            $$option = trim($$option);
            $$option = nl2br($$option);
        }
        
        if (file_exists(CREDENTIALS_PATH)) {
            $credentials = json_decode(file_get_contents(CREDENTIALS_PATH), true);
        } else {
            $credentials = [
                'web' => [
                    "client_id" => "",
                    "project_id" => "zapier-themesic",
                    "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                    "token_uri" => "https://oauth2.googleapis.com/token",
                    "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                    "client_secret" => "",
                    "redirect_uris" => ["https://zapier.themesic.com/admin/google_sheet/redirects"]
                ]
            ];
        }
        $credentials['web']['client_id'] = $client_id;
        $credentials['web']['client_secret'] = $client_secret;
        file_put_contents(CREDENTIALS_PATH, json_encode($credentials));

        update_option('google_sheet_client_id', $client_id);
        update_option('google_sheet_client_secret', $client_secret);
        
        $this->_create_client();
        $authUrl = $this->client->createAuthUrl();
        
        echo json_encode([
            'status' => 'success',
            'auth_url' => $authUrl,
        ]);
    }

    public function redirects() {
        $code = $this->input->get('code');
        
        if ($code) {
            $this->_create_client();
    
            $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);
            $this->client->setAccessToken($accessToken);
    
            // Save the token to a file.
            if (!file_exists(dirname(TOKEN_PATH))) {
                mkdir(dirname(TOKEN_PATH), 0700, true);
            }
            file_put_contents(TOKEN_PATH, json_encode($this->client->getAccessToken()));
            
            set_alert('success', _l('google_sheet_integration_successfully', _l('google_sheet')));
        }
        
        redirect(admin_url('google_sheet/settings?tab=tab_integration'));
    }

    public function settings()
    {
        if (staff_cant('setting', 'google_sheet')) {
            access_denied('google_sheet');
        }

        $data['title'] = _l('google_sheet_settings');
        $this->load->view('settings', $data);
    }

    public function fetch()
    {
        $this->_create_client('readonly');
        $this->_set_service();

        $drive = new Google_Service_Drive($this->client);

        $files = [];

        try {
            $response = $drive->files->listFiles([
                'q' => "mimeType='application/vnd.google-apps.spreadsheet' and trashed=false",
                'fields' => 'files(id, name)',
            ]);

            foreach ($response->getFiles() as $file) {
                $files[] = $file;
            }
        } catch (Exception $e) {
            set_alert('success', _l('google_sheet_integrate_again', _l('google_sheet')));
            redirect(admin_url('google_sheet/settings'));
        }
        
        $google_sheet_ids = [];
        foreach ($files as $file) {
            $google_sheet_ids[] = $file->getId();
            $google_sheet = $this->google_sheet_model->get_by_sheetid($file->getId());
            if ($google_sheet) {
                $this->google_sheet_model->update([
                    'title' => $file->getName()
                ], $google_sheet->id);
            } else {
                $this->google_sheet_model->add([
                    'staffid' => get_staff_user_id(),
                    'sheetid' => $file->getId(),
                    'title' => $file->getName(),
                    'description' => ''
                ]);
            }
        }

        $google_sheets = $this->google_sheet_model->get();
        foreach ($google_sheets as $google_sheet) {
            if (!in_array($google_sheet['sheetid'], $google_sheet_ids)) {
                $this->google_sheet_model->delete($google_sheet['id']);
            }
        }
        
        set_alert('success', _l('google_sheet_fetched_successfully', _l('google_sheet')));

        redirect(admin_url('google_sheet'));
    }

    public function reset_settings()
    {
        if (staff_cant('setting', 'google_sheet')) {
            access_denied('google_sheet');
        }

        // update_option('google_sheet_can_access', 'no');
        // update_option('google_sheet_can_manage', 'no');
        update_option('google_sheet_client_id', '');
        update_option('google_sheet_client_secret', '');

        unlink(CREDENTIALS_PATH);
        unlink(TOKEN_PATH);
        
        set_alert('success', _l('google_sheet_reseted_successfully', _l('google_sheet')));

        redirect(admin_url('google_sheet/settings'));
    }

    public function save_settings()
    {
        if (staff_cant('setting', 'google_sheet')) {
            access_denied('google_sheet');
        }

        hooks()->do_action('before_save_google_sheet');

        foreach(['can_access', 'can_manage'] as $option) {
            // Also created the variables
            $$option = $this->input->post($option);
            $$option = trim($$option);
            $$option = nl2br($$option);
        }

        // update_option('google_sheet_can_access', $can_access);
        // update_option('google_sheet_can_manage', $can_manage);
    }
}
