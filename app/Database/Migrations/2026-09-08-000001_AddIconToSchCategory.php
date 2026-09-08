<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIconToSchCategory extends Migration
{
    public function up(): void
    {
        $this->db->query("
            ALTER TABLE `sch_category`
            ADD COLUMN `sch_cat_icon` VARCHAR(60) NULL DEFAULT NULL AFTER `sch_cat_name`
        ");

        $this->db->query("UPDATE `sch_category` SET `sch_cat_icon` = 'bi-balloon-fill' WHERE `sch_cat_initial` = 'Pre School'");
        $this->db->query("UPDATE `sch_category` SET `sch_cat_icon` = 'bi-palette-fill' WHERE `sch_cat_initial` = 'Kindergarten'");
        $this->db->query("UPDATE `sch_category` SET `sch_cat_icon` = 'bi-book-fill' WHERE `sch_cat_initial` = 'Primary'");
        $this->db->query("UPDATE `sch_category` SET `sch_cat_icon` = 'bi-mortarboard-fill' WHERE `sch_cat_initial` = 'Seconday'");
        $this->db->query("UPDATE `sch_category` SET `sch_cat_icon` = 'bi-tools' WHERE `sch_cat_initial` = 'TVET'");
    }

    public function down(): void
    {
        $this->db->query("
            ALTER TABLE `sch_category`
            DROP COLUMN `sch_cat_icon`
        ");
    }
}
