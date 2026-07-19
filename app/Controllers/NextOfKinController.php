<?php
namespace App\Controllers;

/**
 * NextOfKinController
 * 
 * Handles CRUD operations for next of kin contacts
 * Uses models loaded in BaseController:
 * - $this->nextOfKinModel
 * - $this->userModel
 * - $this->session
 */
class NextOfKinController extends BaseController
{
    protected $db;
    
    public function __construct()
    {
        helper(['form']);
        
        // Initialize database connection
        $this->db = \Config\Database::connect();
        
        // All models already loaded in BaseController ✅
        // $this->nextOfKinModel
        // $this->userModel
        // $this->session
    }
    
    /**
     * Add new next of kin
     */
    public function add()
    {
        $this->session->set('activeTab','profile');
        
        // Check if logged in using BaseController method
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }
        
        // Get user_id_fk from POST
        $userId = $this->request->getPost('user_id_fk');
        
        // Validate user_id_fk is provided
        if (empty($userId)) {
            log_message('error', '[NextOfKin::add] user_id_fk is missing from form submission');
            log_message('debug', '[NextOfKin::add] POST data: ' . json_encode($this->request->getPost()));
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID is required. Please refresh the page and try again.'
            ]);
        }
        
        log_message('debug', '[NextOfKin::add] Adding next of kin for user ID: ' . $userId);

        // A linked existing user supplies its own authoritative contact details,
        // so the free-text phone format rule (exact 7 digits) doesn't apply to it.
        $linkedUserId = (int) $this->request->getPost('linked_user_id_fk');
        $isLinked     = $linkedUserId > 0;

        // Validation rules
        $rules = [
            'user_id_fk' => 'required|integer',
            'linked_user_id_fk' => 'permit_empty|integer',
            'next_of_kin_name' => 'required|min_length[2]|max_length[100]',
            'next_of_kin_relationship' => 'required|max_length[50]',
            'next_of_kin_phone' => $isLinked ? 'permit_empty|max_length[20]' : 'required|exact_length[7]|numeric',
            'next_of_kin_email' => 'permit_empty|valid_email|max_length[100]',
            'next_of_kin_address' => 'permit_empty|max_length[500]'
        ];

        $messages = [
            'user_id_fk' => [
                'required' => 'User ID is required',
                'integer' => 'Invalid user ID'
            ],
            'next_of_kin_name' => [
                'required' => 'Full name is required',
                'min_length' => 'Name must be at least 2 characters',
                'max_length' => 'Name cannot exceed 100 characters'
            ],
            'next_of_kin_relationship' => [
                'required' => 'Relationship is required',
                'max_length' => 'Relationship cannot exceed 50 characters'
            ],
            'next_of_kin_phone' => [
                'required' => 'Phone number is required',
                'exact_length' => 'Phone must be exactly 7 digits',
                'numeric' => 'Phone must contain only numbers'
            ],
            'next_of_kin_email' => [
                'valid_email' => 'Please provide a valid email address',
                'max_length' => 'Email cannot exceed 100 characters'
            ],
            'next_of_kin_address' => [
                'max_length' => 'Address cannot exceed 500 characters'
            ]
        ];

        // Validate input
        if (!$this->validate($rules, $messages)) {
            $errors = $this->validator->getErrors();
            $errorMessage = implode(', ', $errors);
            
            log_message('warning', '[NextOfKin::add] Validation failed: ' . $errorMessage);
            
            return $this->response->setJSON([
                'success' => false,
                'message' => $errorMessage,
                'errors' => $errors
            ]);
        }
        
        try {
            // Validate user exists
            $user = $this->userModel->find($userId);
            if (!$user) {
                log_message('error', '[NextOfKin::add] User not found: ID ' . $userId);
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found. Please refresh the page.'
                ]);
            }
            
            log_message('info', '[NextOfKin::add] User verified: ' . $user['fname'] . ' ' . $user['lname'] . ' (ID: ' . $userId . ')');

            // Check if user already has 3 next of kin
            $existingCount = $this->nextOfKinModel->where('user_id_fk', $userId)->countAllResults();
            if ($existingCount >= 3) {
                log_message('warning', '[NextOfKin::add] User ' . $userId . ' already has 3 next of kin. Cannot add more.');

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You can only add up to 3 next of kin contacts per user. Please delete an existing contact first.'
                ]);
            }

            // If linking to an existing user account, re-verify server-side that
            // it exists and isn't a Student (the search endpoint already excludes
            // students, but the ID is client-supplied so it must be re-checked here).
            if ($isLinked) {
                $linkedRoleCat = (int) $this->db->query("
                    SELECT rc.role_cat_id
                    FROM user_role ur
                    INNER JOIN role r ON r.role_id = ur.role_id_fk
                    INNER JOIN role_category rc ON rc.role_cat_id = r.role_cat_id_fk
                    WHERE ur.user_id_fk = ? AND ur.user_role_status = 'Active'
                    LIMIT 1
                ", [$linkedUserId])->getRow()->role_cat_id ?? 0;

                if ($linkedRoleCat === 0) {
                    log_message('error', '[NextOfKin::add] Linked user not found or has no active role: ID ' . $linkedUserId);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Selected user could not be found. Please search again.'
                    ]);
                }

                if ($linkedRoleCat === 4) {
                    log_message('warning', '[NextOfKin::add] Attempted to link a Student as next of kin: ID ' . $linkedUserId);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'A student account cannot be set as a next of kin.'
                    ]);
                }
            }

            // Prepare data
            $data = [
                'user_id_fk' => $userId,
                'linked_user_id_fk' => $isLinked ? $linkedUserId : null,
                'next_of_kin_name' => $this->request->getPost('next_of_kin_name'),
                'next_of_kin_relationship' => $this->request->getPost('next_of_kin_relationship'),
                'next_of_kin_address' => $this->request->getPost('next_of_kin_address') ?: null,
                'next_of_kin_phone' => $this->request->getPost('next_of_kin_phone') ?: null,
                'next_of_kin_email' => $this->request->getPost('next_of_kin_email') ?: null,
                'is_primary_contact' => $this->request->getPost('is_primary_contact') ? 1 : 0,
                'is_emergency_contact' => $this->request->getPost('is_emergency_contact') ? 1 : 0,
                'authorized_pickup' => $this->request->getPost('authorized_pickup') ? 1 : 0,
                'created_date' => date('Y-m-d')
            ];
            
            // If setting as primary, unset other primary contacts
            if ($data['is_primary_contact'] == 1) {
                $this->db->table('next_of_kin')
                         ->where('user_id_fk', $userId)
                         ->set(['is_primary_contact' => 0])
                         ->update();
                         
                log_message('info', '[NextOfKin::add] Unset other primary contacts for user ' . $userId);
            }
            
            // Insert next of kin
            $kinId = $this->nextOfKinModel->addNextOfKin($data);
            
            if ($kinId) {
                // Get the inserted record
                $insertedData = $this->nextOfKinModel->find($kinId);
                
                log_message('info', '[NextOfKin::add] Success: ' . $data['next_of_kin_name'] . ' (ID: ' . $kinId . ') for user ' . $userId);
                
                // Log activity using BaseController method
                $this->logActivity('add_next_of_kin', [
                    'next_of_kin_id' => $kinId,
                    'user_id_fk' => $userId,
                    'next_of_kin_name' => $data['next_of_kin_name']
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Next of kin added successfully!',
                    'data' => $insertedData
                ]);
            } else {
                log_message('error', '[NextOfKin::add] Insert failed for user ' . $userId);
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add next of kin. Please try again.'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', '[NextOfKin::add] Exception: ' . $e->getMessage());
            log_message('error', '[NextOfKin::add] Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while adding next of kin: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update next of kin
     */
    public function update()
    {
        $this->session->set('activeTab','profile');
        
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }
        
        $kinId = $this->request->getPost('next_of_kin_id');
        $userId = $this->request->getPost('user_id_fk');
        
        if (empty($userId)) {
            log_message('error', '[NextOfKin::update] user_id_fk missing');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID is required'
            ]);
        }
        
        log_message('debug', '[NextOfKin::update] Updating ID: ' . $kinId . ' for user: ' . $userId);

        // A record linked to an existing user account carries that account's own
        // contact details, so the strict 7-digit phone format doesn't apply to it.
        $existingForRules = $this->nextOfKinModel->find($kinId);
        $isLinked         = !empty($existingForRules['linked_user_id_fk']);

        // Same validation rules as add
        $rules = [
            'next_of_kin_id' => 'required|integer',
            'user_id_fk' => 'required|integer',
            'next_of_kin_name' => 'required|min_length[2]|max_length[100]',
            'next_of_kin_relationship' => 'required|max_length[50]',
            'next_of_kin_phone' => $isLinked ? 'permit_empty|max_length[20]' : 'required|exact_length[7]|numeric',
            'next_of_kin_email' => 'permit_empty|valid_email|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('warning', '[NextOfKin::update] Validation failed: ' . implode(', ', $errors));
            
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $errors),
                'errors' => $errors
            ]);
        }
        
        try {
            // Check if exists
            $existing = $this->nextOfKinModel->find($kinId);
            if (!$existing) {
                log_message('error', '[NextOfKin::update] Not found: ID ' . $kinId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Next of kin not found'
                ]);
            }
            
            // Security check: verify user_id_fk matches
            if ($existing['user_id_fk'] != $userId) {
                log_message('error', '[NextOfKin::update] User ID mismatch. Expected: ' . $existing['user_id_fk'] . ', Got: ' . $userId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request'
                ]);
            }
            
            // Prepare update data
            $data = [
                'next_of_kin_name' => $this->request->getPost('next_of_kin_name'),
                'next_of_kin_relationship' => $this->request->getPost('next_of_kin_relationship'),
                'next_of_kin_address' => $this->request->getPost('next_of_kin_address') ?: null,
                'next_of_kin_phone' => $this->request->getPost('next_of_kin_phone') ?: null,
                'next_of_kin_email' => $this->request->getPost('next_of_kin_email') ?: null,
                'is_primary_contact' => $this->request->getPost('is_primary_contact') ? 1 : 0,
                'is_emergency_contact' => $this->request->getPost('is_emergency_contact') ? 1 : 0,
                'authorized_pickup' => $this->request->getPost('authorized_pickup') ? 1 : 0,
                'updated_date' => date('Y-m-d')
            ];
            
            // Handle primary contact
            if ($data['is_primary_contact'] == 1) {
                $this->db->table('next_of_kin')
                         ->where('user_id_fk', $userId)
                         ->where('next_of_kin_id !=', $kinId)
                         ->set(['is_primary_contact' => 0])
                         ->update();
            }
            
            // Update
            $updated = $this->nextOfKinModel->updateNextOfKin($kinId, $data);
            
            if ($updated !== false) {
                $updatedData = $this->nextOfKinModel->find($kinId);
                
                log_message('info', '[NextOfKin::update] Success: ID ' . $kinId);
                
                $this->logActivity('update_next_of_kin', [
                    'next_of_kin_id' => $kinId,
                    'user_id_fk' => $userId
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Next of kin updated successfully!',
                    'data' => $updatedData
                ]);
            } else {
                log_message('error', '[NextOfKin::update] Update failed: ID ' . $kinId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update. Please try again.'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', '[NextOfKin::update] Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get single next of kin for editing
     */
    public function get($kinId)
    {
        $this->session->set('activeTab','profile');
        
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }
        
        try {
            $data = $this->nextOfKinModel->find($kinId);
            
            if ($data) {
                log_message('debug', '[NextOfKin::get] Retrieved ID: ' . $kinId);
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $data
                ]);
            } else {
                log_message('warning', '[NextOfKin::get] Not found: ID ' . $kinId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Next of kin not found'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', '[NextOfKin::get] Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete next of kin
     */
    public function delete($kinId)
    {
        $this->session->set('activeTab','profile');
        
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }
        
        try {
            $existing = $this->nextOfKinModel->find($kinId);
            if (!$existing) {
                log_message('warning', '[NextOfKin::delete] Not found: ID ' . $kinId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Next of kin not found'
                ]);
            }
            
            log_message('info', '[NextOfKin::delete] Deleting: ' . $existing['next_of_kin_name'] . ' (ID: ' . $kinId . ')');
            
            $deleted = $this->nextOfKinModel->deleteNextOfKin($kinId);
            
            if ($deleted) {
                log_message('info', '[NextOfKin::delete] Success: ID ' . $kinId);
                
                $this->logActivity('delete_next_of_kin', [
                    'next_of_kin_id' => $kinId,
                    'user_id_fk' => $existing['user_id_fk'],
                    'next_of_kin_name' => $existing['next_of_kin_name']
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Next of kin deleted successfully!'
                ]);
            } else {
                log_message('error', '[NextOfKin::delete] Delete failed: ID ' . $kinId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete. Please try again.'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', '[NextOfKin::delete] Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }
}
