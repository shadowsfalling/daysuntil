<?php

namespace DaysUntil\Model;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: "Countdown", description: "Countdown model")]
class Countdown
{
    #[OA\Property(property: "id", type: "integer", description: "The unique identifier of the Countdown")]
    public $id;

    #[OA\Property(property: "title", type: "string", description: "The title of the Countdown")]
    public $title;

    #[OA\Property(property: "datetime", type: "string", format: "date-time", description: "The specific date and time of the Countdown")]
    public $datetime;

    #[OA\Property(property: "isPublic", type: "boolean", description: "Indicates whether the Countdown is public or private")]
    public $isPublic;

    #[OA\Property(property: "categoryId", type: "integer", description: "The identifier of the Category associated with this Countdown")]
    public $categoryId;

    #[OA\Property(property: "userId", type: "integer", description: "The identifier of the User who owns this Countdown")]
    public $userId;

    public function __construct($id, $title, $datetime, $isPublic, $categoryId, $userId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->datetime = $datetime;
        $this->isPublic = $isPublic;
        $this->categoryId = $categoryId;
        $this->userId = $userId;
    }
}