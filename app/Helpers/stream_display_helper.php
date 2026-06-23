<?php

namespace App\Helpers;

use App\Models\SchoolStreamModel;

if (!function_exists('show_streams')) {
    function show_streams($level_id, $options = [])
    {
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
        $model = new SchoolStreamModel();
        $streams = $model->getStreamsByLevelId((int)$level_id);
        
        // Check if empty
        if (empty($streams)) {
            return '<div class="row"><div class="col-md-12"><div class="alert alert-danger border">
                <i class="ki-duotone ki-information fs-3 text-muted me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                ' . esc($options['empty_message']) . '
            </div></div></div>';
        }
        
        // ✅ FIX: Initialize $html as empty string
        $html = '<div class="row">';
        
        // ✅ FIX: Use .= (append) not = (overwrite)
        foreach ($streams as $stream) {
            $html .= '
            <div class="col-md-4 mb-8">
                <div class="card card-flush h-md-100" style="border: 1px solid #ecf0f1; border-left: 10px solid #a55eea;">
                    <!--begin::Body-->
                    <div class="card-body pt-6">
                        <!--begin::Item-->
                        <div class="d-flex align-items-center">
                            <!--begin::Description-->
                            <div class="flex-grow-1">
                                <span class="text-gray-800 text-hover-primary fw-bold fs-6">' . esc($stream['stream_name'] ?? 'Unnamed') . '</span>
                                <span class="text-muted fw-semibold d-block fs-7">Stream ID: ' . ($stream['stream_id'] ?? '') . '</span>
                                
                                <!--begin::Statistics-->
                                <div class="d-flex align-items-center w-100 mt-3">
                                    <!--begin::Progress-->
                                    <div class="progress h-6px w-100 me-2 bg-light-danger">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <!--end::Progress-->
                                    <!--begin::Value-->
                                    <span class="text-gray-500 fw-semibold fs-7">65%</span>
                                    <!--end::Value-->
                                </div>
                                <!--end::Statistics-->
                            </div>
                            <!--end::Description-->
                            <span class="badge badge-light-success fs-8 fw-bold">Active</span>
                        </div>
                        <!--end:Item-->
                    </div>
                    <!--end: Card Body-->
                </div>
            </div>';
        }
        
        // ✅ FIX: Close the row div
        $html .= '</div>';
        
        return $html;
    }
}