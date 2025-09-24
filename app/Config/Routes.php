<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Rsv\Quote::framedQuote');
$routes->get('test', 'TestController::index');
$routes->get('logout', 'Registro::logout');

// GraphQL
// $routes->post('graphql', 'GraphQL::index');
$routes->post('graphql', 'GraphqlController::index');
$routes->options('graphql', 'GraphqlController::index'); // CORS preflight

$routes->group('third', function($routes){
    $routes->get('industrias', 'Third\Industrias::index');
    $routes->get('test', 'Third\Industrias::test');
});

// CMS
$routes->group('cms', function($routes){
    $routes->get('mailTranspo', 'Cms\StrapiController::getTranspoMailContent');
});

$routes->group('reports', function($routes){
    $routes->get('uk', 'Reports\Promos::promoUk');
    $routes->post('uk', 'Reports\Promos::promoUk');
});

// DBTest
$routes->group('db', function($routes){
    $routes->get('test', 'Dbtest::index');
    $routes->get('wh/(:any)', 'Warehouse\Reservations::show/$1');
    $routes->get('rsv/(:any)', 'Zd\Tickets::getReservation/$1');
});

// CallitOnce
$routes->group('cio', function($routes){
    $routes->get('llamadas', 'Cio\UploadCalls::mostrarFormulario');
    $routes->post('llamadas/cargar', 'Cio\UploadCalls::cargarCSV');
    $routes->get('load', 'Cio\UploadCalls::loadCSV');
    $routes->get('agentActivity', 'Cio\AgentActivity::loadCSV');
    $routes->get('agentActivityLocal', 'Cio\AgentActivity::loadCSVLocal');
    
    $routes->group('query', function($routes){
        $routes->get('todayCalls', 'Cio\QueryCalls::llamadasDia');
    });

    // Rutas para actividad de agentes en el dashboard CIO
    
    $routes->group('dashboard', function($routes){
        $routes->get('', 'Cio\QueryCalls::test');
        $routes->get('disposicion', 'Cio\QueryCalls::types');
        $routes->get('disposicion/(:any)', 'Cio\QueryCalls::types/$1');
        $routes->get('disposicion/(:any)/(:any)', 'Cio\QueryCalls::types/$1/$2');
        $routes->get('disposicion/(:any)/(:any)/(:any)', 'Cio\QueryCalls::types/$1/$2/$3');
        $routes->get('queues', 'Cio\QueryCalls::queues');
        $routes->get('langs', 'Cio\QueryCalls::langs');
        $routes->get('hotels', 'Cio\QueryCalls::hotels');
        $routes->get('hotels/(:any)', 'Cio\QueryCalls::hotels/$1');
        $routes->get('hotels/(:any)/(:any)', 'Cio\QueryCalls::hotels/$1/$2');
        $routes->get('hotels/(:any)/(:any)/(:any)', 'Cio\QueryCalls::hotels/$1/$2/$3');
        $routes->get('callJourney', 'Cio\QueryCalls::callJourney');
        $routes->get('callJourney/(:any)', 'Cio\QueryCalls::callJourney/$1');
        $routes->get('callJourney/(:any)/(:any)', 'Cio\QueryCalls::callJourney/$1/$2');
        $routes->get('calls', 'Cio\QueryCalls::calls');
        $routes->get('calls/(:any)', 'Cio\QueryCalls::calls/$1/');
        $routes->get('calls/(:any)/(:any)/(:any)', 'Cio\QueryCalls::calls/$1/$2/$3');
        $routes->get('yearly/calls', 'Cio\QueryCalls::calls_yearly');
        $routes->get('yearly/calls/(:any)', 'Cio\QueryCalls::calls_yearly/$1/');
        $routes->get('yearly/calls/(:any)/(:any)/(:any)', 'Cio\QueryCalls::calls_yearly/$1/$2/$3');
        $routes->get('activity/monthly', 'Cio\AgentActivity::monthly');
        $routes->get('activity/live', 'Cio\AgentActivity::live');
    });
});

$routes->group('public', function($routes){
    $routes->post('addTransfer', 'Transpo\TransportacionController::storeForm');
    $routes->get('transfer-reg', 'Transpo\TransportacionController::showForm');
    $routes->get('transfer-reg/(:num)', 'Transpo\TransportacionController::showForm/$1');
    $routes->get('invalid_form', 'Transpo\TransportacionController::invalid');
    $routes->get('consulta_transpo', 'Transpo\TransportacionController::consultaHotel');
    $routes->get('consulta_transpo/today', 'Transpo\TransportacionController::today');
});

$routes->group('cc', function($routes){
    $routes->get('', 'Rsv\Quote::framedQuote');
    $routes->get('cotizador', 'Rsv\Quote::framedQuote');
    $routes->get('test', 'Rsv\Quote::test');
});

$routes->group('mailing', function($routes){
    $routes->get('requestTemplate/(:num)', 'Transpo\TransportacionController::mailRequest/$1');
});


// Transportacion
// $routes->group('transpo', function($routes){
// $routes->group('transpo', function($routes){
$routes->group('transpoTest', function($routes){
    $routes->get('nextAll', 'Transpo\TransportacionController::exportNextAll');
    $routes->get('expotNextDay', 'Transpo\TransportacionController::exportNextDay');
});


$routes->group('app', ['filter' => 'authFilter'], function($routes){
    $routes->get('/', 'App\AppController::index');
});

$routes->group('admin', ['filter' => 'authFilter'], function($routes){
    $routes->get('/', 'Admin\AdminController::index');
    $routes->get('codes', 'Admin\AdminController::codes');
    $routes->post('code_modify', 'Admin\AdminController::code_modify');

    // Horarios Admin
    $routes->get('horarios', 'Admin\HorarioController::index');
    $routes->get('horarios/create', 'Admin\HorarioController::form');
    $routes->get('horarios/edit/(:num)', 'Admin\HorarioController::form/$1');
    $routes->post('horarios/save', 'Admin\HorarioController::save');

    // Listar los horarios del usuario en la vista de home
    $routes->get('home/schedules', 'Admin\HorarioController::homeSchedules');
});

$routes->group('transpo', ['filter' => 'authFilter'], function($routes){
    $routes->get('/', 'Transpo\TransportacionController::index');
    $routes->get('create', 'Transpo\TransportacionController::create');
    $routes->post('store', 'Transpo\TransportacionController::store');
    $routes->post('removeTicket/(:num)/(:num)', 'Transpo\TransportacionController::removeTicket/$1/$2');
    $routes->get('edit/(:num)', 'Transpo\TransportacionController::edit/$1');
    $routes->get('editStatus/(:num)/(:any)', 'Transpo\TransportacionController::editStatus/$1/$2');
    $routes->get('confirmDelete/(:num)', 'Transpo\TransportacionController::confirmDelete/$1');
    $routes->post('update/(:num)', 'Transpo\TransportacionController::update/$1');
    $routes->delete('delete/(:num)', 'Transpo\TransportacionController::delete/$1');
    $routes->get('db/get', 'Transpo\DatabaseController::getIncluded/3');
    $routes->get('db/get/(:num)', 'Transpo\DatabaseController::getIncluded/$1');
    $routes->get('history/(:num)', 'Transpo\TransportacionController::getHistory/$1');
    $routes->get('searchFolio/(:num)', 'Transpo\TransportacionController::findByFolio/$1');
    $routes->get('pass', 'Log\Login::passHash');
    $routes->get('nextDay', 'Transpo\TransportacionController::nextDayServices');
    $routes->post('duplicate/(:num)', 'Transpo\TransportacionController::duplicateService/$1');
    $routes->post('sendNewRequest/(:num)', 'Transpo\TransportacionController::newMailRequest/$1');
    $routes->post('conf', 'Transpo\TransportacionController::confirmTranspoMail');
    $routes->get('confPreview', 'Transpo\TransportacionController::transpoPreviewConf');
    $routes->get('expotNewQwt', 'Transpo\TransportacionController::exportNew');
    $routes->get('exportNextDay', 'Transpo\TransportacionController::exportNextDay');
    $routes->post('confirmExport', 'Transpo\TransportacionController::exportNewConfirm');
    $routes->post('sendQwtServices', 'Transpo\TransportacionController::sendQwtConfirms');
    $routes->get('pendingConf', 'Transpo\TransportacionController::pendingConf');
});

// $routes->group('zd|app', function($routes){
$routes->group('zdapp', ['filter' => 'zendeskFilter'], function($routes){
    // $routes->post('/', 'Zdapp\ZendeskAppController::index');
    $routes->post('/', 'Zdapp\ZendeskAppController::index');
    
    $routes->group('conf', function($routes){
        $routes->post('/', 'Zdapp\ZendeskAppController::confirms');
    });
    $routes->group('transpo', function($routes){
        $routes->post('/', 'Zdapp\ZendeskAppController::transpo');
        $routes->get('searchFolios/(:any)', 'Transpo\TransportacionController::search/$1');
    });
    $routes->group('quote', function($routes){
        $routes->post('/', 'Zdapp\ZendeskAppController::quote');
    });
});


$routes->group('zdappC', function($routes){
    $routes->group('transpo',  function($routes){
        $routes->get('/', 'Zdapp\ZendeskAppController::transpo');
        $routes->post('/', 'Cio\QueryCalls::calls');
        $routes->get('searchFolios/(:any)', 'Transpo\TransportacionController::search/$1');
        $routes->get('searchFolio/(:any)', 'Transpo\TransportacionController::findByFolio/$1');
        $routes->get('searchIds/(:num)/(:num)', 'Transpo\TransportacionController::findById/$1/$2');
        $routes->post('requestTemplate', 'Transpo\TransportacionController::mailRequest');
        $routes->post('requestLink', 'Transpo\TransportacionController::linkRequest');
        $routes->post('conf', 'Transpo\TransportacionController::confirmTranspoMail');

        $routes->get('create', 'Transpo\TransportacionController::create');
        $routes->post('store', 'Transpo\TransportacionController::store');
        $routes->post('removeTicket/(:num)/(:num)', 'Transpo\TransportacionController::removeTicket/$1/$2');
        $routes->post('setPayment', 'Transpo\TransportacionController::setPaymentTicket');
        $routes->get('edit/(:num)', 'Transpo\TransportacionController::edit/$1');
        $routes->get('editStatus/(:num)/(:any)', 'Transpo\TransportacionController::editStatus/$1/$2');
        $routes->get('confirmDelete/(:num)', 'Transpo\TransportacionController::confirmDelete/$1');
        $routes->post('update/(:num)', 'Transpo\TransportacionController::update/$1');
        $routes->delete('delete/(:num)', 'Transpo\TransportacionController::delete/$1');
        $routes->get('db/get', 'Transpo\DatabaseController::getIncluded/3');
        $routes->get('db/get/(:num)', 'Transpo\DatabaseController::getIncluded/$1');
        $routes->get('history/(:num)', 'Transpo\TransportacionController::getHistory/$1');
        
        // DBController
        $routes->get('getRsv/(:any)', 'Transpo\DatabaseController::getRsva/$1');
        // $routes->get('getRsvHtml/(:any)', 'Transpo\DatabaseController::getRsva/$1/1'); // $2 es boolean para vista html
        $routes->get('getRsvHtml/(:any)', 'Transpo\DatabaseController::getRsva/$1/today/1'); // $2 es boolean para vista html
        
        // CREATE NEW ROUND TRIP
        $routes->post('saveNewRound', 'Transpo\TransportacionController::storeRound'); 
        
    });

    // Confirmations
    $routes->group('confirmations', function($routes){
        $routes->post('confPreview', 'Zd\Tickets::sendConf/preview');
        $routes->post('sendConf', 'Zd\Tickets::sendConf'); 
        $routes->post('pdfConf', 'Zd\Tickets::sendConf/pdf'); 
        $routes->get('download/(:any)', 'Zd\Tickets::downloadPdf/$1'); 
    });
});

// Zendesk
$routes->group('zd', function($routes){
    $routes->group('mailing', function($routes){
        $routes->get('conf', 'Zd\Mailing::index');
        $routes->get('confHtml', 'Zd\Mailing::getConf');
        $routes->post('confPreview', 'Zd\Tickets::previewConf');
        $routes->get('confPdf', 'Zd\Mailing::pdfConf2');
    });
    $routes->group('object', function($routes){
        $routes->get('show', 'Zd\Objects::index');
    });
    $routes->group('user', function($routes){
        $routes->get('get/(:any)', 'Zd\Users::showUser/$1');
    });
    $routes->group('whats', function($routes){
        $routes->get('slaJob', 'Zd\Tickets::whatsVipSlaJob');
        $routes->get('listConv/(:any)', 'Zd\Tickets::whatsConv/$1');
        $routes->post('send/(:any)', 'Zd\Tickets::sendMsgToConv/$1');
        $routes->get('listConvs/(:any)', 'Zd\Whatsapp::listConversations/$1');
        $routes->get('getConversation/(:any)', 'Zd\Whatsapp::getConversation/$1');
        $routes->get('getActiveConversation/(:any)', 'Zd\Whatsapp::getActiveConversation/$1');
        $routes->post('notify', 'Zd\Whatsapp::sendNotification');
        $routes->post('test', 'Zd\Whatsapp::test');
        $routes->post('conv_id', 'Zd\Tickets::convid');
    });
    $routes->group('ticket', function($routes){
        $routes->get('search/(:any)', 'Zd\Tickets::searchQuery/$1');
        $routes->get('show', 'Zd\Tickets::index');
        $routes->get('show/(:any)', 'Zd\Tickets::index/$1');
        $routes->get('metrics/(:any)', 'Zd\Tickets::metrics/$1');
        $routes->get('adhForm/(:any)', 'Zd\Tickets::adhFormToClient/$1');
        $routes->get('audits/(:any)', 'Zd\Tickets::audits/$1');
        $routes->get('fields', 'Zd\Tickets::showFields');
        $routes->delete('closeTicket/(:any)', 'Zd\Tickets::closeTicket/$1');
    });
    $routes->post('webhook', 'Zd\Tickets::webhook');
    $routes->group('apps', function($routes){
        $routes->post('key', 'Zd\Tickets::getPublicKey');
        $routes->post('key/(:num)', 'Zd\Tickets::getPublicKey/$1');
        $routes->post('installations', 'Zd\Tickets::installations');
        $routes->post('aud', 'Zd\Tickets::appAud');
    });
    $routes->group('config', function($routes){
        $routes->get('showForm/(:num)', 'Zd\Tickets::showForm/$1');
        $routes->get('supportAddress', 'Zd\Config::supportAddress');
    });
    $routes->group('forms', function($routes){
        $routes->post('post-msg', 'Zd\Tickets::fromContactForm');
    });
    $routes->group('transpo', function($routes){
        $routes->get('searchFolio/(:any)', 'Transpo\TransportacionController::findByFolio/$1');
    });
});

$routes->group('view', function($routes){
    $routes->group('reports', function($routes){
        $routes->get('uk', 'Reports\Promos::promoUk/true');
        $routes->post('uk', 'Reports\Promos::promoUk/true');
    });
});


// Upload CSV
$routes->group('rsv', function($routes){
    $routes->get('search', 'Rsv\Manager::index');
    $routes->post('search', 'Rsv\Manager::search');
    $routes->post('quote', 'Rsv\Quote::index');
    $routes->get('discountcodes', 'Rsv\DiscountCodes::index');
    $routes->get('dbtest', 'Dbtest::index');
    $routes->get('viewConf', 'Dbtest::conf');

});

// Upload CSV
$routes->group('csv', function($routes){
    $routes->get('upload', 'CsvUpload::upload');
    $routes->post('upload', 'CsvUpload::upload');
});

// Rutas de logueo
$routes->group('login', function($routes){
    $routes->get('/', 'Log\Login::showLogin');
    $routes->post('/', 'Log\Login::login', ['as' => 'login.login']);
    $routes->get('out', 'Log\Login::logout', ['as' => 'login.logout']);
    $routes->get('show', 'Log\Login::show', ['as' => 'login.show']);
    $routes->get('perm', 'Log\Login::showSessionData');
});

// Rutas Mailing
$routes->group('mailing', function($routes){
    $routes->post('buzon-sugerencias', 'Mailing\FormController::buzonSugerencias');
    $routes->post('test', 'Mailing\FormController::sendTestEmail');
});

// Rutas protegidas por BearerToken
$routes->group('auth', function($routes){
    $routes->group('log', ['filter' => 'verificarPermiso:dash_logs'], function($routes){
        $routes->get('test', 'Test::index', ['as' => 'log.test.index']);
        $routes->get('show', 'Log\Login::show', ['as' => 'log.auth.show']);
    });
    $routes->get('test', 'Test::index', ['as' => 'test.index']);
    $routes->get('show', 'Log\Login::show', ['as' => 'auth.show']);
});

// Rutas sin proteccion por BearerToken
$routes->group('test', function($routes){
    $routes->post('db', 'Test::dbs', ['as' => 'test.test.dbs']);
    $routes->get('test', 'Test::index', ['as' => 'test.test.index']);
    $routes->get('jwt', 'Test::jwt', ['as' => 'test.test.jwt']);
    $routes->get('show', 'Log\Login::show', ['as' => 'test.auth.show']);
    $routes->get('cancel', 'Transpo\DatabaseController::testCancel');
});

$routes->group('pms', function($routes){
    $routes->get('searchFolio/(:any)', 'Pms\PmsController::getRsvDetail/$1');
});

// Este archivo contiene rutas actualizadas hasta el 2025-07-02
