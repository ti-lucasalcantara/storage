<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model para a tabela de logs do sistema.
 * Registra uso de endpoints, erros, alertas e eventos para consulta no painel.
 */
class LogSistemaModel extends Model
{
    protected $table            = 'tb_logs_sistema';
    protected $primaryKey       = 'id_log';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'request_id',
        'origem',
        'tipo_log',
        'nivel_log',
        'metodo_http',
        'endpoint',
        'rota',
        'acao',
        'mensagem',
        'contexto',
        'parametros',
        'resposta',
        'codigo_resposta',
        'tempo_execucao_ms',
        'ip_origem',
        'user_agent',
        'usuario',
        'sistema_origem',
        'arquivo_relacionado',
    ];

    /**
     * Tipos de log para filtros e badges.
     */
    public const TIPOS = ['sucesso', 'erro', 'alerta', 'informacao'];

    /**
     * Origens possíveis.
     */
    public const ORIGENS = ['api', 'painel', 'sistema', 'exception'];

    /**
     * Retorna contagem por tipo para um período (opcional).
     *
     * @return array<string, int>
     */
    public function contagemPorTipo(?string $dataInicial = null, ?string $dataFinal = null): array
    {
        $builder = $this->builder();
        if ($dataInicial !== null && $dataInicial !== '') {
            $builder->where('created_at >=', $dataInicial . ' 00:00:00');
        }
        if ($dataFinal !== null && $dataFinal !== '') {
            $builder->where('created_at <=', $dataFinal . ' 23:59:59');
        }
        $rows = $builder->select('tipo_log, COUNT(*) as total')
            ->groupBy('tipo_log')
            ->get()
            ->getResultArray();

        $out = ['sucesso' => 0, 'erro' => 0, 'alerta' => 0, 'informacao' => 0];
        foreach ($rows as $r) {
            $out[$r['tipo_log']] = (int) $r['total'];
        }

        return $out;
    }

    /**
     * Contagem total de logs no período (ou todos).
     */
    public function totalLogs(?string $dataInicial = null, ?string $dataFinal = null): int
    {
        $builder = $this->builder();
        if ($dataInicial !== null && $dataInicial !== '') {
            $builder->where('created_at >=', $dataInicial . ' 00:00:00');
        }
        if ($dataFinal !== null && $dataFinal !== '') {
            $builder->where('created_at <=', $dataFinal . ' 23:59:59');
        }

        return (int) $builder->countAllResults(false);
    }

    /**
     * Contagem de logs do dia (hoje).
     */
    public function totalLogsHoje(): int
    {
        $hoje = date('Y-m-d');

        return (int) $this->builder()
            ->where('created_at >=', $hoje . ' 00:00:00')
            ->where('created_at <=', $hoje . ' 23:59:59')
            ->countAllResults(false);
    }
}
