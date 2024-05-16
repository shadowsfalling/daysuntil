<?php
namespace DaysUntil\Model;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: "Category", description: "Category model")]
class Category
{
    #[OA\Property(property: "id", type: "integer", description: "The unique identifier of the Category")]
    public $id;

    #[OA\Property(property: "name", type: "string", description: "The name of the Category")]
    public $name;

    #[OA\Property(property: "color", type: "string", description: "The color associated with the Category")]
    public $color;

    #[OA\Property(property: "userId", type: "integer", description: "The identifier of the User that this Category belongs to")]
    public $userId;

    public function __construct($id, $name, $color, $userId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
        $this->userId = $userId;
    }
}