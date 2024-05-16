export class Category {
    id: number;
    name: string;
    color: string;

    constructor(
        id: number,
        name: string,
        color: string
    ) {
        this.id = id;
        this.color = color;
        this.name = name;
    }
}