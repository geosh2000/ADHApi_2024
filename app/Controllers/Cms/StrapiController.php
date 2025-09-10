<?php

namespace App\Controllers\Cms;

use App\Controllers\BaseController;
use App\Services\Cms\StrapiService;
use App\Queries\Cms\TranspoMailQuery;
use CodeIgniter\HTTP\ResponseInterface;

class StrapiController extends BaseController
{
    protected $strapi;

    public function __construct()
    {
        $this->strapi = new StrapiService();
    }

    public function getTranspoMailContent($lang)
    {
        $lan = $lang == 'eng' ? 'en' : 'es';

        $variables = [
            "filters" => [
                "site" => [
                    "slug" => ["eq" => "contact-center"]
                ],
                "slug" => [
                    "contains" => "cc-transpo-conf"
                ],
                "locale" => [
                    "eq" => $lan
                ]
            ]
        ];

        $query = TranspoMailQuery::confirmation();
        $result = $this->strapi->query($query, $variables);
        

        if (isset($result['error'])) {
            return $this->response->setStatusCode($result['status'])
                                  ->setJSON(['error' => $result['error']]);
        }

        return $result['data'] ?? null;
    }
}