import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';
import { Observable, map } from 'rxjs';
import { Category, Recipe } from '../models/recipe.model';
import { Router } from '@angular/router';

@Injectable({
    providedIn: 'root',
})
export class RecipesService {
    constructor(private http: HttpClient, private router: Router) {}

    public getAllCategories(): Observable<Array<Category>> {
        return this.http.post(environment.apiUrl + '/recipes/getAllCategories.php', {}).pipe(
            map((response: any) => {
                if (response.success) return response.data;
                return [];
            })
        );
    }

    public getRecipes(name: string = '', category: string = ''): Observable<Array<Recipe>> {
        return this.http.post(environment.apiUrl + '/recipes/getRecipes.php', { name: name, category: category }).pipe(
            map((response: any) => {
                if (response.error) {
                    alert(response.error);
                    return [];
                }

                return response.data;
            })
        );
    }

    public addRecipe(name: string, description: string, category: string, preparation_description: string, image: string): Observable<any> {
        return this.http.post(environment.apiUrl + '/recipes/addRecipe.php', { name, description, category, preparation_description, image });
    }

    public getRecipe(id: string): Observable<Recipe> {
        return this.http.post(environment.apiUrl + '/recipes/getRecipe.php', { id }).pipe(
            map((response: any) => {
                if (response.errors.id) {
                    alert(response.errors.id);
                    this.router.navigateByUrl('/');
                    return [];
                }

                return response.data;
            })
        );
    }

    public removeRecipe(id: string): Observable<any> {
        return this.http.post(environment.apiUrl + '/recipes/removeRecipe.php', { id });
    }
}
