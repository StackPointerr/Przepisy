import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { RecipesService } from '../core/services/recipes.service';
import { Category, Recipe } from '../core/models/recipe.model';
import { MenuComponent } from '../core/blocks/menu/menu.component';
import { environment } from '../../environments/environment';
import { RouterLink } from '@angular/router';
import { AuthenticationService } from '../core/services/authorization.service';
import { BookService } from '../core/services/book.service';

@Component({
    selector: 'app-main',
    standalone: true,
    imports: [CommonModule, FormsModule, MenuComponent, RouterLink],
    templateUrl: './main.component.html',
    styleUrl: './main.component.scss',
})
export class MainComponent implements OnInit {
    name: string = '';
    category: string = '';

    environment = environment;

    recipes: Array<Recipe> = [];
    categories: Array<Category> = [];

    constructor(private recipeService: RecipesService, private bookService: BookService, public authenticationService: AuthenticationService) {}

    ngOnInit(): void {
        this.fetchCategories();
        this.fetchRecipes();
    }

    searchClick() {
        this.fetchRecipes(this.name, this.category);
    }

    fetchCategories() {
        this.recipeService.getAllCategories().subscribe({
            next: (categories) => {
                this.categories = categories;
            },
        });
    }

    fetchRecipes(name: string = '', category: string = '') {
        this.recipeService.getRecipes(name, category).subscribe({
            next: (recipes) => {
                this.recipes = recipes;
            },
        });
    }

    removeRecipe(id: string) {
        this.recipeService.removeRecipe(id).subscribe({
            next: (response: any) => {
                if (response.success) {
                    this.fetchRecipes();
                }
            },
        });
    }

    addToBook(id: string) {
        this.bookService.addToBook(id).subscribe({
            next: (response: any) => {
                if (response.success) alert('Pomyślnie dodano do książki z przepisami kucharskimi!');
                else if (response.errors.exists) alert(response.errors.exists);
            },
        });
    }
}
