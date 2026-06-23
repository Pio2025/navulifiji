<?php
namespace App\Controllers;

class MedicalController extends BaseController
{
    private function ensureMedicalUploadDir(): string
    {
        $dir = FCPATH . 'uploads/medical/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    // ================================================================
    // INDEX — View all medical records for a user
    // ================================================================

    public function index(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $this->setPageData('Medical Records', 'Medical', 'User Medical');

        $records = $this->userMedicalModel->getByUser($userId);

        // Attach files to each record
        foreach ($records as &$record) {
            $record['files'] = $this->userMedicalFilesModel->getByMedical($record['medical_id']);
        }

        $data['_view']    = 'app/medical/index';
        $data['user']     = $user;
        $data['userID']   = $userId;
        $data['records']  = $records;
        $data['canEdit']  = $this->require_access('_edit_user') === true;

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ADD — Show add form
    // ================================================================

    public function add(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        if (!$this->require_access('_edit_user')) {
            return redirect()->back()->with('error', 'Access denied.');
        }

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $this->setPageData('Add Medical Record', 'Medical', 'Add Medical');

        $data['_view']  = 'app/medical/form';
        $data['user']   = $user;
        $data['userID'] = $userId;
        $data['record'] = null;
        $data['files']  = [];
        $data['isEdit'] = false;

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STORE — Save new medical record
    // ================================================================

    public function store(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $medicalId = $this->userMedicalModel->insert([
                'user_id_fk'                 => $userId,
                'blood_type'                 => $this->request->getPost('blood_type')                 ?? '',
                'medical_condition'          => $this->request->getPost('medical_condition')          ?? '',
                'allergies'                  => $this->request->getPost('allergies')                  ?? '',
                'medications'                => $this->request->getPost('medications')                ?? '',
                'emergency_contact_name'     => $this->request->getPost('emergency_contact_name')     ?? '',
                'emergency_contact_phone'    => $this->request->getPost('emergency_contact_phone')    ?? '',
                'emergency_contact_relation' => $this->request->getPost('emergency_contact_relation') ?? '',
                'doctor_name'                => $this->request->getPost('doctor_name')                ?? '',
                'doctor_phone'               => $this->request->getPost('doctor_phone')               ?? '',
                'doctor_address'             => $this->request->getPost('doctor_address')             ?? '',
                'notes'                      => $this->request->getPost('notes')                      ?? '',
                'medical_date'               => date('Y-m-d'),
                'medical_time'               => time(),
                'medical_status'             => 'Active',
            ]);

            // Handle file uploads
            $this->handleFileUploads($medicalId);

            // Log
            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Medical Record Added',
                'log_desc'    => 'Medical record added for user ID ' . $userId,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-heart-circle"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'info',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Medical record saved successfully.',
                'redirect' => base_url('user/medical/' . $userId),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[MedicalController::store] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // EDIT — Show edit form
    // ================================================================

    public function edit(int $medicalId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        if (!$this->require_access('_edit_user')) {
            return redirect()->back()->with('error', 'Access denied.');
        }

        $record = $this->userMedicalModel->find($medicalId);
        if (!$record) return redirect()->back()->with('error', 'Medical record not found.');

        $user   = $this->userModel->findUserFull($record['user_id_fk']);
        $files  = $this->userMedicalFilesModel->getByMedical($medicalId);
        $userId = $record['user_id_fk'];

        $this->setPageData('Edit Medical Record', 'Medical', 'Edit Medical');

        $data['_view']  = 'app/medical/form';
        $data['user']   = $user;
        $data['userID'] = $userId;
        $data['record'] = $record;
        $data['files']  = $files;
        $data['isEdit'] = true;

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // UPDATE — Save edited medical record
    // ================================================================

    public function update(int $medicalId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $record = $this->userMedicalModel->find($medicalId);
            if (!$record) {
                return $this->response->setJSON(['success' => false, 'message' => 'Record not found.']);
            }

            $this->userMedicalModel->update($medicalId, [
                'blood_type'                 => $this->request->getPost('blood_type')                 ?? '',
                'medical_condition'          => $this->request->getPost('medical_condition')          ?? '',
                'allergies'                  => $this->request->getPost('allergies')                  ?? '',
                'medications'                => $this->request->getPost('medications')                ?? '',
                'emergency_contact_name'     => $this->request->getPost('emergency_contact_name')     ?? '',
                'emergency_contact_phone'    => $this->request->getPost('emergency_contact_phone')    ?? '',
                'emergency_contact_relation' => $this->request->getPost('emergency_contact_relation') ?? '',
                'doctor_name'                => $this->request->getPost('doctor_name')                ?? '',
                'doctor_phone'               => $this->request->getPost('doctor_phone')               ?? '',
                'doctor_address'             => $this->request->getPost('doctor_address')             ?? '',
                'notes'                      => $this->request->getPost('notes')                      ?? '',
            ]);

            // Handle new file uploads
            $this->handleFileUploads($medicalId);

            // Log
            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Medical Record Updated',
                'log_desc'    => 'Medical record ID ' . $medicalId . ' updated for user ID ' . $record['user_id_fk'],
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-heart-circle"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'warning',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Medical record updated successfully.',
                'redirect' => base_url('user/medical/' . $record['user_id_fk']),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[MedicalController::update] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // DELETE — Delete medical record + files
    // ================================================================

    public function delete(int $medicalId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        if (!$this->require_access('_edit_user')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $record = $this->userMedicalModel->find($medicalId);
            if (!$record) {
                return $this->response->setJSON(['success' => false, 'message' => 'Record not found.']);
            }

            // Delete all associated files from disk
            $files = $this->userMedicalFilesModel->getByMedical($medicalId);
            foreach ($files as $file) {
                $path = FCPATH . 'uploads/medical/' . $file['file_name'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // DB cascade will delete files records
            $this->userMedicalModel->delete($medicalId);

            // Log
            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Medical Record Deleted',
                'log_desc'    => 'Medical record ID ' . $medicalId . ' deleted for user ID ' . $record['user_id_fk'],
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-trash"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'danger',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Medical record deleted successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[MedicalController::delete] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // DELETE FILE — Delete single attached file
    // ================================================================

    public function deleteFile(int $fileId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        try {
            $file = $this->userMedicalFilesModel->find($fileId);
            if (!$file) {
                return $this->response->setJSON(['success' => false, 'message' => 'File not found.']);
            }
    
            // Get user_id via medical record for logging
            $medical = $this->userMedicalModel->find($file['medical_id_fk']);
    
            $path = FCPATH . 'uploads/medical/' . $file['file_name'];
            if (file_exists($path)) {
                unlink($path);
            }
    
            $this->userMedicalFilesModel->delete($fileId);
    
            // Log
            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Medical File Deleted',
                'log_desc'    => 'Deleted medical file: ' . $file['file_original_name'] .
                                 ' from medical record ID ' . $file['medical_id_fk'] .
                                 ' (user ID ' . ($medical['user_id_fk'] ?? '?') . ')',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-trash"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'danger',
            ]);
    
            return $this->response->setJSON([
                'success' => true,
                'message' => 'File deleted successfully.',
            ]);
    
        } catch (\Exception $e) {
            log_message('error', '[MedicalController::deleteFile] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // VIEW FILE — Stream file to browser
    // ================================================================

    public function viewFile(int $fileId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
    
        $file = $this->userMedicalFilesModel->find($fileId);
        if (!$file) {
            return redirect()->back()->with('error', 'File not found.');
        }
    
        $path = FCPATH . 'uploads/medical/' . $file['file_name'];
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File does not exist on server.');
        }
    
        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . $file['file_original_name'] . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    // ================================================================
    // PRIVATE — Handle multiple file uploads
    // ================================================================

    private function handleFileUploads(int $medicalId): void
    {
        $dir = $this->ensureMedicalUploadDir();
    
        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
    
        // ── CodeIgniter multiple file handling ────────────────────────
        // getFiles() returns array keyed by input name
        // For input name="medical_files[]" each file is individually accessible
        $uploadedFiles = $this->request->getFiles();
    
        if (empty($uploadedFiles) || empty($uploadedFiles['medical_files'])) {
            return;
        }
    
        $files = $uploadedFiles['medical_files'];
    
        // Normalize — single file comes as object, multiple as array
        if (!is_array($files)) {
            $files = [$files];
        }
    
        foreach ($files as $file) {
            // Skip empty/invalid files
            if (!$file instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
                continue;
            }
    
            if (!$file->isValid()) {
                log_message('warning', '[MedicalController::handleFileUploads] Invalid file: ' . $file->getErrorString());
                continue;
            }
    
            if ($file->hasMoved()) {
                continue;
            }
    
            // Check file size
            if ($file->getSize() > 10 * 1024 * 1024) {
                log_message('warning', '[MedicalController::handleFileUploads] File too large: ' . $file->getClientName());
                continue;
            }
    
            // Check mime type
            $mime = $file->getMimeType();
            if (!in_array($mime, $allowedTypes)) {
                log_message('warning', '[MedicalController::handleFileUploads] Rejected mime: ' . $mime . ' for file: ' . $file->getClientName());
                continue;
            }
    
            // Generate unique filename
            $ext     = $file->getExtension();
            $newName = 'medical_' . $medicalId . '_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;
    
            try {
                $file->move($dir, $newName);
    
                $this->userMedicalFilesModel->insert([
                    'medical_id_fk'      => $medicalId,
                    'file_name'          => $newName,
                    'file_original_name' => $file->getClientName(),
                    'file_type'          => $mime,
                    'file_size'          => $file->getSize(),
                    'file_date'          => date('Y-m-d'),
                    'file_time'          => time(),
                ]);
    
                log_message('info', '[MedicalController::handleFileUploads] Saved: ' . $newName . ' for medical ID ' . $medicalId);
    
            } catch (\Exception $e) {
                log_message('error', '[MedicalController::handleFileUploads] Failed to move file: ' . $e->getMessage());
            }
        }
    }
}