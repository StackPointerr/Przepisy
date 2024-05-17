import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { MenuComponent } from '../core/blocks/menu/menu.component';
import { Category } from '../core/models/recipe.model';
import { RecipesService } from '../core/services/recipes.service';
import { Router } from '@angular/router';

@Component({
    selector: 'app-new-recipe',
    standalone: true,
    imports: [CommonModule, FormsModule, MenuComponent],
    templateUrl: './new-recipe.component.html',
    styleUrl: './new-recipe.component.scss',
})
export class NewRecipeComponent implements OnInit {
    categories: Array<Category> = [];

    name: string = '';
    description: string = '';
    category: string = '';
    image: string = '';
    preparation_description: string = '';

    response: any;

    constructor(private recipeService: RecipesService, private router: Router) {}

    ngOnInit(): void {
        this.recipeService.getAllCategories().subscribe({
            next: (categories) => {
                this.categories = categories;
            },
        });
    }

    getBase64(file: File): Promise<string> {
        return new Promise((resolve, reject) => {
            if (!file) resolve('');

            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                if (reader.result) resolve(reader.result.toString());
            };
        });
    }

    async addRecipe(imageInput: any): Promise<void> {
        let base64 = await this.getBase64(imageInput.files[0]);

        this.recipeService.addRecipe(this.name, this.description, this.category, this.preparation_description, base64).subscribe({
            next: (response) => {
                this.response = response;

                if (response.success) {
                    alert('Pomyślnie dodano nowy przepis!');
                    this.router.navigate(['/']);
                }
            },
        });
    }
}
