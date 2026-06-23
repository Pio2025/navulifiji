<?php

namespace App\Cells;

use App\Models\SchoolStreamModel;

// Simple class without extending Cell
class StreamsCell
{
    public static function byLevel($level_id, $options = [])
    {
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
        
        // Include the view file directly
        $viewFile = APPPATH . 'Cells/Views/streams_' . $options['view_type'] . '.php';
        
        if (!file_exists($viewFile)) {
            return '<div class="alert alert-danger">View template missing</div>';
        }
        
        // Extract variables for the view
        extract([
            'streams' => $streams,
            'level_id' => $level_id,
            'empty_message' => $options['empty_message'],
            'total_count' => count($streams)
        ]);
        
        // Capture output
        ob_start();
        include $viewFile;
        return ob_get_clean();
    }
}