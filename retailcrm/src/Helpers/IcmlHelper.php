<?php

class IcmlHelper
{
    protected $shop;
    protected $file;

    protected $properties;
    protected $params;

    protected $document;
    protected $categories;
    protected $offers;

    public function __construct($shop, $file)
    {
        $this->shop = $shop;
        $this->file = $file;

        $this->properties = array(
            'name',
            'productName',
            'price',
            'purchasePrice',
            'vendor',
            'picture',
            'url',
            'xmlId',
            'productActivity'
        );

        $this->params = array(
            'article' => 'Артикул',
            'color' => 'Цвет',
            'weight' => 'Вес',
            'size' => 'Размер',
        );
    }

    public function generate($categories, $offers)
    {
        $string = '<?xml version="1.0" encoding="UTF-8"?>
            <yml_catalog date="'.date('Y-m-d H:i:s').'">
                <shop>
                    <name>'.$this->shop.'</name>
                    <categories/>
                    <offers/>
                </shop>
            </yml_catalog>
        ';

        $xml = new SimpleXMLElement(
            $string,
            LIBXML_NOENT |LIBXML_NOCDATA | LIBXML_COMPACT | LIBXML_PARSEHUGE
        );

        $this->document = new DOMDocument();
        $this->document->preserveWhiteSpace = false;
        $this->document->formatOutput = true;
        $this->document->loadXML($xml->asXML());

        $this->categories = $this->document
            ->getElementsByTagName('categories')->item(0);
        $this->offers = $this->document
            ->getElementsByTagName('offers')->item(0);

        $this->addCategories($categories);
        $this->addOffers($offers);

        $this->document->saveXML();
        $this->document->save($this->file);
    }

    private function addCategories($categories)
    {
        foreach($categories as $category) {
            if (!array_key_exists('name', $category) || !array_key_exists('id', $category)) {
                continue;
            }

            $e = $this->categories->appendChild(
                $this->document->createElement(
                    'category', $category['name']
                )
            );

            $e->setAttribute('id', $category['id']);

            if (array_key_exists('parentId', $category) && $category['parentId'] > 0) {
                $e->setAttribute('parentId', $category['parentId']);
            }
        }
     }

    private function addOffers($offers)
    {
        foreach ($offers as $offer) {

            if (!array_key_exists('id', $offer)) {
                continue;
            }

            $e = $this->offers->appendChild(
                $this->document->createElement('offer')
            );

            $e->setAttribute('id', $offer['id']);

            if (!array_key_exists('productId', $offer) || empty($offer['productId'])) {
                $offer['productId'] = $offer['id'];
            }
            $e->setAttribute('productId', $offer['productId']);

            if (!empty($offer['quantity'])) {
                $e->setAttribute('quantity', (int) $offer['quantity']);
            } else {
                $e->setAttribute('quantity', 0);
            }

            if (is_array($offer['categoryId'])) {
                foreach ($offer['categoryId'] as $categoryId) {
                    $e->appendChild(
                        $this->document->createElement('categoryId', $categoryId)
                    );
                }
            } else {
                $e->appendChild(
                    $this->document->createElement('categoryId', $offer['categoryId'])
                );
            }

            if (!array_key_exists('name', $offer) || empty($offer['name'])) {
                $offer['name'] = 'Без названия';
            }

            if (!array_key_exists('productName', $offer) || empty($offer['productName'])) {
                $offer['name'] = $offer['name'];
            }

            $offerKeys  = array_keys($offer);

            foreach ($offerKeys as $key) {
                if (in_array($key, $this->properties) && !is_null($offer[$key]) && $offer[$key] !== '') {
                    $e->appendChild(
                        $this->document->createElement($key)
                    )->appendChild(
                        $this->document->createTextNode($offer[$key])
                    );
                }

                if (
                    in_array($key, array_keys($this->params)) &&
                    !is_null($offer[$key]) && $offer[$key] !== ''
                ) {
                    $param = $this->document->createElement('param');
                    $param->setAttribute('code', $key);
                    $param->setAttribute('name', $this->params[$key]);
                    $param->appendChild(
                        $this->document->createTextNode($offer[$key])
                    );
                    $e->appendChild($param);
                }
            }
        }
    }
}
