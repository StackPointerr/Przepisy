import { Component, OnInit } from '@angular/core';
import { MenuComponent } from '../core/blocks/menu/menu.component';
import { RecipesService } from '../core/services/recipes.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Recipe } from '../core/models/recipe.model';
import { CommonModule } from '@angular/common';
import { environment } from '../../environments/environment';

@Component({
    selector: 'app-recipe',
    standalone: true,
    imports: [MenuComponent, CommonModule],
    templateUrl: './recipe.component.html',
    styleUrl: './recipe.component.scss',
})
export class RecipeComponent implements OnInit {
    constructor(private recipeService: RecipesService, private route: ActivatedRoute, private router: Router) {}

    recipe: Recipe = new Recipe();
    environment = environment;

    ngOnInit(): void {
        const id = this.route.snapshot.paramMap.get('id');

        if (id)
            this.recipeService.getRecipe(id).subscribe({
                next: (recipe) => {
                    this.recipe = recipe;
                },
            });
    }
}
