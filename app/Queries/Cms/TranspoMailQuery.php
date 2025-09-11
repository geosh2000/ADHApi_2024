<?php

namespace App\Queries\Cms;

class TranspoMailQuery
{
    public static function confirmation()
    {
        return <<<'GRAPHQL'
        query Mails($filters: MailFiltersInput, $status: PublicationStatus) {
            mails(filters: $filters, status: $status) {
                slug
                name
                subject
                css
                variables
                locale
                footer {
                    text
                }
                body {
                    ... on ComponentSharedRichText {
                        name
                        section
                        sort
                        body
                        class
                        css
                        wrapper
                        type
                    }
                    ... on ComponentSharedButtons {
                        name
                        sort
                        section
                        label
                        button_id
                        url
                        class
                        css
                        type
                    }
                    ... on ComponentSharedMedia {
                        name
                        section
                        sort
                        class
                        css
                        type
                        file {
                            alternativeText
                            url
                            previewUrl
                        }
                    }
                    ... on ComponentSharedHtml {
                        name
                        section
                        sort
                        html
                        type
                    }
                    ... on ComponentSharedPlainText {
                        name
                        section
                        sort
                        text
                        class
                        css
                        type
                    }
                    ... on Error {
                        code
                        message
                    }
                }
                header {
                    id
                    title
                    bg_color
                }
                site {
                    name
                    slug
                    domain_us
                    privacy_policy_en
                    domain_mx
                    privacy_policy_es
                    global_css
                    contact_fields {
                        phone_us
                        phone_mx
                        phone_rest
                        contact_mail
                    }
                }
            }
        }
        GRAPHQL;
    }
}