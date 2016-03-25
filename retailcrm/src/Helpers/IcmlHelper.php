<?php

class IcmlHelper
{
    protected $shop;
    protected $file;
    protected $tmpFile;

    protected $properties = array(
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

    protected $xml;
    protected $categories;
    protected $offers;

    protected $chunk = 500;
    protected $fileLifeTime = 3600;

    public function __construct($shop, $file)
    {
        $this->shop = $shop;
        $this->file = $file;
        $this->tmpFile = sprintf('%s.tmp', $file);
    }

    public function generate($categories, $offers)
    {
        if (file_exists($this->tmpFile)) {
            if (filectime($this->tmpFile) + $this->fileLifeTime < time()) {
                unlink($this->tmpFile);
                $this->writeHead();
            }
        } else {
            $this->writeHead();
        }

        try {
            if (!empty($categories)) {
                $this->writeCategories($categories);
                unset($categories);
            }

            if (!empty($offers)) {
                $this->writeOffers($offers);
                unset($offers);
            }

            $dom = dom_import_simplexml(simplexml_load_file($this->tmpFile))->ownerDocument;
            $dom->formatOutput = true;
            $formatted = $dom->saveXML();

            unset($dom, $this->xml);

            file_put_contents($this->tmpFile, $formatted);
            rename($this->tmpFile, $this->file);
        } catch (Exception $e) {
            unlink($this->tmpFile);
        }
    }

    private function loadXml()
    {
        return new SimpleXMLElement(
                $this->tmpFile,
                LIBXML_NOENT | LIBXML_NOCDATA | LIBXML_COMPACT | LIBXML_PARSEHUGE,
                true
        );
    }

    private function writeHead()
    {
        $string = sprintf(
            '<?xml version="1.0" encoding="UTF-8"?><yml_catalog date="%s"><shop><name>%s</name><categories/><offers/></shop></yml_catalog>',
            date('Y-m-d H:i:s'),
            $this->shop
        );

        file_put_contents($this->tmpFile, $string, LOCK_EX);
    }

    private function writeCategories($categories)
    {
        $chunkCategories = array_chunk($categories, $this->chunk);
        foreach ($chunkCategories as $categories) {
            $this->xml = $this->loadXml();

            $this->categories = $this->xml->shop->categories;
            $this->addCategories($categories);

            $this->xml->asXML($this->tmpFile);
        }

        unset($this->categories);
    }

    private function writeOffers($offers)
    {
        $chunkOffers = array_chunk($offers, $this->chunk);
        foreach ($chunkOffers as $offers) {
            $this->xml = $this->loadXml();

            $this->offers = $this->xml->shop->offers;
            $this->addOffers($offers);

            $this->xml->asXML($this->tmpFile);
        }

        unset($this->offers);
    }

    private function addCategories($categories)
    {
        $categories = DataHelper::filterRecursive($categories);

        foreach($categories as $category) {
            if (!array_key_exists('name', $category) || !array_key_exists('id', $category)) {
                continue;
            }

            $e = $this->categories->addChild('category', $category['name']);

            $e->addAttribute('id', $category['id']);

            if (array_key_exists('parentId', $category) && $category['parentId'] > 0) {
                $e->addAttribute('parentId', $category['parentId']);
            }
        }
     }

    private function addOffers($offers)
    {
        $offers = DataHelper::filterRecursive($offers);

        foreach ($offers as $key => $offer) {

            if (!array_key_exists('id', $offer)) {
                continue;
            }

            $e = $this->offers->addChild('offer');

            $e->addAttribute('id', $offer['id']);

            if (!array_key_exists('productId', $offer) || empty($offer['productId'])) {
                $offer['productId'] = $offer['id'];
            }
            $e->addAttribute('productId', $offer['productId']);

            if (!empty($offer['quantity'])) {
                $e->addAttribute('quantity', (int) $offer['quantity']);
            } else {
                $e->addAttribute('quantity', 0);
            }

            if (is_array($offer['categoryId'])) {
                foreach ($offer['categoryId'] as $categoryId) {
                    $e->addChild('categoryId', $categoryId);
                }
            } else {
                $e->addChild('categoryId', $offer['categoryId']);
            }

            if (!array_key_exists('name', $offer) || empty($offer['name'])) {
                $offer['name'] = 'Без названия';
            }

            if (!array_key_exists('productName', $offer) || empty($offer['productName'])) {
                $offer['productName'] = $offer['name'];
            }

            unset($offer['id'], $offer['productId'], $offer['categoryId'], $offer['quantity']);
            array_walk($offer, array($this, 'setOffersProperties'), $e);

            if (array_key_exists('params', $offer) && !empty($offer['params'])) {
                array_walk($offer['params'], array($this, 'setOffersParams'), $e);
            }

            unset($offers[$key]);
        }
    }

    private function setOffersProperties($value, $key, &$e) {
        if (in_array($key, $this->properties) && $key != 'params') {
            $e->addChild($key, htmlspecialchars($value));
        }
    }

    private function setOffersParams($value, $key, &$e) {
        if (
            array_key_exists('code', $value) &&
            array_key_exists('name', $value) &&
            array_key_exists('value', $value) &&
            !empty($value['code']) &&
            !empty($value['name']) &&
            !empty($value['value'])
        ) {
            $param = $e->addChild('param', htmlspecialchars($value['value']));
            $param->addAttribute('code', $value['code']);
            $param->addAttribute('name', htmlspecialchars($value['name']));
        }
    }
}
