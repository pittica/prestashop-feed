<?php

/**
 * PrestaShop Module - pitticafeed
 *
 * Copyright 2022 Pittica S.r.l.
 *
 * @category  Module
 * @package   Pittica\PrestaShop\Module\Feed
 * @author    Lucio Benini <info@pittica.com>
 * @copyright 2022 Pittica S.r.l.
 * @license   http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link      https://github.com/pittica/prestashop-feed
 */

namespace Pittica\PrestaShop\Module\Feed\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents the offer.
 *
 * @category Module
 * @package  Pittica\PrestaShop\Module\Feed
 * @author   Lucio Benini <info@pittica.com>
 * @license  http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link     https://github.com/pittica/prestashop-feed/tree/main/src/Entity/PitticaFeedOffer.php
 * @since    1.0.0
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pittica\PrestaShop\Module\Feed\Repository\PitticaFeedOfferRepository")
 */
class PitticaFeedOffer
{
    /**
     * Shop ID.
     *
     * @var   integer
     * @since 1.0.0
     *
     * @ORM\Id
     * @ORM\Column(name="id_shop", type="integer", nullable=false)
     */
    protected $shopId;

    /**
     * Product ID.
     *
     * @var   integer
     * @since 1.0.0
     *
     * @ORM\Id
     * @ORM\Column(name="id_product", type="integer", nullable=false)
     */
    protected $productId;

    /**
     * Product Attribute ID.
     *
     * @var   integer
     * @since 1.0.0
     *
     * @ORM\Id
     * @ORM\Column(name="id_product_attribute", type="integer", nullable=false)
     */
    protected $productAttributeId = 0;

    /**
     * Category ID.
     *
     * @var   integer|null
     * @since 1.0.0
     *
     * @ORM\Column(name="id_category", type="integer", nullable=true)
     */
    protected $categoryId = null;

    /**
     * Currency ID.
     *
     * @var   integer|null
     * @since 1.0.0
     *
     * @ORM\Column(name="id_currency", type="integer", nullable=true)
     */
    protected $currencyId = null;

    /**
     * Name.
     *
     * @var   string
     * @since 1.0.0
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * Brand name.
     *
     * @var   string|null
     * @since 1.0.0
     *
     * @ORM\Column(name="brand", type="string", nullable=true)
     */
    protected $brand;

    /**
     * Description.
     *
     * @var   string|null
     * @since 1.0.0
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    protected $description;

    /**
     * Original price.
     *
     * @var   float|null
     * @since 1.0.0
     *
     * @ORM\Column(name="original_price", type="decimal", nullable=true)
     */
    protected $originalPrice;

    /**
     * Price.
     *
     * @var   float|null
     * @since 1.0.0
     *
     * @ORM\Column(name="price", type="decimal", nullable=true)
     */
    protected $price;

    /**
     * Link.
     *
     * @var   string|null
     * @since 1.0.0
     *
     * @ORM\Column(name="link", type="string", nullable=true)
     */
    protected $link;

    /**
     * Stock.
     *
     * @var   integer
     * @since 1.0.0
     *
     * @ORM\Column(name="stock", type="integer", nullable=false)
     */
    protected $stock;

    /**
     * Categories.
     *
     * @var   string|null
     * @since 1.0.0
     *
     * @ORM\Column(name="categories", type="string", nullable=true)
     */
    protected $categories;

    /**
     * Image 1.
     *
     * @var   string|null
     * @since 1.0.0
     *
     * @ORM\Column(name="image_first", type="string", nullable=true)
     */
    protected $imageFirst;

    /**
     * Image 2.
     *
     * @var   string|null
     * @since 1.0.0
     *
     * @ORM\Column(name="image_second", type="string", nullable=true)
     */
    protected $imageSecond;

    /**
     * Image 3.
     *
     * @var   string|null
     * @since 1.0.0
     *
     * @ORM\Column(name="image_third", type="string", nullable=true)
     */
    protected $imageThird;

    /**
     * Part number or SKU code.
     *
     * @var   string|null
     * @since 1.0.0
     *
     * @ORM\Column(name="part_number", type="string", nullable=true)
     */
    protected $partNumber;

    /**
     * EAN code.
     *
     * @var   string|null
     * @since 1.0.0
     *
     * @ORM\Column(name="ean_code", type="string", nullable=true)
     */
    protected $eanCode;

    /**
     * Shipping cost.
     *
     * @var   float|null
     * @since 1.0.0
     *
     * @ORM\Column(name="shipping_cost", type="decimal", nullable=true)
     */
    protected $shippingCost;

    /**
     * Weight.
     *
     * @var   float|null
     * @since 1.0.0
     *
     * @ORM\Column(name="weight", type="decimal", nullable=true)
     */
    protected $weight;

    /**
     * Active.
     *
     * @var   bool
     * @since 1.0.0
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    protected $active = false;

    /**
     * Gets the Shop ID.
     *
     * @return integer
     * @since  1.0.0
     */
    public function getShopId() : int
    {
        return $this->shopId;
    }

    /**
     * Sets the Shop ID.
     *
     * @param integer $value Shop ID.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setShopId(int $value) : PitticaFeedOffer
    {
        $this->shopId = $value;

        return $this;
    }

    /**
     * Gets the Product ID.
     *
     * @return integer
     * @since  1.0.0
     */
    public function getProductId() : int
    {
        return $this->productId;
    }

    /**
     * Sets the Product ID.
     *
     * @param integer $value Product ID.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setProductId(int $value) : PitticaFeedOffer
    {
        $this->productId = $value;

        return $this;
    }

    /**
     * Gets the Product Attribute ID.
     *
     * @return integer
     * @since  1.0.0
     */
    public function getProductAttributeId() : int
    {
        return $this->productAttributeId;
    }

    /**
     * Sets the Product Attribute ID.
     *
     * @param integer $value Product Attribute ID.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setProductAttributeId(int $value) : PitticaFeedOffer
    {
        $this->productAttributeId = $value;

        return $this;
    }

    /**
     * Gets the Category ID.
     *
     * @return integer|null
     * @since  1.0.0
     */
    public function getCategoryId() : ?int
    {
        return $this->categoryId;
    }

    /**
     * Sets the Category ID.
     *
     * @param integer|null $value Category ID.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setCategoryId(?int $value) : PitticaFeedOffer
    {
        $this->categoryId = $value;

        return $this;
    }

    /**
     * Gets the Currency ID.
     *
     * @return integer|null
     * @since  1.0.0
     */
    public function getCurrencyId() : ?int
    {
        return $this->currencyId;
    }

    /**
     * Sets the Currency ID.
     *
     * @param integer|null $value Currency ID.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setCurrencyId(?int $value = null) : PitticaFeedOffer
    {
        $this->currencyId = $value;

        return $this;
    }
    
    /**
     * Gets the name.
     *
     * @return string
     * @since  1.0.0
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $value Name.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setName(string $value) : PitticaFeedOffer
    {
        $this->name = $value;

        return $this;
    }

    /**
     * Gets the brand name.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function getBrand() : ?string
    {
        return $this->brand;
    }

    /**
     * Sets the brand name.
     *
     * @param string|null $value Description.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setBrand(?string $value) : PitticaFeedOffer
    {
        $this->brand = $value;

        return $this;
    }

    /**
     * Gets the description.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }

    /**
     * Sets the description.
     *
     * @param string|null $value Description.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setDescription(?string $value) : PitticaFeedOffer
    {
        $this->description = $value;

        return $this;
    }

    /**
     * Gets the original price.
     *
     * @return float|null
     * @since  1.0.0
     */
    public function getOriginalPrice() : ?float
    {
        return $this->originalPrice;
    }

    /**
     * Sets the original price.
     *
     * @param float|null $value Original price.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setOriginalPrice(?float $value) : PitticaFeedOffer
    {
        $this->originalPrice = $value;

        return $this;
    }

    /**
     * Gets the price.
     *
     * @return float|null
     * @since  1.0.0
     */
    public function getPrice() : ?float
    {
        return $this->price;
    }

    /**
     * Sets the price.
     *
     * @param float|null $value Price.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setPrice(?float $value) : PitticaFeedOffer
    {
        $this->price = $value;

        return $this;
    }

    /**
     * Gets the link.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function getLink() : ?string
    {
        return $this->link;
    }

    /**
     * Sets the link.
     *
     * @param string|null $value Link.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setLink(?string $value) : PitticaFeedOffer
    {
        $this->link = $value;

        return $this;
    }

    /**
     * Gets the stock.
     *
     * @return integer
     * @since  1.0.0
     */
    public function getStock() : int
    {
        return $this->stock;
    }

    /**
     * Sets the stock.
     *
     * @param integer $value Stock.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setStock(int $value) : PitticaFeedOffer
    {
        $this->stock = $value;

        return $this;
    }

    /**
     * Gets the categories.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function getCategories() : ?string
    {
        return $this->categories;
    }

    /**
     * Sets the categories.
     *
     * @param string|null $value Categories.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setCategories(?string $value) : PitticaFeedOffer
    {
        $this->categories = $value;

        return $this;
    }

    /**
     * Gets the first image.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function getImageFirst() : ?string
    {
        return $this->imageFirst;
    }

    /**
     * Sets the first image.
     *
     * @param string|null $value First Image.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setImageFirst(?string $value) : PitticaFeedOffer
    {
        $this->imageFirst = $value;

        return $this;
    }

    /**
     * Gets the second image.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function getImageSecond() : ?string
    {
        return $this->imageSecond;
    }

    /**
     * Sets the second image.
     *
     * @param string|null $value Second image.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setImageSecond(?string $value) : PitticaFeedOffer
    {
        $this->imageSecond = $value;

        return $this;
    }

    /**
     * Gets the third image.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function getImageThird() : ?string
    {
        return $this->imageThird;
    }

    /**
     * Sets the third image.
     *
     * @param string|null $value Third image.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setImageThird(?string $value) : PitticaFeedOffer
    {
        $this->imageThird = $value;

        return $this;
    }

    /**
     * Gets the part number or SKU code.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function getPartNumber() : ?string
    {
        return $this->partNumber;
    }

    /**
     * Sets the part number or SKU code.
     *
     * @param string|null $value Part number or SKU code.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setPartNumber(?string $value) : PitticaFeedOffer
    {
        $this->partNumber = $value;

        return $this;
    }

    /**
     * Gets the EAN code.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function getEanCode() : ?string
    {
        return $this->eanCode;
    }

    /**
     * Sets the EAN code.
     *
     * @param string|null $value EAN code.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setEanCode(?string $value) : PitticaFeedOffer
    {
        $this->eanCode = $value;

        return $this;
    }

    /**
     * Gets the shipping cost.
     *
     * @return float|null
     * @since  1.0.0
     */
    public function getShippingCost() : ?float
    {
        return $this->shippingCost;
    }

    /**
     * Sets the shipping cost.
     *
     * @param float|null $value Shipping cost.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setShippingCost(?float $value) : PitticaFeedOffer
    {
        $this->shippingCost = $value;

        return $this;
    }

    /**
     * Gets the weight.
     *
     * @return float|null
     * @since  1.0.0
     */
    public function getWeight() : ?float
    {
        return $this->weight;
    }

    /**
     * Sets the weight.
     *
     * @param float|null $value Weight.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setWeight(?float $value) : PitticaFeedOffer
    {
        $this->weight = $value;

        return $this;
    }

    /**
     * Gets a value indicating whether the offer is active.
     *
     * @return boolean
     * @since  1.0.0
     */
    public function getActive() : bool
    {
        return $this->active;
    }

    /**
     * Sets a value indicating whether the offer is active.
     *
     * @param boolean $value A value indicating whether the offer is active.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function setActive(bool $value) : PitticaFeedOffer
    {
        $this->active = $value;

        return $this;
    }

    /**
     * Toggle active status.
     *
     * @return PitticaFeedOffer
     * @since  1.0.0
     */
    public function toggleActive() : PitticaFeedOffer
    {
        $this->active = !$this->active;

        return $this;
    }

    /**
     * Gets a value indicating whether the has a sale price.
     *
     * @return boolean
     * @since  1.0.0
     */
    public function hasSalePrice() : bool
    {
        return $this->getOriginalPrice() !== $this->getPrice();
    }

    /**
     * Gets a unique code.
     *
     * @return string
     * @since  1.0.0
     */
    public function getUniqueCode() : string
    {
        return $this->getProductId() . ($this->getProductAttributeId() ? ('-' . $this->getProductAttributeId()) : '');
    }

    /**
     * Gets the part number or EAN code.
     *
     * @return string|null
     * @since  1.0.0
     */
    public function getPartNumberOrEanCode() : ?string
    {
        return $this->getPartNumber() ? $this->getPartNumber() : $this->getEanCode();
    }

    /**
     * Converts the current object to an array.
     *
     * @return array
     * @since  1.0.0
     */
    public function toArray() : array
    {
        return [
            'id_shop'              => $this->getShopId(),
            'id_product'           => $this->getProductId(),
            'id_product_attribute' => $this->getProductAttributeId(),
            'id_category'          => $this->getCategoryId(),
            'id_currency'          => $this->getCurrencyId(),
            'name'                 => $this->getName(),
            'brand'                => $this->getBrand(),
            'description'          => $this->getDescription(),
            'original_price'       => $this->getOriginalPrice(),
            'price'                => $this->getPrice(),
            'link'                 => $this->getLink(),
            'stock'                => $this->getStock(),
            'categories'           => $this->getCategories(),
            'image_first'          => $this->getImageFirst(),
            'image_second'         => $this->getImageSecond(),
            'image_third'          => $this->getImageThird(),
            'part_number'          => $this->getPartNumber(),
            'ean_code'             => $this->getEanCode(),
            'shipping_cost'        => $this->getShippingCost(),
            'weight'               => $this->getWeight(),
            'active'               => $this->getActive(),
        ];
    }
}
