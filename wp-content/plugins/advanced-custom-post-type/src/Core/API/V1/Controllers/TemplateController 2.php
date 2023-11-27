<?php

namespace ACPT\Core\API\V1\Controllers;

use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\Template\TemplateModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Repository\TemplateRepository;
use ACPT\Costants\MetaTypes;

class TemplateController extends AbstractController
{
    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed
     */
    public function store(\WP_REST_Request $request)
    {
        try {
            $templateModel = $this->getSavedTemplateModel($request['belongsTo'], $request['template'], $request['data'], $request['pagesHtml'], $request['find'], $request['metaFieldId']);

            TemplateRepository::save($templateModel);

            return $this->jsonResponse([
                'success' => true
            ]);

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

    /**
     * @param $belongsTo
     * @param      $template
     * @param      $json
     * @param      $html
     * @param null $find
     * @param null $metaFieldId
     *
     * @return TemplateModel|null
     * @throws \Exception
     */
    private function getSavedTemplateModel($belongsTo, $template, $json, $html, $find = null, $metaFieldId = null)
    {
        $templateModel = TemplateRepository::get($belongsTo, $template, $find, $metaFieldId);

        if(!$templateModel){
            $id = Uuid::v4();

            return new TemplateModel(
                $id,
                $belongsTo,
                $template,
                json_encode($json),
                json_encode($html),
                $find,
                [],
                $metaFieldId
            );
        }

        $templateModel->modify(
            $belongsTo,
            $template,
            json_encode($json),
            json_encode($html),
            $find,
            [],
            $metaFieldId
        );

        return $templateModel;
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed
     */
    public function load(\WP_REST_Request $request)
    {
        $template = $request['template'];
        $belongsTo = $request['belongsTo'];
        $find = isset($request['find']) ? $request['find'] : null;
        $metaFieldId = isset($request['metaFieldId']) ? $request['metaFieldId'] : null;

        try {
            $templateModel = TemplateRepository::get($belongsTo, $template, $find, $metaFieldId);

            if($templateModel === null){
                return $this->jsonResponse([]);
            }

            $data = json_decode($templateModel->getJson());

            return $this->jsonResponse($data);

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function block(\WP_REST_Request $request)
    {
        $template = $request['template'];
        $belongsTo = $request['belongsTo'];
        $find = isset($request['find']) ? $request['find'] : null;

        $blocks = array_merge(
            $this->basicBlocks(),
            $this->WordpressBlocks($template, $belongsTo),
            $this->WooCommerceBlocks($template, $belongsTo, $find),
            $this->templateBlocks(),
            $this->ACPTCustomHooks(),
            $this->ACPTMetaFieldBlocks($belongsTo, $find)
        );

        return $this->jsonResponse($blocks);
    }

    /**
     * @return array
     */
    private function basicBlocks()
    {
        $divIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M22 7.999a1 1 0 0 0-.516-.874l-9.022-5a1.003 1.003 0 0 0-.968 0l-8.978 4.96a1 1 0 0 0-.003 1.748l9.022 5.04a.995.995 0 0 0 .973.001l8.978-5A1 1 0 0 0 22 7.999zm-9.977 3.855L5.06 7.965l6.917-3.822l6.964 3.859l-6.918 3.852z"/><path fill="currentColor" d="M20.515 11.126L12 15.856l-8.515-4.73l-.971 1.748l9 5a1 1 0 0 0 .971 0l9-5l-.97-1.748z"/><path fill="currentColor" d="M20.515 15.126L12 19.856l-8.515-4.73l-.971 1.748l9 5a1 1 0 0 0 .971 0l9-5l-.97-1.748z"/></svg>';
        $category = "Basic";

        return [
            [
                'id' => 'div',
                'icon' => $divIcon,
                'label' => 'Div',
                'category' => $category,
                'content' => [
                    'type' => 'div',
                ],
            ],
        ];
    }

    /**
     * @param string $template
     *
     * @return array
     */
    private function WordpressBlocks($template, $belongsTo)
    {
        $wordpressIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M19.891 7.788a8.966 8.966 0 0 1 1.099 4.313a8.986 8.986 0 0 1-4.47 7.771l2.746-7.939c.513-1.282.684-2.309.684-3.219a7.165 7.165 0 0 0-.059-.926m-6.651.087a14.14 14.14 0 0 0 1.026-.088c.485-.063.428-.775-.056-.749c0 0-1.463.112-2.4.112c-.887 0-2.375-.125-2.375-.125c-.487-.024-.55.713-.061.738c0 0 .449.052.938.075l1.399 3.838l-1.975 5.899l-3.274-9.724a17.006 17.006 0 0 0 1.028-.083c.487-.063.43-.775-.055-.747c0 0-1.455.115-2.395.115c-.167 0-.365-.007-.575-.013C6.093 4.726 8.862 3.113 12 3.113c2.341 0 4.471.894 6.071 2.36c-.038-.002-.076-.008-.117-.008c-.883 0-1.51.77-1.51 1.596c0 .741.427 1.369.883 2.108c.343.601.742 1.37.742 2.481c0 .763-.295 1.662-.685 2.899l-.896 2.987l-3.25-9.675l.002.014zM12 21.087a8.983 8.983 0 0 1-2.54-.364l2.697-7.838l2.763 7.572c.021.044.042.085.065.124a9.016 9.016 0 0 1-2.985.508m-8.99-8.988a8.94 8.94 0 0 1 .778-3.658l4.287 11.749a8.993 8.993 0 0 1-5.065-8.091m8.99-10c-5.513 0-10 4.487-10 10s4.487 10 10 10s10-4.487 10-10s-4.487-10-10-10"/></svg>';
        $category = "Wordpress";

        $blocks = [
            [
                'id' => 'wp_title',
                'icon' => $wordpressIcon,
                'label' => 'WP Title',
                'category' => $category,
                'content' => [
                    'type' => 'wp-el',
                    'content' => '{{wp_title}}'
                ],
            ],
            [
                'id' => 'wp_content',
                'icon' => $wordpressIcon,
                'label' => 'WP Content',
                'category' => $category,
                'content' => [
                    'type' => 'wp-el',
                    'content' => '{{wp_content}}'
                ],
            ],
            [
                'id' => 'wp_thumbnail',
                'icon' => $wordpressIcon,
                'label' => 'WP Thumbnail',
                'category' => $category,
                'content' => [
                    'type' => 'wp-thumbnail',
                    'content' => '{{wp_thumbnail}}'
                ],
            ],
            [
                'id' => 'wp_excerpt',
                'icon' => $wordpressIcon,
                'label' => 'WP Excerpt',
                'category' => $category,
                'content' => [
                    'type' => 'wp-el',
                    'content' => '{{wp_excerpt}}'
                ],
            ],
            [
                'id' => 'wp_author',
                'icon' => $wordpressIcon,
                'label' => 'WP Author',
                'category' => $category,
                'content' => [
                    'type' => 'wp-el',
                    'content' => '{{wp_author}}'
                ],
            ],
            [
                'id' => 'wp_date',
                'icon' => $wordpressIcon,
                'label' => 'WP Date',
                'category' => $category,
                'content' => [
                    'type' => 'wp-date',
                    'content' => '{{wp_date}}'
                ],
            ],
            [
                'id' => 'wp_taxonomy',
                'icon' => $wordpressIcon,
                'label' => 'WP Taxonomy',
                'category' => $category,
                'content' => [
                    'type' => 'wp-el',
                    'content' => '{{wp_taxonomy}}'
                ],
            ],
	        [
		        'id' => 'wp_term_name',
		        'icon' => $wordpressIcon,
		        'label' => 'WP Term name',
		        'category' => $category,
		        'content' => [
			        'type' => 'wp-el',
			        'content' => '{{wp_term_name}}'
		        ],
	        ],
	        [
		        'id' => 'wp_term_description',
		        'icon' => $wordpressIcon,
		        'label' => 'WP Term description',
		        'category' => $category,
		        'content' => [
			        'type' => 'wp-el',
			        'content' => '{{wp_term_description}}'
		        ],
	        ],
        ];

        if(($belongsTo === MetaTypes::CUSTOM_POST_TYPE and $template === 'archive') or ($belongsTo === MetaTypes::TAXONOMY and $template === 'single')){
            $blocks[] = [
                'id' => 'wp_permalink',
                'icon' => $wordpressIcon,
                'label' => 'WP Permalink',
                'category' => $category,
                'content' => [
                    'type' => 'wp-permalink',
                    'content' => '{{wp_permalink}}'
                ],
            ];
        }

        if($template === 'single' or $template === 'related'){
            $blocks[] = [
                'id' => 'wp_navigation_links',
                'icon' => $wordpressIcon,
                'label' => 'WP Prev/Next links',
                'category' => $category,
                'content' => [
                    'type' => 'wp-el',
                    'content' => '{{wp_navigation_links}}'
                ],
            ];
        }

        return $blocks;
    }

    /**
     * @param string $template
     * @param $belongsTo
     * @param $find
     * @return array
     */
    private function WooCommerceBlocks( $template, $belongsTo, $find )
    {
        if ( $find === 'product'
                and $belongsTo === MetaTypes::CUSTOM_POST_TYPE
                and $template === 'single'
                and in_array( 'woocommerce/woocommerce.php',  get_option( 'active_plugins' )  )
        ){
            $wooCommerceIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 153"><path fill="currentColor" fill-rule="evenodd" d="M232.138 0H23.759C10.572 0-.103 10.78.001 23.862v79.542c0 13.187 10.676 23.863 23.863 23.863h98.694l45.11 25.118l-10.258-25.118h74.728c13.187 0 23.862-10.676 23.862-23.863V23.862C256 10.675 245.325 0 232.138 0ZM19.364 18.42c-2.93.21-5.128 1.256-6.594 3.245c-1.465 1.883-1.988 4.29-1.674 7.012c6.175 39.248 11.931 65.726 17.269 79.437c2.093 5.023 4.5 7.431 7.326 7.222c4.396-.315 9.629-6.385 15.804-18.211c3.244-6.699 8.268-16.746 15.07-30.143c5.652 19.781 13.397 34.643 23.13 44.586c2.722 2.825 5.548 4.081 8.268 3.872c2.408-.21 4.292-1.465 5.548-3.768c1.046-1.988 1.465-4.291 1.256-6.907c-.628-9.524.314-22.816 2.93-39.876c2.721-17.583 6.07-30.247 10.152-37.782c.837-1.57 1.151-3.14 1.047-5.024c-.21-2.407-1.256-4.395-3.245-5.965c-1.988-1.57-4.186-2.303-6.593-2.094c-3.035.21-5.338 1.675-6.908 4.605c-6.489 11.827-11.094 30.98-13.815 57.563C84.358 66.145 81.01 54.32 78.392 40.4c-1.15-6.175-3.977-9.106-8.582-8.792c-3.14.21-5.756 2.303-7.85 6.28L39.04 81.53c-3.768-15.176-7.326-33.7-10.57-55.574c-.733-5.443-3.768-7.955-9.106-7.536Zm201.68 7.536c7.431 1.57 12.978 5.547 16.746 12.14c3.349 5.652 5.023 12.455 5.023 20.619c0 10.78-2.72 20.618-8.163 29.618c-6.28 10.467-14.443 15.7-24.595 15.7c-1.78 0-3.663-.21-5.652-.629c-7.43-1.57-12.978-5.546-16.746-12.14c-3.349-5.756-5.023-12.664-5.023-20.723c0-10.78 2.721-20.618 8.163-29.514c6.385-10.466 14.548-15.699 24.596-15.699c1.779 0 3.663.21 5.651.628Zm-4.395 56.62c3.872-3.453 6.488-8.581 7.954-15.489c.418-2.407.732-5.023.732-7.744c0-3.036-.628-6.28-1.884-9.525c-1.57-4.081-3.663-6.28-6.175-6.802c-3.767-.733-7.43 1.36-10.884 6.489c-2.826 3.977-4.606 8.163-5.547 12.454c-.524 2.407-.733 5.024-.733 7.64c0 3.035.628 6.28 1.884 9.524c1.57 4.082 3.663 6.28 6.175 6.803c2.616.523 5.442-.628 8.478-3.35Zm-44.481-44.48c-3.768-6.593-9.42-10.57-16.746-12.14c-1.989-.419-3.872-.628-5.652-.628c-10.047 0-18.21 5.233-24.595 15.7c-5.443 8.895-8.163 18.733-8.163 29.513c0 8.06 1.674 14.967 5.023 20.723c3.768 6.594 9.315 10.57 16.746 12.14c1.988.419 3.872.628 5.652.628c10.152 0 18.315-5.232 24.595-15.699c5.442-9 8.163-18.839 8.163-29.618c0-8.164-1.675-14.967-5.023-20.618ZM158.98 67.088c-1.465 6.908-4.082 12.036-7.954 15.49c-3.035 2.721-5.86 3.872-8.477 3.35c-2.512-.524-4.606-2.722-6.175-6.804c-1.256-3.244-1.884-6.489-1.884-9.524c0-2.616.209-5.233.733-7.64c.941-4.291 2.72-8.477 5.546-12.454c3.455-5.129 7.118-7.222 10.885-6.49c2.512.524 4.605 2.722 6.175 6.803c1.256 3.245 1.884 6.49 1.884 9.525c0 2.72-.21 5.337-.733 7.744Z"/></svg>';
            $category = "WooCommerce positions";

            $positions = [
                [
                    'id' => 'wc_before_main_content',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC before main content',
                    'category' => $category,
                    'content' => [
                        'type' => 'wp-before-main-content',
                    ],
                ],
                [
                    'id' => 'wc_before_product_summary',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC before product summary',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-before-product-summary',
                    ],
                ],
                [
                    'id' => 'wc_product_summary',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product summary',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-product-summary',
                    ],
                ],
                [
                    'id' => 'wc_after_product_summary',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC after product summary',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-after-product-summary',
                    ],
                ],
            ];

            $category = "WooCommerce hooks";

            $hooks = [
                [
                    'id' => 'wc_breadcrumb',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC breadcrumb',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_breadcrumb}}'
                    ],
                ],
                [
                    'id' => 'wc_sale_flash',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC sale flash',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_sale_flash}}'
                    ],
                ],
                [
                    'id' => 'wc_product_images',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product images',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_product_images}}'
                    ],
                ],
                [
                    'id' => 'wc_product_thumbnails',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product thumbnails',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_product_thumbnails}}'
                    ],
                ],
                [
                    'id' => 'wc_product_title',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product title',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_product_title}}'
                    ],
                ],
                [
                    'id' => 'wc_product_sku',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product sku',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_product_sku}}'
                    ],
                ],
                [
                    'id' => 'wc_product_rating',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product rating',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_product_rating}}'
                    ],
                ],
                [
                    'id' => 'wc_product_price',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product price',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_product_price}}'
                    ],
                ],
                [
                    'id' => 'wc_product_excerpt',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product excerpt',
                    'category' => $category,
                    'content' => [
                            'type' => 'wc-el',
                            'content' => '{{wc_product_excerpt}}'
                    ],
                ],
                [
                    'id' => 'wc_add_to_cart',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC add to cart',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_add_to_cart}}'
                    ],
                ],
                [
                    'id' => 'wc_product_meta',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product meta',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_product_meta}}'
                    ],
                ],
                [
                    'id' => 'wc_sharing',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC sharing',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_sharing}}'
                    ],
                ],
                [
                    'id' => 'wc_product_data_tabs',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product data tabs',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_product_data_tabs}}'
                    ],
                ],
                [
                    'id' => 'wc_product_upsell',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product upsell',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_product_upsell}}'
                    ],
                ],
                [
                    'id' => 'wc_product_related',
                    'icon' => $wooCommerceIcon,
                    'label' => 'WC product related',
                    'category' => $category,
                    'content' => [
                        'type' => 'wc-el',
                        'content' => '{{wc_product_related}}'
                    ],
                ],
            ];

            return array_merge($positions, $hooks);
        }

        return [];

    }

    /**
     * @return array
     */
    private function templateBlocks()
    {
        $headerIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M21 3a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h18zm-1 2H4v14h16V5zm-2 2v2H6V7h12z"/></svg>';
        $footerIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M21 3a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h18zm-1 2H4v14h16V5zm-2 10v2H6v-2h12z"/></svg>';
        $templateIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M16.65 12.575q-.2 0-.375-.063q-.175-.062-.325-.212L11.7 8.05q-.15-.15-.212-.325q-.063-.175-.063-.375t.063-.375q.062-.175.212-.325l4.25-4.25q.15-.15.325-.212q.175-.063.375-.063t.375.063q.175.062.325.212l4.25 4.25q.15.15.212.325q.063.175.063.375t-.063.375q-.062.175-.212.325l-4.25 4.25q-.15.15-.325.212q-.175.063-.375.063ZM4 11q-.425 0-.712-.288Q3 10.425 3 10V4q0-.425.288-.713Q3.575 3 4 3h6q.425 0 .713.287Q11 3.575 11 4v6q0 .425-.287.712Q10.425 11 10 11Zm10 10q-.425 0-.712-.288Q13 20.425 13 20v-6q0-.425.288-.713Q13.575 13 14 13h6q.425 0 .712.287q.288.288.288.713v6q0 .425-.288.712Q20.425 21 20 21ZM4 21q-.425 0-.712-.288Q3 20.425 3 20v-6q0-.425.288-.713Q3.575 13 4 13h6q.425 0 .713.287q.287.288.287.713v6q0 .425-.287.712Q10.425 21 10 21ZM5 9h4V5H5Zm11.675 1.2L19.5 7.375L16.675 4.55L13.85 7.375ZM15 19h4v-4h-4ZM5 19h4v-4H5ZM9 9Zm4.85-1.625ZM9 15Zm6 0Z"/></svg>';
        $category = "Theme areas";
        $blocks = [];

        // Headers and footers
        $templateDir = get_template_directory();
        $scanDir = scandir($templateDir);

        foreach ($scanDir as $file){

            if($file === 'header.php'){
                $blocks[] = [
                    'id' => 'header',
                    'icon' => $headerIcon,
                    'label' => "Header",
                    'category' => $category,
                    'content' => [
                        'type' => 'theme-el',
                        'content' => '{{header}}',
                    ]
                ];
            }

            if($file === 'footer.php'){
                $blocks[] = [
                    'id' => 'footer',
                    'icon' => $footerIcon,
                    'label' => "Footer",
                    'category' => $category,
                    'content' => [
                        'type' => 'theme-el',
                        'content' => '{{footer}}',
                    ]
                ];
            }

            preg_match('/header-([a-z]+).php/', $file, $headers);
            preg_match('/footer-([a-z]+).php/', $file, $footers);

            if(!empty($headers[0])){

                $header = $headers[1];

                $blocks[] = [
                    'id' => 'header-'. $header,
                    'icon' => $headerIcon,
                    'label' => "Header " . $header,
                    'category' => $category,
                    'content' => [
                        'type' => 'theme-el',
                        'content' => '{{header name="'.$header.'"}}',
                    ]
                ];
            }

            if(!empty($footers[0])){

                $footer = $footers[1];

                $blocks[] = [
                    'id' => 'footer-'. $footer,
                    'icon' => $footerIcon,
                    'label' => "Footer " . $footer,
                    'category' => $category,
                    'content' => [
                        'type' => 'theme-el',
                        'content' => '{{footer name="'.$footer.'"}}',
                    ]
                ];
            }
        }

        // Sidebars
        global $wp_registered_sidebars;

        foreach ($wp_registered_sidebars as $sidebar){
            $blocks[] = [
                    'id' => $sidebar['id'],
                    'icon' => $templateIcon,
                    'label' => $sidebar['name'],
                    'category' => $category,
                    'content' => [
                            'type' => 'theme-el',
                            'content' => '{{template_part="'.$sidebar['id'].'"}}',
                    ]
            ];
        }

        return $blocks;
    }

    /**
     * @return array
     */
    private function ACPTCustomHooks()
    {
         $category = "ACPT custom hooks";

        return [
            [
                'id' => 'acpt_breadcrumbs',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 48 48"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4"><path stroke-linejoin="round" d="M4 32.083c0-1.202.266-2.395.971-3.368C7.045 25.85 12.671 20 24 20c11.33 0 16.955 5.851 19.029 8.715c.705.973.971 2.166.971 3.368A7.917 7.917 0 0 1 36.083 40H11.917A7.917 7.917 0 0 1 4 32.083Z"/><path d="M12 9v4m2 9v4M36 9v4m-2 9v4M24 7v6m0 7v8m16-2.557C36.906 22.78 31.808 20 24 20s-12.906 2.779-16 5.443"/></g></svg>',
                'label' => 'ACPT breadcrumbs',
                'category' => $category,
                'content' => [
                    'type' => 'wp-breadcrumb',
                    'content' => '{{acpt_breadcrumbs}}',
                ],
            ],
	        [
		        'id' => 'acpt_loop',
		        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M17 7c-2.094 0-3.611 1.567-5.001 3.346C10.609 8.567 9.093 7 7 7c-2.757 0-5 2.243-5 5a4.98 4.98 0 0 0 1.459 3.534A4.956 4.956 0 0 0 6.99 17h.012c2.089-.005 3.605-1.572 4.996-3.351C13.389 15.431 14.906 17 17 17c2.757 0 5-2.243 5-5s-2.243-5-5-5zM6.998 15l-.008 1v-1c-.799 0-1.55-.312-2.114-.878A3.004 3.004 0 0 1 7 9c1.33 0 2.56 1.438 3.746 2.998C9.558 13.557 8.328 14.997 6.998 15zM17 15c-1.33 0-2.561-1.44-3.749-3.002C14.438 10.438 15.668 9 17 9c1.654 0 3 1.346 3 3s-1.346 3-3 3z"/></svg>',
		      //  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 48 48"><path fill="currentColor" d="M22.26 23.795a5.273 5.273 0 0 1-5.268-5.26c-.002-.215.013-2.436 1.475-4.689a9.178 9.178 0 0 1 2.023-2.214c-1.273-.416-2.772-.625-4.49-.625c-1.683 0-3.16.225-4.421.673a9.177 9.177 0 0 1 1.952 2.158c1.461 2.25 1.48 4.47 1.477 4.687v.003a5.274 5.274 0 0 1-5.268 5.267a5.274 5.274 0 0 1-5.267-5.264c0-.207.027-3.708 2.73-6.432a9.01 9.01 0 0 1 .36-.344c-1.32-.451-2.858-.705-4.593-.756A1 1 0 0 1 3.03 9c2.521.074 4.703.544 6.492 1.397c1.766-.923 3.933-1.39 6.478-1.39c2.622 0 4.833.447 6.612 1.334c1.763-.812 3.9-1.26 6.358-1.333H29a1 1 0 0 1 .03 1.999c-1.651.048-3.124.28-4.398.69c.203.182.397.372.582.572c2.425 2.615 2.336 5.849 2.312 6.283a5.273 5.273 0 0 1-5.267 5.243Zm.307-11.171a7.428 7.428 0 0 0-2.37 2.231c-1.21 1.812-1.206 3.64-1.205 3.658v.015a3.271 3.271 0 0 0 3.267 3.267a3.271 3.271 0 0 0 3.268-3.267l.003-.083c.001-.023.159-2.741-1.801-4.837a6.741 6.741 0 0 0-1.162-.984Zm-12.993.08c-.343.243-.66.51-.951.804c-2.147 2.164-2.15 4.984-2.15 5.013v.008a3.271 3.271 0 0 0 3.267 3.267a3.271 3.271 0 0 0 3.268-3.267v-.027c0-.013.003-1.842-1.208-3.653a7.366 7.366 0 0 0-2.226-2.144Z"/></svg>',
		        'label' => 'ACPT loop',
		        'category' => $category,
		        'content' => [
			        'type' => 'acpt-loop',
			        'content' => '{{acpt_loop}}',
		        ],
	        ]
        ];
    }

    /**
     * @param $belongsTo
     * @param null $find
     *
     * @return array
     * @throws \Exception
     */
    private function ACPTMetaFieldBlocks($belongsTo, $find = null)
    {
	    $category = "ACPT meta fields";
        $shortcode = 'acpt';
        $meta = [];

        switch ($belongsTo){
            case MetaTypes::CUSTOM_POST_TYPE:
                $meta = MetaRepository::get([
                    'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                    'find' => $find,
                ]);
                $category = "ACPT post meta fields";
                $shortcode = 'acpt';
                break;

            case MetaTypes::TAXONOMY:
                $meta = MetaRepository::get([
                    'belongsTo' => MetaTypes::TAXONOMY,
                    'find' => $find,
                ]);
                $category = "ACPT taxonomy meta fields";
                $shortcode = 'acpt_tax';
                break;

            case MetaTypes::USER:
                $meta = MetaRepository::get([
                    'belongsTo' => MetaTypes::USER,
                ]);
                $category = "ACPT user meta fields";
                $shortcode = 'acpt_user';
                break;
        }

        $blocks = [];

        $this->addACPTBlocks($meta, $category, $shortcode, $blocks);

        // add options meta
	    $meta = MetaRepository::get([
		    'belongsTo' => MetaTypes::OPTION_PAGE,
	    ]);
	    $shortcode = 'acpt_option';
	    $category = "ACPT options page meta fields";

	    $this->addACPTBlocks($meta, $category, $shortcode, $blocks);

        return $blocks;
    }

	/**
	 * @param AbstractMetaBoxModel[] $meta
	 * @param string $category
	 * @param string $shortcode
	 * @param array $blocks
	 */
	private function addACPTBlocks($meta, $category, $shortcode, &$blocks)
	{
		foreach ($meta as $boxModel){
			foreach ($boxModel->getFields() as $fieldModel){
				if($fieldModel->getType() === CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE){
					foreach ($fieldModel->getChildren() as $childFieldModel){
						$blocks[] = [
							'id' => $childFieldModel->getId(),
							'icon' => $this->metaFieldIcon($childFieldModel->getType()),
							'label' => '['.$this->parentFieldLabel($fieldModel) . '] ' . $childFieldModel->getUiName(),
							'category' => $category,
							'content' => [
								'type' => 'acpt-meta',
								'content' => '{{'.$shortcode.' box="'.$childFieldModel->getMetaBox()->getName().'" field="'.$childFieldModel->getName().'" parent="'.$fieldModel->getName().'"}}',
							],
						];
					}
				} elseif($fieldModel->getType() === CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
					foreach ($fieldModel->getBlocks() as $blockModel){
						foreach ($blockModel->getFields() as $nestedFieldModel){
							$blocks[] = [
								'id' => $nestedFieldModel->getId(),
								'icon' => $this->metaFieldIcon($nestedFieldModel->getType()),
								'label' => '['.$this->parentFieldLabel($fieldModel) . '] ' . $blockModel->getUiName() . ' - ' . $nestedFieldModel->getName(),
								'category' => $category,
								'content' => [
									'type' => 'acpt-meta',
									'content' => '{{'.$shortcode.' box="'.$nestedFieldModel->getMetaBox()->getName().'" field="'.$nestedFieldModel->getName().'" block_name="'.$blockModel->getName().'" parent="'.$fieldModel->getName().'"}}',
								],
							];
						}
					}
				} else {
					$blocks[] = [
						'id' => $fieldModel->getId(),
						'icon' => $this->metaFieldIcon($fieldModel->getType()),
						'label' => '['.$this->parentFieldLabel($fieldModel) . '] ' . $fieldModel->getUiName(),
						'category' => $category,
						'content' => [
							'type' => 'acpt-meta',
							'content' => '{{'.$shortcode.' box="'.$fieldModel->getMetaBox()->getName().'" field="'.$fieldModel->getName().'"}}',
						],
					];
				}
			}
		}
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string|null
	 */
	private function parentFieldLabel(AbstractMetaBoxFieldModel $fieldModel)
	{
		$baseLabel = null;

		switch ($fieldModel->getMetaBox()->metaType()){
			case MetaTypes::CUSTOM_POST_TYPE:
				$baseLabel = $fieldModel->getMetaBox()->getPostType();
				break;

			case MetaTypes::TAXONOMY:
				$baseLabel = $fieldModel->getMetaBox()->getTaxonomy();
				break;

			case MetaTypes::OPTION_PAGE:
				$baseLabel = $fieldModel->getMetaBox()->getOptionPage();
				break;

			case MetaTypes::USER:
				$baseLabel = 'USER';
				break;
		}

		return $baseLabel;
	}

    /**
     * @param $template
     *
     * @return string
     */
    private function metaFieldIcon($template)
    {
        switch ($template){

            case CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M12 14c2.206 0 4-1.794 4-4s-1.794-4-4-4s-4 1.794-4 4s1.794 4 4 4zm0-6c1.103 0 2 .897 2 2s-.897 2-2 2s-2-.897-2-2s.897-2 2-2z"/><path fill="currentColor" d="M11.42 21.814a.998.998 0 0 0 1.16 0C12.884 21.599 20.029 16.44 20 10c0-4.411-3.589-8-8-8S4 5.589 4 9.995c-.029 6.445 7.116 11.604 7.42 11.819zM12 4c3.309 0 6 2.691 6 6.005c.021 4.438-4.388 8.423-6 9.73c-1.611-1.308-6.021-5.294-6-9.735c0-3.309 2.691-6 6-6z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::COLOR_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M20 13.998c-.092.065-2 2.083-2 3.5c0 1.494.949 2.448 2 2.5c.906.044 2-.891 2-2.5c0-1.5-1.908-3.435-2-3.5zm-16.586-1c0 .534.208 1.036.586 1.414l5.586 5.586c.378.378.88.586 1.414.586s1.036-.208 1.414-.586l7-7l-.707-.707L11 4.584L8.707 2.291L7.293 3.705l2.293 2.293L4 11.584c-.378.378-.586.88-.586 1.414zM11 7.412l5.586 5.586L11 18.584h.001l-.001 1v-1l-5.586-5.586L11 7.412z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M13.464 6c1.43 0 2.779.613 3.799 1.726l1.475-1.352C17.334 4.843 15.461 4 13.464 4c-1.998 0-3.87.843-5.272 2.375A8.034 8.034 0 0 0 6.589 9H4v2h2.114c-.038.33-.064.663-.064 1s.026.67.064 1H4v2h2.589c.362.97.901 1.861 1.603 2.626C9.594 19.157 11.466 20 13.464 20c1.997 0 3.87-.843 5.273-2.374l-1.475-1.352C16.243 17.387 14.894 18 13.464 18s-2.778-.612-3.798-1.726A5.937 5.937 0 0 1 8.801 15H13v-2H8.139c-.05-.328-.089-.66-.089-1s.039-.672.089-1H13V9H8.801c.24-.457.516-.893.865-1.274C10.686 6.613 12.034 6 13.464 6z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::DATE_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M7 11h2v2H7zm0 4h2v2H7zm4-4h2v2h-2zm0 4h2v2h-2zm4-4h2v2h-2zm0 4h2v2h-2z"/><path fill="currentColor" d="M5 22h14c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2h-2V2h-2v2H9V2H7v2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2zM19 8l.001 12H5V8h14z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::EDITOR_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M5 18h14v3H5zm7.5-14h-1c-.401 0-.764.24-.921.609L5.745 16h2.173l1.273-3h5.604l1.268 3h2.171L13.421 4.61A1 1 0 0 0 12.5 4zm-2.46 7l1.959-4.616L13.95 11h-3.91z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223l-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044L20.002 18H4z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M5.282 12.064c-.428.328-.72.609-.875.851c-.155.24-.249.498-.279.768h2.679v-.748H5.413c.081-.081.152-.151.212-.201c.062-.05.182-.142.361-.27c.303-.218.511-.42.626-.604c.116-.186.173-.375.173-.578a.898.898 0 0 0-.151-.512a.892.892 0 0 0-.412-.341c-.174-.076-.419-.111-.733-.111c-.3 0-.537.038-.706.114a.889.889 0 0 0-.396.338c-.094.143-.159.346-.194.604l.894.076c.025-.188.074-.317.147-.394a.375.375 0 0 1 .279-.108c.11 0 .2.035.272.108a.344.344 0 0 1 .108.258a.55.55 0 0 1-.108.297c-.074.102-.241.254-.503.453zm.055 6.386a.398.398 0 0 1-.282-.105c-.074-.07-.128-.195-.162-.378L4 18.085c.059.204.142.372.251.506c.109.133.248.235.417.306c.168.069.399.103.692.103c.3 0 .541-.047.725-.14a1 1 0 0 0 .424-.403c.098-.175.146-.354.146-.544a.823.823 0 0 0-.088-.393a.708.708 0 0 0-.249-.261a1.015 1.015 0 0 0-.286-.11a.943.943 0 0 0 .345-.299a.673.673 0 0 0 .113-.383a.747.747 0 0 0-.281-.596c-.187-.159-.49-.238-.909-.238c-.365 0-.648.072-.847.219c-.2.143-.334.353-.404.626l.844.151c.023-.162.067-.274.133-.338s.151-.098.257-.098a.33.33 0 0 1 .241.089c.059.06.087.139.087.238c0 .104-.038.193-.117.27s-.177.112-.293.112a.907.907 0 0 1-.116-.011l-.045.649a1.13 1.13 0 0 1 .289-.056c.132 0 .237.041.313.126c.077.082.115.199.115.352c0 .146-.04.266-.119.354a.394.394 0 0 1-.301.134zm.948-10.083V5h-.739a1.47 1.47 0 0 1-.394.523c-.168.142-.404.262-.708.365v.754a2.595 2.595 0 0 0 .937-.48v2.206h.904zM9 6h11v2H9zm0 5h11v2H9zm0 5h11v2H9z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::PHONE_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M17.707 12.293a.999.999 0 0 0-1.414 0l-1.594 1.594c-.739-.22-2.118-.72-2.992-1.594s-1.374-2.253-1.594-2.992l1.594-1.594a.999.999 0 0 0 0-1.414l-4-4a.999.999 0 0 0-1.414 0L3.581 5.005c-.38.38-.594.902-.586 1.435c.023 1.424.4 6.37 4.298 10.268s8.844 4.274 10.269 4.298h.028c.528 0 1.027-.208 1.405-.586l2.712-2.712a.999.999 0 0 0 0-1.414l-4-4.001zm-.127 6.712c-1.248-.021-5.518-.356-8.873-3.712c-3.366-3.366-3.692-7.651-3.712-8.874L7 4.414L9.586 7L8.293 8.293a1 1 0 0 0-.272.912c.024.115.611 2.842 2.271 4.502s4.387 2.247 4.502 2.271a.991.991 0 0 0 .912-.271L17 14.414L19.586 17l-2.006 2.005z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::TEXT_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M5 8h2V6h3.252L7.68 18H5v2h8v-2h-2.252L13.32 6H17v2h2V4H5z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::TEXTAREA_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M18.404 2.998c-.757-.754-2.077-.751-2.828.005l-1.784 1.791L11.586 7H7a.998.998 0 0 0-.939.658l-4 11c-.133.365-.042.774.232 1.049l2 2a.997.997 0 0 0 1.049.232l11-4A.998.998 0 0 0 17 17v-4.586l2.207-2.207v-.001h.001L21 8.409c.378-.378.586-.881.585-1.415c0-.535-.209-1.038-.588-1.415l-2.593-2.581zm-3.111 8.295A.996.996 0 0 0 15 12v4.3l-9.249 3.363l4.671-4.671c.026.001.052.008.078.008A1.5 1.5 0 1 0 9 13.5c0 .026.007.052.008.078l-4.671 4.671L7.7 9H12c.266 0 .52-.105.707-.293L14.5 6.914L17.086 9.5l-1.793 1.793zm3.206-3.208l-2.586-2.586l1.079-1.084l2.593 2.581l-1.086 1.089z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::LIST_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M4 6h2v2H4zm0 5h2v2H4zm0 5h2v2H4zm16-8V6H8.023v2H18.8zM8 11h12v2H8zm0 5h12v2H8z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::HTML_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m7.375 16.781l1.25-1.562L4.601 12l4.024-3.219l-1.25-1.562l-5 4a1 1 0 0 0 0 1.562l5 4zm9.25-9.562l-1.25 1.562L19.399 12l-4.024 3.219l1.25 1.562l5-4a1 1 0 0 0 0-1.562l-5-4zm-1.649-4.003l-4 18l-1.953-.434l4-18z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::URL_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M8.465 11.293c1.133-1.133 3.109-1.133 4.242 0l.707.707l1.414-1.414l-.707-.707c-.943-.944-2.199-1.465-3.535-1.465s-2.592.521-3.535 1.465L4.929 12a5.008 5.008 0 0 0 0 7.071a4.983 4.983 0 0 0 3.535 1.462A4.982 4.982 0 0 0 12 19.071l.707-.707l-1.414-1.414l-.707.707a3.007 3.007 0 0 1-4.243 0a3.005 3.005 0 0 1 0-4.243l2.122-2.121z"/><path fill="currentColor" d="m12 4.929l-.707.707l1.414 1.414l.707-.707a3.007 3.007 0 0 1 4.243 0a3.005 3.005 0 0 1 0 4.243l-2.122 2.121c-1.133 1.133-3.109 1.133-4.242 0L10.586 12l-1.414 1.414l.707.707c.943.944 2.199 1.465 3.535 1.465s2.592-.521 3.535-1.465L19.071 12a5.008 5.008 0 0 0 0-7.071a5.006 5.006 0 0 0-7.071 0z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::SELECT_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M20 2H8c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zM8 16V4h12l.002 12H8z"/><path fill="currentColor" d="M4 8H2v12c0 1.103.897 2 2 2h12v-2H4V8zm8.933 3.519l-1.726-1.726l-1.414 1.414l3.274 3.274l5.702-6.84l-1.538-1.282z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M20 2H8a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm-6.933 12.481l-3.274-3.274l1.414-1.414l1.726 1.726l4.299-5.159l1.537 1.281l-5.702 6.84z"/><path fill="currentColor" d="M4 22h11v-2H4V8H2v12c0 1.103.897 2 2 2z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::TOGGLE_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M8 9c-1.628 0-3 1.372-3 3s1.372 3 3 3s3-1.372 3-3s-1.372-3-3-3z"/><path fill="currentColor" d="M16 6H8c-3.3 0-5.989 2.689-6 6v.016A6.01 6.01 0 0 0 8 18h8a6.01 6.01 0 0 0 6-5.994V12c-.009-3.309-2.699-6-6-6zm0 10H8a4.006 4.006 0 0 1-4-3.99C4.004 9.799 5.798 8 8 8h8c2.202 0 3.996 1.799 4 4.006A4.007 4.007 0 0 1 16 16zm4-3.984l.443-.004l.557.004h-1z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::EMBED_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M19 10V7c0-1.103-.897-2-2-2h-3c0-1.654-1.346-3-3-3S8 3.346 8 5H5c-1.103 0-2 .897-2 2v3.881l.659.239C4.461 11.41 5 12.166 5 13s-.539 1.59-1.341 1.88L3 15.119V19c0 1.103.897 2 2 2h3.881l.239-.659C9.41 19.539 10.166 19 11 19s1.59.539 1.88 1.341l.239.659H17c1.103 0 2-.897 2-2v-3c1.654 0 3-1.346 3-3s-1.346-3-3-3zm0 4h-2l-.003 5h-2.545c-.711-1.22-2.022-2-3.452-2s-2.741.78-3.452 2H5v-2.548C6.22 15.741 7 14.43 7 13s-.78-2.741-2-3.452V7h5V5a1 1 0 0 1 2 0v2h5v5h2a1 1 0 0 1 0 2z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::FILE_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M13 19v-4h3l-4-5l-4 5h3v4z"/><path fill="currentColor" d="M7 19h2v-2H7c-1.654 0-3-1.346-3-3c0-1.404 1.199-2.756 2.673-3.015l.581-.102l.192-.558C8.149 8.274 9.895 7 12 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-3v2h3c2.206 0 4-1.794 4-4a4.01 4.01 0 0 0-3.056-3.888C18.507 7.67 15.56 5 12 5C9.244 5 6.85 6.611 5.757 9.15C3.609 9.792 2 11.82 2 14c0 2.757 2.243 5 5 5z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M20 2H8c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zM8 16V4h12l.002 12H8z"/><path fill="currentColor" d="M4 8H2v12c0 1.103.897 2 2 2h12v-2H4V8z"/><path fill="currentColor" d="m12 12l-1-1l-2 3h10l-4-6z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><circle cx="7.499" cy="9.5" r="1.5" fill="currentColor"/><path fill="currentColor" d="m10.499 14l-1.5-2l-3 4h12l-4.5-6z"/><path fill="currentColor" d="M19.999 4h-16c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm-16 14V6h16l.002 12H3.999z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M18 7c0-1.103-.897-2-2-2H4c-1.103 0-2 .897-2 2v10c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-3.333L22 17V7l-4 3.333V7zm-1.998 10H4V7h12l.001 4.999L16 12l.001.001l.001 4.999z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::POST_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M19 7a1 1 0 0 0-1-1h-8v2h7v5h-3l3.969 5L22 13h-3V7zM5 17a1 1 0 0 0 1 1h8v-2H7v-5h3L6 6l-4 5h3v6z"/></svg>';

            case CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M13 9h-2v3H8v2h3v3h2v-3h3v-2h-3z"/><path fill="currentColor" d="M20 5h-8.586L9.707 3.293A.996.996 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z"/></svg>';
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" width="36px" height="36px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 48 48"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4"><path stroke-linejoin="round" d="M4 32.083c0-1.202.266-2.395.971-3.368C7.045 25.85 12.671 20 24 20c11.33 0 16.955 5.851 19.029 8.715c.705.973.971 2.166.971 3.368A7.917 7.917 0 0 1 36.083 40H11.917A7.917 7.917 0 0 1 4 32.083Z"/><path d="M12 9v4m2 9v4M36 9v4m-2 9v4M24 7v6m0 7v8m16-2.557C36.906 22.78 31.808 20 24 20s-12.906 2.779-16 5.443"/></g></svg>';
    }
}