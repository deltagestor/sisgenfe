<?php
/**
 * Sisgenfe.php
 * @author Lídmo <suporte@lidmo.com.br>
 */

namespace Deltagestor\Sisgenfe;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Sisgenfe extends Client
{
    const BASE_URI = 'https://nota.systemainformatica.com.br/api/';

    /**
     * @param string $token
     */
    public function __construct(string $token)
    {
        $config['base_uri'] = self::BASE_URI;
        $config['headers']['Authorization'] = $token;
        parent::__construct($config);
    }

    /**
     * Login
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-auth-login
     * @param array $options
     * @return string Token
     * @throws GuzzleException
     */
    public static function auth(array $options): string
    {
        $client = new Client(['base_uri' => self::BASE_URI]);
        $request = $client->post('login', ['json' => $options]);
        return 'Bearer ' . $request->getBody()->getContents();
    }

    /**
     * Cadastra tomador
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-protected-tomador-cadastro
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    public function newTaker(array $data): array
    {
        $request = $this->post('tomador', ['json' => $data]);
        return json_decode($request->getBody()->getContents(), true);
    }

    /**
     * Consulta tomador
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-protected-consulta-tomador
     * @param string $document
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws GuzzleException
     */
    public function searchTaker(string $document, int $limit = 10, int $offset = 0): array
    {
        $body = [
            'limit' => $limit,
            'offset' => $offset,
            'where' => [
                'eq' => [
                    'documento' => $document,
                ],
            ],
        ];
        $request = $this->post('tomador/query', ['json' => $body]);
        return json_decode($request->getBody()->getContents(), true);
    }

    /**
     * Cadastro NFS-e
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-protected-nfse-cadastro
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    public function newNfs(array $data): array
    {
        $request = $this->post('nfs', ['json' => $data]);
        return json_decode($request->getBody()->getContents(), true);
    }

    /**
     * Consulta NFS-e
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-protected-consulta-nfse
     * @param string $number
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws GuzzleException
     */
    public function searchNfs(string $number, int $limit = 10, int $offset = 0): array
    {
        $body = [
            'limit' => $limit,
            'offset' => $offset,
            'where' => [
                'ge' => [
                    'numero' => $number,
                ],
            ],
        ];
        $request = $this->post('nfs/query', ['json' => $body]);
        return json_decode($request->getBody()->getContents(), true);
    }

    /**
     * Corrigi NFS-e
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-protected-nfse-correcao
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    public function editNfs(array $data): array
    {
        $request = $this->post('nfs/corrige', ['json' => $data]);
        return json_decode($request->getBody()->getContents(), true);
    }

    /**
     * Cancela NFS-e
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-protected-nfse-cancelamento
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    public function cancelNfs(array $data): array
    {
        $request = $this->post('nfs/cancela', ['json' => $data]);
        return json_decode($request->getBody()->getContents(), true);
    }

    /**
     *  Retorna lista de CNAEs que estão atribuídos ao prestador.
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-protected-atividades
     * @return array
     * @throws GuzzleException
     */
    public function getCnaes(): array
    {
        $request = $this->get('cnae', ['headers' => ['Content-Type' => 'application/json']]);
        return json_decode($request->getBody()->getContents(), true);
    }

    /**
     * Consulta CNAE
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-protected-consulta-cnae
     * @param string $code
     * @param int $limit
     * @return array
     * @throws GuzzleException
     */
    public function searchCnae(string $code, int $limit = 10): array
    {
        $body = [
            'limit' => $limit,
            'where' => [
                'eq' => [
                    'lei116.codigo' => $code,
                ],
            ],
        ];
        $request = $this->post('cnae/query', ['json' => $body]);
        return json_decode($request->getBody()->getContents(), true);
    }

    /**
     * Consulta município
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-protected-consulta-municipio
     * @param string $uf
     * @param array $params
     * @param int $limit
     * @return array
     * @throws GuzzleException
     */
    public function searchCity(string $uf, array $params = [], int $limit = 10): array
    {
        $body = [
            'limit' => $limit,
            'where' => [
                'eq' => [
                  'estado.uf' => $uf,
                ],
            ],
        ];
        $body['where']['eq'] = array_merge($body['where']['eq'], $params);
        $request = $this->post('municipio/query', ['json' => $body]);
        return json_decode($request->getBody()->getContents(), true);
    }

    /**
     * Consulta unidade de medida
     * @see https://nota.systemainformatica.com.br/documentacao-webservice#nav-webservice-protected-consulta-unidade
     * @param string $unit
     * @param int $limit
     * @return array
     * @throws GuzzleException
     */
    public function searchUnitOfMeasurement(string $unit, int $limit = 1): array
    {
        $body = [
            'limit' => $limit,
            'where' => [
                'eq' => [
                    'nome' => $unit,
                ],
            ],
        ];
        $request = $this->post('unidade/query', ['json' => $body]);
        return json_decode($request->getBody()->getContents(), true);
    }
}