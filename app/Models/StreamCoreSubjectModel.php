<?php
namespace App\Models;

use CodeIgniter\Model;

class StreamCoreSubjectModel extends Model
{
    protected $table = 'stream_core_subject';
    protected $primaryKey = 'stream_core_sub_id';
    protected $allowedFields = ['stream_core_sub_id','sch_sub_id_fk','stream_id_fk'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Get School Stream by stream_id
     */
    public function getStreamCoreSubject($id)
    {
        return $this->find($id);
    }

    /**
     * Get School Stream by sch_id_fk
     */
    public function getStreamCoreSubjectByStream($id)
    {
        //return $this->where('stream_id_fk', $id)->findAll();
        return $this->select('stream_core_subject.*, subject.subject_name, subject.level_id_fk')
                ->join('sch_subject', 'sch_subject.sch_sub_id = stream_core_subject.sch_sub_id_fk','left')
                ->join('subject', 'subject.subject_id = sch_subject.subject_id_fk','left')
                ->where('stream_core_subject.stream_id_fk', $id)
                ->orderBy('subject.subject_id', 'ASC')
                ->findAll();
    } 
    
    public function getStreamCoreSubjectByStreamFirst($id)
    {
        return $this->where('stream_id_fk', $id)->first();
    }
    
    /**
     * Get School Stream by sch_id_fk
     */
    public function getStreamCoreSubjectEntry($sub_id,$stream_id)
    {
        //return $this->where('sub_id_fk', $sub_id)
        return $this->select('stream_core_subject.*, subject.subject_name, subject.level_id_fk')
                ->join('sch_subject', 'sch_subject.sch_sub_id = stream_core_subject.sch_sub_id_fk','left')
                ->join('subject', 'subject.subject_id = sch_subject.subject_id_fk','left')
                ->where('stream_core_subject.sch_sub_id_fk', $sub_id)
                ->where('stream_core_subject.stream_id_fk', $stream_id)
                ->findAll();
    } 

    /**
     * Get all School Stream Levels
     */
    public function getAllStreamCoreSubject()
    {
        return $this->orderBy('stream_core_sub_id', 'ASC')
                   ->findAll();
    }


    /**
     * Add new School Stream Level
     */
    public function addStreamCoreSubject($data)
    {
        return $this->insert($data);
    }

    /**
     * Update School Stream Level
     */
    public function updateStreamCoreSubject($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete School Stream
     */
    public function deleteStreamCoreSubject($id)
    {
        return $this->delete($id);
    }

    
}