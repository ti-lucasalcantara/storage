<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdicionarAmbienteNaTabelaStorageArquivos extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('tb_storage_arquivos')) {
            return;
        }

        if (! $this->db->fieldExists('ambiente', 'tb_storage_arquivos')) {
            $this->forge->addColumn('tb_storage_arquivos', [
                'ambiente' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => false,
                    'default'    => 'PROD',
                    'after'      => 'id_arquivo',
                ],
            ]);

            $this->db->query("UPDATE tb_storage_arquivos SET ambiente = 'PROD' WHERE ambiente IS NULL OR ambiente = ''");
        }
    }

    public function down(): void
    {
        if (! $this->db->tableExists('tb_storage_arquivos')) {
            return;
        }

        if ($this->db->fieldExists('ambiente', 'tb_storage_arquivos')) {
            $this->forge->dropColumn('tb_storage_arquivos', 'ambiente');
        }
    }
}
