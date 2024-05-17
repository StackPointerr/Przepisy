import { Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { RegisterComponent } from './register/register.component';
import { MainComponent } from './main/main.component';
import { authGuard } from './core/guards/auth.guard';
import { NewRecipeComponent } from './new-recipe/new-recipe.component';
import { RecipeComponent } from './recipe/recipe.component';
import { RecipeBookComponent } from './recipe-book/recipe-book.component';

export const routes: Routes = [
    { path: '', component: MainComponent, canActivate: [authGuard] },
    { path: 'new-recipe', component: NewRecipeComponent, canActivate: [authGuard] },
    { path: 'my-book', component: RecipeBookComponent, canActivate: [authGuard] },
    { path: 'recipe/:id', component: RecipeComponent, canActivate: [authGuard] },
    { path: 'register', component: RegisterComponent },
    { path: 'login', component: LoginComponent },
];
