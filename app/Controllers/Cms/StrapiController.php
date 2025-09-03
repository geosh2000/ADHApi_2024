<?php

namespace App\Controllers\Cms;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class StrapiController extends BaseController
{
    private $endpoint = 'https://strapi.grupobd.mx/graphql';

    public function getTranspoMailContent( $lang )
    {

        $lan = $lang == 'eng' ? 'en' : 'es';
        $query = <<<GQL
query TranspoMail(\$status: PublicationStatus, \$mailFilters: MailFiltersInput, \$siteFilters: SiteFiltersInput, \$locale: I18NLocaleCode) {
  sites(status: \$status, filters:  \$siteFilters) {
    documentId
    name
    slug
    contact_fields {
      phone_mx
      phone_rest
      phone_us
      privacy_policy_link
      contact_mail
    }
  }
  mails(status: \$status, filters: \$mailFilters, locale: \$locale) {
    css
    header {
      image {
        url
      }
      title
      bg_color
    }
    body {
      ... on ComponentSharedRichText {
        name
        sort
        body
        type
      }
      ... on ComponentSharedButtons {
        name
        sort
        label
        button_id
        url
        class
        css
        type
      }
      ... on ComponentSharedMedia {
        file {
          alternativeText
          height
          size
          width
          url
          previewUrl
          name
          formats
        }
      }
      ... on ComponentSharedHtml {
        name
        sort
        html
        type
      }
    }
    footer {
      text
    }
    variables
  }
}
GQL;

        $variables = [
            "status" => "PUBLISHED",
            "mailFilters" => [
                "slug" => ["startsWith" => "cc-transpo-confirmation"]
            ],
            "siteFilters" => [
                "slug" => ["eq" => "atelier-de-hoteles-contact-center"]
            ],
            "locale" => $lan
        ];

        $payload = json_encode([
            'query' => $query,
            'variables' => $variables
        ]);

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                                  ->setJSON(['error' => $err]);
        }

        $data = json_decode($response, true);

        return $data['data'] ?? null;
    }
}