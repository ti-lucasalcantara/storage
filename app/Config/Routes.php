<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Rotas da API de Storage de Arquivos (microserviço central de arquivos).
 * Acesso aos arquivos somente via estas rotas; não há acesso direto pela web.
 */
$routes->get('/', 'Home::index', ['as' => 'home.index']);

// SSO
$routes->get('login', 'Auth::login', ['as' => 'login']);
$routes->get('sair', 'Auth::sair', ['as' => 'sair']);
$routes->get('auth', 'Auth::sso');

// Painel administrativo de Storage (web)
$routes->get('painel/arquivos', 'PainelArquivosController::index');
$routes->get('painel/arquivos/(:num)', 'PainelArquivosController::detalhar/$1');
$routes->post('painel/arquivos/(:num)/excluir', 'PainelArquivosController::excluir/$1');
$routes->post('painel/arquivos/(:num)/restaurar', 'PainelArquivosController::restaurar/$1');

$routes->get('painel/logs', 'PainelLogsController::index');
$routes->get('painel/logs/(:num)', 'PainelLogsController::detalhar/$1');

// API de Arquivos (rotas em português)
$routes->post('arquivos', 'ArquivosController::enviar');
$routes->get('arquivos/(:num)/download', 'ArquivosController::download/$1');
$routes->get('arquivos/(:num)', 'ArquivosController::detalhar/$1');
$routes->delete('arquivos/(:num)', 'ArquivosController::excluir/$1');
