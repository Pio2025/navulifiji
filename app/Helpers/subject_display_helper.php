<?php

namespace App\Helpers;

use App\Models\SubjectModel;
use App\Models\SchoolSubjectModel;
use App\Models\SchoolDepartmentModel;

if (!function_exists('show_subjects')) {
    function show_subjects($level_id, $options = [])
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Default options
        $defaults = [
            'empty_message' => 'No streams found',
            'view_type' => 'list'
        ];
        
        $options = array_merge($defaults, $options);
        
        // Validate
        if (empty($level_id) || !is_numeric($level_id)) {
            return '<div class="alert alert-warning">Invalid Level ID</div>';
        }
        
        // Get data
        $model = new SubjectModel();
        $model2 = new SchoolDepartmentModel();
        $model3 = new SchoolSubjectModel();
        $subjects = $model->getSubjectByLevel((int)$level_id);
        $schID = session()->get('schID');
        $departments = $model2->getAllSchoolDepartmentBySchool($schID);
        
        // Check if empty
        if (empty($subjects)) {
            return '<div class="row"><div class="col-md-12"><div class="alert alert-danger border">
                <i class="ki-duotone ki-information fs-3 text-danger me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                ' . esc($options['empty_message']) . '
            </div></div></div>';
        }
        
        // Get old form data from session
        $oldSubjects = old("subject.{$level_id}") ?? [];
        $oldDepartments = old("subDept.{$level_id}") ?? [];
        
        // ✅ FIX: Initialize $html as empty string
        $html = '<ul class="list-group mb-5 mb-xl-10">';
        
        foreach ($subjects as $subject) {
            $subjectId = $subject['subject_id'];
            $subjectName = esc($subject['subject_name']);
            
            // Check if this subject was previously checked
            $isChecked = in_array($subjectId, $oldSubjects);
            
            // Get previously selected department for this subject
            $selectedDept = $oldDepartments[$subjectId] ?? '';
            
            //if subject is already registered don't display
            $checkEntry = $model3->where('subject_id_fk', $subjectId)->first();
            
            if(!$checkEntry){
                $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
                $html .= '<div>';
                $html .= '<div class="form-check">';
                
                // Add checkbox with checked state if previously selected
                $checkedAttr = $isChecked ? 'checked' : '';
                $html .= '<input class="form-check-input" 
                          type="checkbox" 
                          name="subject['.$level_id.'][]" 
                          value="'.$subjectId.'" 
                          id="subject_'.$level_id.'_'.$subjectId.'" 
                          '.$checkedAttr.' />';
                
                $html .= '<label class="form-check-label text-dark" for="subject_'.$level_id.'_'.$subjectId.'">';
                $html .= $subjectName;
                $html .= '</label>';
                $html .= '</div>';
                $html .= '</div>';
                
                // Department select with previously selected value
                $html .= '<select class="form-select w-50" 
                          name="subDept['.$level_id.']['.$subjectId.']">';
                $html .= '<option value="">Assign to Department</option>';
                
                foreach($departments as $dept){
                    $deptId = $dept['sch_dept_id'];
                    $deptName = esc($dept['dept_name'] ?? 'Unnamed');
                    
                    $selectedAttr = ($selectedDept == $deptId) ? 'selected' : '';
                    $html .= '<option value="'.$deptId.'" '.$selectedAttr.'>'.$deptName.'</option>';
                }
                
                $html .= '</select>';
                $html .= '</li>';
            }
        }
        
        // ✅ FIX: Close the row div
        $html .= '</ul>';
        
        return $html;
    }
}