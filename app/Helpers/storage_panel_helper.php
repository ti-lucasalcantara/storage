<?php

/**
 * Helper para o painel de Storage de Arquivos.
 * Formatação de tamanho, data e badge de status.
 */

if (! function_exists('formatar_tamanho_arquivo')) {
    /**
     * Formata tamanho em bytes para exibição amigável (KB, MB, GB).
     */
    function formatar_tamanho_arquivo(?int $bytes): string
    {
        if ($bytes === null || $bytes < 0) {
            return '—';
        }
        if ($bytes === 0) {
            return '0 B';
        }
        $unidades = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int) floor(log($bytes, 1024));

        return round($bytes / (1024 ** $i), 2) . ' ' . ($unidades[$i] ?? 'B');
    }
}

if (! function_exists('formatar_data_storage')) {
    /**
     * Formata data/hora para exibição no painel.
     */
    function formatar_data_storage(?string $dataHora): string
    {
        if ($dataHora === null || $dataHora === '') {
            return '—';
        }
        $ts = strtotime($dataHora);

        return $ts !== false ? date('d/m/Y H:i', $ts) : $dataHora;
    }
}

if (! function_exists('badge_status_arquivo')) {
    /**
     * Retorna classe Bootstrap para badge conforme status do arquivo.
     */
    function badge_status_arquivo(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'ativo'    => 'bg-success',
            'excluido' => 'bg-danger',
            'inativo'  => 'bg-secondary',
            default    => 'bg-secondary',
        };
    }
}
