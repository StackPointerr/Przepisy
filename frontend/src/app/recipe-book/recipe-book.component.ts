import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { MenuComponent } from '../core/blocks/menu/menu.component';
import { environment } from '../../environments/environment';
import { Recipe } from '../core/models/recipe.model';
import { RouterLink } from '@angular/router';
import { RecipesService } from '../core/services/recipes.service';
import { BookService } from '../core/services/book.service';

@Component({
    selector: 'app-recipe-book',
    standalone: true,
    imports: [MenuComponent, CommonModule, RouterLink],
    templateUrl: './recipe-book.component.html',
    styleUrl: './recipe-book.component.scss',
})
export class RecipeBookComponent implements OnInit {
    environment = environment;
    recipes: Array<Recipe> = [];

    constructor(private recipeService: RecipesService, private bookService: BookService) {}

    ngOnInit(): void {
        this.fetchBook();
    }

    fetchBook() {
        this.bookService.getBook().subscribe({
            next: (recipes) => {
                this.recipes = recipes;
            },
        });
    }

    removeFromBook(id: string) {
        this.bookService.removeFromBook(id).subscribe({
            next: (response) => {
                if (response.success) {
                    this.fetchBook();
                    alert('Pomyślnie usunięto przepis z książki kucharskiej!');
                }
            },
        });
    }
}
