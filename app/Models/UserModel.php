<?php



namespace App\Models;



use CodeIgniter\Model;



class UserModel extends Model

{

    // Database table

    protected $table = 'users';

    

    // Primary key

    protected $primaryKey = 'user_id';

    

    // Allowed fields (for security)

    protected $allowedFields = [

        'district_id_fk',

        'email',

        'pending_email_update',

        'password',
        
        'username',

        'fname',

        'lname',

        'oname',

        'gender',

        'dob',

        'address',

        'phone',

        'profile_photo',

        'created_date',

        'created_time',

        'online_status',

        'password_reset_code',

        'is_a_parent',

        'account_status',

        'user_status',

        'updated_date',

        'updatedTime',

        'security_token',

        'security_token_expiry',

        'reset_token',

        'reset_token_expiry',

        'femis_id',

        'email_verified',

    ];

    

    // Dates configuration

    protected $useTimestamps = false;

    protected $returnType = 'array';

    

    

    public function addUser($data)

    {

        return $this->insert($data);

    }

    

    public function updateUser($id, $data)

    {

        return $this->update($id, $data);

    }

    

    public function getActivationCode($code){

        return $this->where('password_reset_code', $code)->first();

    }

    

    public function findUserFull($id)

    {

        $this->select('

            users.*,

            district.district_id,

            district.district_name,

            province.province_id,

            province.province_name

        ');

        $this->join('district', 'district.district_id = users.district_id_fk', 'left');

        $this->join('province', 'province.province_id = district.province_id_fk', 'left');

        $this->where('users.user_id', $id);

        $this->orderBy('users.user_id', 'DESC');

        return $this->first();

    }

    

    /**

     * Get all users without active admission

     * Excludes Super Admin (role_cat_id = 1) and Parent (role_cat_id = 6)

     */

    public function getUsersWithoutActiveAdmission(): array

    {

        $db = \Config\Database::connect();

    

        return $db->table('users')

            ->select('

                users.user_id,

                users.fname,

                users.lname,

                users.oname,

                users.email,

                users.gender,

                role.role_name,

                role_category.role_cat_name,

                role_category.role_cat_id

            ')

            ->join('user_role',     'user_role.user_id_fk          = users.user_id',             'inner')

            ->join('role',          'role.role_id                   = user_role.role_id_fk',      'inner')

            ->join('role_category', 'role_category.role_cat_id      = role.role_cat_id_fk',       'inner')

            ->where('user_role.user_role_status', 'Active')

            ->whereNotIn('role_category.role_cat_id', [1, 6]) // Exclude Super Admin and Parent

            ->whereNotIn('users.user_id', function($subQuery) {

                $subQuery->select('user_id_fk')

                         ->from('admission')

                         ->where('admission_status', 'Active');

            })

            ->orderBy('users.fname', 'ASC')

            ->get()

            ->getResultArray();

    }



}