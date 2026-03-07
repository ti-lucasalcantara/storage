<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration para criar a tabela de logs do sistema.
 * Logs estruturados para uso da API, erros, alertas e auditoria.
 */
class CriarTabelaLogsSistema extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id_log' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'request_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 32,
                'null'       => true,
            ],
            'origem' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'tipo_log' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => false,
            ],
            'nivel_log' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'metodo_http' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'endpoint' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'rota' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'acao' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'mensagem' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'contexto' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'parametros' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'resposta' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'codigo_resposta' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'tempo_execucao_ms' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'ip_origem' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'usuario' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'sistema_origem' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'arquivo_relacionado' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_log', true);
        $this->forge->addKey('tipo_log');
        $this->forge->addKey('origem');
        $this->forge->addKey('metodo_http');
        $this->forge->addKey('codigo_resposta');
        $this->forge->addKey('created_at');
        $this->forge->addKey('endpoint');
        $this->forge->addKey('sistema_origem');
        $this->forge->addKey('arquivo_relacionado');
        $this->forge->addKey('request_id');

        $this->forge->createTable('tb_logs_sistema');
    }

    public function down(): void
    {
        $this->forge->dropTable('tb_logs_sistema');
    }
}
