export class Recipe {
    id: string = '';
    author_username: string = '';
    name: string = '';
    description: string = '';
    preparation_description: string = '';
    category: string = '';
    image_url: string = '';
    creation_date: Date = new Date();
}

export interface Category {
    id: number;
    name: string;
}
