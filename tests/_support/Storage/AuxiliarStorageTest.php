<?php

namespace Tests\Support\Storage;

use RuntimeException;

/**
 * Auxiliar para testes da API de Storage.
 * Cria arquivos temporários para upload e limpa o diretório de teste.
 */
class AuxiliarStorageTest
{
    /** Sistema e módulo usados nos testes (evita poluir o storage real; limpamos no tearDown). */
    public const SISTEMA_TESTE = 'teste';
    public const MODULO_TESTE  = 'phpunit';

    /**
     * Conteúdo mínimo de um PDF válido (para passar na validação de MIME).
     */
    private const CONTEUDO_PDF_MINIMO = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R>>endobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000052 00000 n \n0000000101 00000 n \ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n178\n%%EOF";

    /**
     * Cria um arquivo temporário com conteúdo de PDF mínimo.
     * O arquivo deve ser removido pelo chamador (ex.: unlink) após o teste.
     *
     * @return string Caminho do arquivo temporário criado
     */
    public static function criarArquivoTemporarioPdf(): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'storage_test_');
        if ($tmp === false) {
            throw new RuntimeException('Não foi possível criar arquivo temporário.');
        }
        file_put_contents($tmp, self::CONTEUDO_PDF_MINIMO);

        return $tmp;
    }

    /**
     * Cria um arquivo temporário com extensão e conteúdo para teste de extensão proibida.
     * Ex.: .php com conteúdo PHP.
     *
     * @return string Caminho do arquivo temporário criado
     */
    public static function criarArquivoTemporarioExtensaoProibida(): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'storage_test_');
        if ($tmp === false) {
            throw new RuntimeException('Não foi possível criar arquivo temporário.');
        }
        $novoNome = $tmp . '.php';
        rename($tmp, $novoNome);
        file_put_contents($novoNome, '<?php echo 1;');

        return $novoNome;
    }

    /**
     * Remove o arquivo temporário do disco (seguro se já foi removido).
     */
    public static function removerArquivoTemporario(string $caminho): void
    {
        if ($caminho !== '' && is_file($caminho)) {
            @unlink($caminho);
        }
    }

    /**
     * Retorna o diretório de storage usado pela aplicação (WRITEPATH . 'storage').
     */
    public static function obterDiretorioStorage(): string
    {
        $base = rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'storage';

        return $base;
    }

    /**
     * Remove a pasta de storage dos testes (teste/phpunit) para não poluir o storage real.
     */
    public static function limparStorageDeTeste(): void
    {
        $base   = self::obterDiretorioStorage();
        $pasta  = $base . DIRECTORY_SEPARATOR . self::SISTEMA_TESTE . DIRECTORY_SEPARATOR . self::MODULO_TESTE;
        if (! is_dir($pasta)) {
            return;
        }
        self::removerDiretorioRecursivo($pasta);
    }

    private static function removerDiretorioRecursivo(string $dir): void
    {
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $item) {
            if ($item->isDir()) {
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }
        @rmdir($dir);
    }
}
