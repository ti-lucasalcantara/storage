<?php

namespace Tests\Support\Storage;

use RuntimeException;

/**
 * Auxiliar para testes da API de Storage.
 * Cria arquivos temporarios para upload e limpa o diretorio de teste.
 */
class AuxiliarStorageTest
{
    public const AMBIENTE_TESTE = 'TESTES';
    public const SISTEMA_TESTE = 'Teste com Acento.';
    public const MODULO_TESTE = 'Modulo com Espaco!';
    public const SISTEMA_DIRETORIO = 'teste-com-acento';
    public const MODULO_DIRETORIO = 'modulo-com-espaco';

    /**
     * Conteudo minimo de um PDF valido.
     */
    private const CONTEUDO_PDF_MINIMO = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R>>endobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000052 00000 n \n0000000101 00000 n \ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n178\n%%EOF";

    public static function criarArquivoTemporarioPdf(): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'storage_test_');
        if ($tmp === false) {
            throw new RuntimeException('Nao foi possivel criar arquivo temporario.');
        }

        file_put_contents($tmp, self::CONTEUDO_PDF_MINIMO);

        return $tmp;
    }

    public static function criarArquivoTemporarioExtensaoProibida(): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'storage_test_');
        if ($tmp === false) {
            throw new RuntimeException('Nao foi possivel criar arquivo temporario.');
        }

        $novoNome = $tmp . '.php';
        rename($tmp, $novoNome);
        file_put_contents($novoNome, '<?php echo 1;');

        return $novoNome;
    }

    public static function removerArquivoTemporario(string $caminho): void
    {
        if ($caminho !== '' && is_file($caminho)) {
            @unlink($caminho);
        }
    }

    public static function obterDiretorioStorage(string $ambiente = self::AMBIENTE_TESTE): string
    {
        return rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $ambiente;
    }

    public static function limparStorageDeTeste(): void
    {
        $base = self::obterDiretorioStorage();
        $pasta = $base . DIRECTORY_SEPARATOR . self::SISTEMA_DIRETORIO;

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
