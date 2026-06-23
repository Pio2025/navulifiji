<?php
namespace App\Models;

use CodeIgniter\Model;

class StreamOptionalSubjectModel extends Model
{
    protected $table = 'stream_optional_subject';
    protected $primaryKey = 'stream_opt_sub_id';
    protected $allowedFields = ['stream_opt_sub_id','sch_sub_id_fk','stream_id_fk','option_num'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Get School Stream by stream_id
     */
    public function getStreamOptionalSubject($id)
    {
        return $this->find($id);
    }
    
    public function getStreamOptionalSubjectByOptNum($id)
    {
        return $this->where('option_num', $id)->findAll();
    }

    /**
     * Get School Stream by sch_id_fk
     */
    public function getStreamOptionalSubjectByStream($stream_id)
    {
        //return $this->where('stream_id_fk', $stream_id)->findAll();
        return $this->select('stream_optional_subject.*, subject.subject_name')
                ->join('sch_subject', 'sch_subject.sch_sub_id = stream_optional_subject.sch_sub_id_fk','left')
                ->join('subject', 'subject.subject_id = sch_subject.subject_id_fk','left')
                ->where('stream_optional_subject.stream_id_fk', $stream_id)
                ->orderBy('stream_optional_subject.option_num', 'ASC')
                ->orderBy('subject.subject_name', 'ASC')
                ->findAll();
    }   
    
    public function getStreamOptionalSubjectByStreamFirst($stream_id)
    {
        return $this->where('stream_id_fk', $stream_id)->first();
    }
    
    /**
     * Get School Stream by sch_id_fk
     */
    public function getStreamOptionalSubjectEntry($sub_id,$stream_id)
    {
        //return $this->where('sub_id_fk', $sub_id)
        return $this->select('stream_optional_subject.*, subject.subject_name')
                ->join('sch_subject', 'sch_subject.sch_sub_id = stream_optional_subject.sch_sub_id_fk','left')
                ->join('subject', 'subject.subject_id = sch_subject.subject_id_fk','left')
                ->where('stream_optional_subject.sch_sub_id_fk', $sub_id)
                ->where('stream_optional_subject.stream_id_fk', $stream_id)
                ->findAll();
    }
    
    /**
     * Get next option number (max + 1 or 1 if no records)
     */
    public function getNextOptionNum()
    {
        $builder = $this->db->table($this->table);
        $builder->selectMax('option_num', 'max_option_num');
        $query = $builder->get();
        
        $result = $query->getRow();
        
        // If no records exist, start with 1, otherwise max + 1
        return $result && $result->max_option_num ? $result->max_option_num + 1 : 1;
    }

    /**
     * Get all School Stream Levels
     */
    public function getAllStreamOptionalSubject()
    {
        return $this->orderBy('stream_opt_sub_id', 'ASC')
                   ->findAll();
    }


    /**
     * Add new School Stream Level
     */
    public function addStreamOptionalSubject($data)
    {
        return $this->insert($data);
    }

    /**
     * Update School Stream Level
     */
    public function updateStreamOptionalSubject($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete School Stream
     */
    public function deleteStreamOptionalSubject($id)
    {
        return $this->delete($id);
    }
    
    public function deleteStreamOptionalSubjectByOptNum($id)
    {
        return $this->where('option_num', $id)->delete();
    }

    
}