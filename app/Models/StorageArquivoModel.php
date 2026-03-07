<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model para a tabela de armazenamento de arquivos (storage).
 * Utiliza soft deletes e timestamps.
 */
class StorageArquivoModel extends Model
{
    protected $table            = 'tb_storage_arquivos';
    protected $primaryKey       = 'id_arquivo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'sistema',
        'modulo',
        'tipo_entidade',
        'id_entidade',
        'categoria',
        'nome_original',
        'nome_salvo',
        'extensao',
        'mime_type',
        'tamanho_bytes',
        'hash_arquivo',
        'caminho_relativo',
        'enviado_por',
        'ip_origem',
        'status',
    ];

    protected $validationRules = [
        'sistema'        => 'required|max_length[50]',
        'modulo'         => 'required|max_length[100]',
        'nome_original'  => 'required|max_length[255]',
        'nome_salvo'     => 'required|max_length[255]',
        'tamanho_bytes'  => 'required|integer',
        'caminho_relativo' => 'required|max_length[500]',
        'status'         => 'required|in_list[ativo,excluido,inativo]',
    ];

    protected $validationMessages = [
        'sistema' => [
            'required' => 'O campo sistema é obrigatório.',
            'max_length' => 'O sistema deve ter no máximo 50 caracteres.',
        ],
        'modulo' => [
            'required' => 'O campo módulo é obrigatório.',
            'max_length' => 'O módulo deve ter no máximo 100 caracteres.',
        ],
        'nome_original' => [
            'required' => 'O nome original do arquivo é obrigatório.',
            'max_length' => 'O nome original deve ter no máximo 255 caracteres.',
        ],
    ];
}
