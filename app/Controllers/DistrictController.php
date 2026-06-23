<?php

namespace App\Controllers;

use App\Models\DistrictModel;

class DistrictController extends BaseController
{
    public function index()
    {
        echo "test";
    }
    
    public function getDistrictByProvince()
    {
        $html = '';
        
        // Get province ID from POST
        $provinceID = $this->request->getPost('id');
        
        // Verify the request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        

        // Debug: Check what ID is received
        log_message('debug', 'Received Province ID: ' . $provinceID);

        if (empty($provinceID)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Province ID is required'
            ]);
        }
        
        try {
            // Use the districtModel inherited from BaseController
            $districts = $this->districtModel->getDistrictByProvince($provinceID);
            
            log_message('debug', 'Districts found: ' . count($districts));

            if (empty($districts)) {
                $html = '<!--begin::Label-->
                        <label class="form-label mb-3 required">Select District</label>
                        <select class="form-select" aria-label="Select district" name="district" disabled>
                            <option value="">No districts available</option>
                        </select>';
            } else {
                $html = '<!--begin::Label-->
                        <label class="form-label mb-3 required">Select District</label>
                        <select class="form-select" aria-label="Select district" name="district">';
                        if($provinceID != 16){
                            $html .= '<option value="">Select District...</option>';
                        }
                foreach ($districts as $item) {
                    $html .= '<option value="'.$item['district_id'].'">'.$item['district_name'].'</option>';
                }
                
                $html .= '</select>';
            }

            return $this->response->setJSON([
                'success' => true,
                'html' => $html,
                'count' => count($districts)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in getDistrictByProvince: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Server error occurred'
            ]);
        }
    }
    
    public function getDistrictByProvince2()
    {
        $html = '';
        
        // Get province ID from POST
        $provinceID = $this->request->getPost('id');
        
        // Verify the request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        

        // Debug: Check what ID is received
        log_message('debug', 'Received Province ID: ' . $provinceID);

        if (empty($provinceID)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Province ID is required'
            ]);
        }
        
        try {
            // Use the districtModel inherited from BaseController
            $districts = $this->districtModel->getDistrictByProvince($provinceID);
            
            log_message('debug', 'Districts found: ' . count($districts));

            if (empty($districts)) {
                $html = '<!--begin::Label-->
                        <label class="form-label mb-3 required">Select District</label>
                        <select class="form-select" aria-label="Select district" name="district2" disabled>
                            <option value="">No districts available</option>
                        </select>';
            } else {
                $html = '<!--begin::Label-->
                        <label class="form-label mb-3 required">Select District</label>
                        <select class="form-select" aria-label="Select district" name="district2">
                            <option value="">Select District...</option>';
                
                foreach ($districts as $item) {
                    $html .= '<option value="'.$item['district_id'].'">'.$item['district_name'].'</option>';
                }
                
                $html .= '</select>';
            }

            return $this->response->setJSON([
                'success' => true,
                'html' => $html,
                'count' => count($districts)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in getDistrictByProvince: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Server error occurred'
            ]);
        }
    }
    
    public function getDistrictByProvinceAll()
    {
        $html = '';
        
        // Get province ID from POST
        $provinceID = $this->request->getPost('id');
        
        // Verify the request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        

        // Debug: Check what ID is received
        log_message('debug', 'Received Province ID: ' . $provinceID);

        if (empty($provinceID)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Province ID is required'
            ]);
        }
        
        try {
            // Use the districtModel inherited from BaseController
            $districts = $this->districtModel->getDistrictByProvince($provinceID);
            
            log_message('debug', 'Districts found: ' . count($districts));

            if (empty($districts)) {
                $html = '<!--begin::Label-->
                        <label class="form-label mb-3 required">Select District</label>
                        <select class="form-select" aria-label="Select district" name="district" disabled>
                            <option value="">No districts available</option>
                        </select>';
            } else {
                $html = '<!--begin::Label-->
                        <label class="form-label mb-3 required">Select District</label>
                        <select class="form-select" aria-label="Select district" name="district">
                            <option value="">Select District...</option>';
                
                foreach ($districts as $item) {
                    $html .= '<option value="'.$item['district_id'].'">'.$item['district_name'].'</option>';
                }
                
                $html .= '</select>';
            }

            return $this->response->setJSON([
                'success' => true,
                'html' => $html,
                'count' => count($districts)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in getDistrictByProvince: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Server error occurred'
            ]);
        }
    }
}