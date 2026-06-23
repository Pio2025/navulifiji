<?php
namespace App\Models;

use CodeIgniter\Model;

class SchoolStreamModel extends Model
{
    protected $table = 'stream';
    protected $primaryKey = 'stream_id';
    protected $allowedFields = ['stream_id','sch_level_id_fk','stream_name'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Get School Stream by stream_id
     */
    public function getStream($stream_id)
    {
        return $this->find($stream_id);
    }
    
    /**
     * Get all streams for a specific school level
     *
     * @param int $level_id
     * @return array
     */
    public function getStreamsByLevelId($level_id)
    {
        return $this->where('sch_level_id_fk', $level_id)
                    ->orderBy('stream_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get all streams by school ID
     */
    public function getAllStreamsBySchool($schID)
    {
        return $this->select('stream.*, sch_level.sch_level_id, sch_level.level_id_fk, level.level_id, level.level_name, school.sch_name')
           ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk')
           ->join('level', 'level.level_id = sch_level.level_id_fk')
           ->join('school', 'school.sch_id = sch_level.sch_id_fk')
           ->where('sch_level.sch_id_fk', $schID)
           //->orderBy('level.level_name', 'ASC')
           ->orderBy('stream.stream_id', 'ASC')
           ->findAll();
    }  

    /**
     * Get all School Stream Levels
     */
    public function getAllStream()
    {
        return $this->orderBy('stream_id', 'ASC')
                   ->findAll();
    }


    /**
     * Add new School Stream Level
     */
    public function addStream($data)
    {
        return $this->insert($data);
    }

    /**
     * Update School Stream Level
     */
    public function updateStream($stream_id, $data)
    {
        return $this->update($stream_id, $data);
    }

    /**
     * Delete School Stream
     */
    public function deleteStream($stream_id)
    {
        return $this->delete($stream_id);
    }

    
}