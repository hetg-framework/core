<?php

namespace Hetg\Framework\Model;

class MealModel extends Model
{
    public static $table = 'meals';

    public static $primaryField = 'id';

    public static $fields = [
        'id' => 'integer',
        'title' => 'string',
        'price' => 'integer',
    ];

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var integer
     */
    private $price;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return MealModel
     */
    public function setTitle(string $title): MealModel
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     *
     * @return MealModel
     */
    public function setPrice(int $price): MealModel
    {
        $this->price = $price;

        return $this;
    }


}