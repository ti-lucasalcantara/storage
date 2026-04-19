<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends BaseController
{
    private $dados;

    public function login()
    {
        $redirectTo = $this->request->getGet('redirect_to') ?? '';
        if($redirectTo == 'sair'){
            return redirect()->to( getenv('sso.login_url') );
        }
        return redirect()->to( getenv('sso.login_url') . '?redirect='. urlencode(base64_encode(base_url('auth'))) .'&url_to=' .  urlencode(base64_encode($redirectTo)) );
    }

    public function sair()
    {
        session()->destroy();
        return redirect()->route('home.index');
    }

    public function sso()
    {
        $token = $this->request->getGet('token');
        $url_to = $this->request->getGet('url_to') ?? '';

        if (!$token) {
            session()->setFlashdata( getMessageFail('toast', ['title' => 'Acesso negado!', 'text' => 'Token ausente.']) );
            return redirect()->route('login');
        }

        try {
            $decoded = JWT::decode($token, new Key(getenv('jwt.secret'), 'HS256'));

            $decodedArray = json_decode(json_encode($decoded), true);

            if ($decodedArray['exp'] < time()) {
                throw new \Exception('Token expirado');
            }

            $session = [
                'usuario_logado'  => $decodedArray['user']
            ];
            // Cria sessão local
            session()->set($session);

            if($url_to){
                return redirect()->to(site_url( $url_to ));
            }

            return redirect()->route('home.index');

        } catch (\Exception $e) {
            session()->setFlashdata( getMessageFail('toast', ['title' => 'Acesso negado!', 'text' => 'Token inválido ou expirado']) );
            return redirect()->route('login');
        }
    }

}